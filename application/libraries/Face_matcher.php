<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Face Matcher Library — Strict cosine similarity with dimension enforcement
 * 
 * FIXES ROOT CAUSE (b): NEVER compares vectors of different dimensions.
 * Dimension mismatch = RuntimeException, NOT a silent wrong result.
 * 
 * FIXES ROOT CAUSE (c): Implements margin-based ambiguity rejection.
 */
class Face_matcher {

    const DIM = 512;

    /**
     * Assert vector has exactly 512 dimensions.
     * Throws RuntimeException on mismatch — NEVER returns a silent wrong result.
     * 
     * @param array $v
     * @throws RuntimeException
     */
    private function assert_vec(array $v) {
        if (count($v) !== self::DIM) {
            throw new RuntimeException(
                'Dimensi embedding tidak valid: ' . count($v) . 
                '. Diharapkan: ' . self::DIM . 
                '. JANGAN bandingkan vektor berdimensi berbeda.'
            );
        }
    }

    /**
     * Cosine similarity between two L2-normalized 512-D embeddings.
     * Since embeddings are already L2-normalized, cosine = dot product.
     * 
     * @param array $a 512-D embedding
     * @param array $b 512-D embedding
     * @return float Cosine similarity [-1.0, 1.0]
     * @throws RuntimeException if dimensions don't match
     */
    public function cosine(array $a, array $b) {
        $this->assert_vec($a);
        $this->assert_vec($b);

        $dot = 0.0;
        for ($i = 0; $i < self::DIM; $i++) {
            $dot += (float)$a[$i] * (float)$b[$i];
        }
        return $dot;
    }

    /**
     * Match a probe embedding against a set of gallery templates.
     * Returns the best score per employee (max across their 5 pose templates).
     * 
     * @param array $probe 512-D embedding
     * @param array $gallery Array of [em_code => [template1, template2, ...]]
     *                       Each template is a 512-D array.
     * @return array Sorted by score DESC: [em_code => best_score, ...]
     * @throws RuntimeException on dimension mismatch
     */
    public function match_1toN(array $probe, array $gallery) {
        $this->assert_vec($probe);

        $scores = [];
        foreach ($gallery as $em_code => $templates) {
            $best = -999.0;
            foreach ($templates as $template) {
                $score = $this->cosine($probe, $template);
                if ($score > $best) {
                    $best = $score;
                }
            }
            $scores[$em_code] = $best;
        }

        arsort($scores);
        return $scores;
    }

    /**
     * Match a probe against a single employee's templates (1:1 verification).
     * Returns the best score across their pose templates.
     * 
     * @param array $probe 512-D embedding
     * @param array $templates Array of 512-D templates for one employee
     * @return float Best cosine score
     * @throws RuntimeException on dimension mismatch
     */
    public function match_1to1(array $probe, array $templates) {
        $this->assert_vec($probe);

        $best = -999.0;
        foreach ($templates as $template) {
            $score = $this->cosine($probe, $template);
            if ($score > $best) {
                $best = $score;
            }
        }
        return $best;
    }

    /**
     * Apply decision rules with margin enforcement.
     * 
     * FIXES ROOT CAUSE (c): NEVER guesses when ambiguous.
     * 
     * @param array  $scores    Sorted scores [em_code => score], descending
     * @param float  $threshold Minimum score for acceptance
     * @param float  $min_margin Minimum gap between best and second-best
     * @return array [decision, em_code, best_score, second_score, margin, runner_up_code]
     */
    public function decide(array $scores, $threshold, $min_margin) {
        $codes  = array_keys($scores);
        $best   = isset($codes[0]) ? $scores[$codes[0]] : 0.0;
        $second = isset($codes[1]) ? $scores[$codes[1]] : 0.0;
        $margin = $best - $second;

        $best_code = isset($codes[0]) ? $codes[0] : null;
        $runner_up = isset($codes[1]) ? $codes[1] : null;

        if ($best < $threshold) {
            $decision = 'rejected_threshold';
            $best_code = null; // Don't claim a match
        } elseif ($margin < $min_margin) {
            $decision = 'rejected_ambiguous';
            // DO NOT pick either — both are uncertain
        } else {
            $decision = 'accepted';
        }

        return [
            'decision'       => $decision,
            'em_code'        => $best_code,
            'best_score'     => round($best, 6),
            'second_score'   => round($second, 6),
            'score_margin'   => round($margin, 6),
            'runner_up_code' => $runner_up,
        ];
    }
}
