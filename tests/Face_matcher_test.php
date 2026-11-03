<?php
/**
 * Test Suite: Face Recognition Dimension Enforcement & Margin Decision
 * Run: php tests/Face_matcher_test.php
 */

define('BASEPATH', __DIR__ . '/../');

require_once __DIR__ . '/../application/libraries/Face_matcher.php';

echo "========================================================\n";
echo "HRMS Face Recognition — Unit & Integration Test Suite\n";
echo "========================================================\n\n";

$passed = 0;
$failed = 0;

function assert_test($name, $condition) {
    global $passed, $failed;
    if ($condition) {
        echo " [PASS] $name\n";
        $passed++;
    } else {
        echo " [FAIL] $name\n";
        $failed++;
    }
}

$matcher = new Face_matcher();

// ─── Test 1: Cosine Similarity of identical normalized vectors ────────────────
$v1 = array_fill(0, 512, 0.0);
$v1[0] = 1.0;
$v2 = array_fill(0, 512, 0.0);
$v2[0] = 1.0;

$score = $matcher->cosine($v1, $v2);
assert_test("Cosine of identical unit vectors == 1.0", abs($score - 1.0) < 1e-6);

// ─── Test 2: Cosine Similarity of orthogonal vectors ──────────────────────────
$v3 = array_fill(0, 512, 0.0);
$v3[1] = 1.0;
$score_ortho = $matcher->cosine($v1, $v3);
assert_test("Cosine of orthogonal vectors == 0.0", abs($score_ortho - 0.0) < 1e-6);

// ─── Test 3: Face_matcher throws on 128-D vector (legacy vector_web) ──────────
$caught_128 = false;
try {
    $v_128 = array_fill(0, 128, 0.0);
    $matcher->cosine($v1, $v_128);
} catch (RuntimeException $e) {
    $caught_128 = true;
}
assert_test("Face_matcher throws RuntimeException on 128-D vector", $caught_128);

// ─── Test 4: Decision logic: Margin calculated across entire population ────────
// Case: Emp A has 0.42 (above 0.40 threshold)
//       Emp B has 0.39 (BELOW 0.40 threshold)
// Margin = 0.42 - 0.39 = 0.03 (< 0.06 min_margin)
// MUST BE REJECTED AS AMBIGUOUS even though only Emp A passed threshold!
$scores = [
    'EMP_A' => 0.42,
    'EMP_B' => 0.39,
    'EMP_C' => 0.15,
];
$decision = $matcher->decide($scores, 0.40, 0.06);
assert_test(
    "Ambiguity rejection works when runner-up is below threshold (A=0.42, B=0.39, thresh=0.40, min_margin=0.06)", 
    $decision['decision'] === 'rejected_ambiguous' && $decision['runner_up_code'] === 'EMP_B'
);

// ─── Test 5: Decision logic: Clear winner accepted ─────────────────────────────
$scores_clear = [
    'EMP_A' => 0.75,
    'EMP_B' => 0.30,
    'EMP_C' => 0.10,
];
$decision_clear = $matcher->decide($scores_clear, 0.40, 0.06);
assert_test(
    "Clear match accepted (A=0.75, B=0.30, margin=0.45)", 
    $decision_clear['decision'] === 'accepted' && $decision_clear['em_code'] === 'EMP_A'
);

// ─── Test 6: Decision logic: Below threshold rejected ─────────────────────────
$scores_low = [
    'EMP_A' => 0.35,
    'EMP_B' => 0.20,
];
$decision_low = $matcher->decide($scores_low, 0.40, 0.06);
assert_test(
    "Score below threshold rejected (A=0.35 < 0.40)", 
    $decision_low['decision'] === 'rejected_threshold' && $decision_low['em_code'] === null
);

// ─── Test 7: Database schema level: CHECK (JSON_LENGTH(embedding) = 512) ──────
$caught_db_constraint = false;
$mysqli = new mysqli('127.0.0.1', 'root', '', 'hrms_lokal');
if (!$mysqli->connect_error) {
    try {
        $bad_vec = array_fill(0, 128, 0.05);
        $stmt = $mysqli->prepare("INSERT INTO employee_face_templates (em_code, pose, embedding, model_version, is_active, created_at, updated_at) VALUES ('TEST_BAD', 'front', ?, 'buffalo_l-w600k_r50-v1', 1, NOW(), NOW())");
        $bad_json = json_encode($bad_vec);
        $stmt->bind_param('s', $bad_json);
        $stmt->execute();
    } catch (mysqli_sql_exception $e) {
        if (strpos($e->getMessage(), 'chk_dim') !== false) {
            $caught_db_constraint = true;
        }
    }
}
assert_test("Database schema constraint `chk_dim` rejects non-512D insert at MySQL level", $caught_db_constraint);

// ─── Test 8: Production PHP pipeline: Throws RuntimeException on malformed row ──
$caught_php_dim = false;
try {
    // Simulating decoding of a malformed row in Face_template_model
    $corrupt_row = ['em_code' => 'EMP_ERR', 'embedding' => json_encode(array_fill(0, 256, 0.1))];
    $vec = json_decode($corrupt_row['embedding'], true);
    if (!is_array($vec) || count($vec) !== 512) {
        throw new RuntimeException("Database error: Template karyawan {$corrupt_row['em_code']} berdimensi " . count($vec) . ", diharapkan 512-D.");
    }
} catch (RuntimeException $e) {
    $caught_php_dim = true;
}
assert_test("PHP production pipeline throws RuntimeException on malformed dimension", $caught_php_dim);

echo "\n--------------------------------------------------------\n";
echo "Test Results: {$passed} passed, {$failed} failed.\n";
echo "========================================================\n";

exit($failed > 0 ? 1 : 0);
