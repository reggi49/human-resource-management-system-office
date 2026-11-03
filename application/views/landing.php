<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS Enterprise'; ?> | Platform SDM & Presensi AI</title>
    
    <!-- Google Fonts: Plus Jakarta Sans & Inter -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Plus+Jakarta+Sans:wght@500;600;700;800&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --bg-canvas: #FFFFFF;
            --bg-subtle: #F8FAFC;
            --border-color: #E2E8F0;
            --text-heading: #0F172A;
            --text-body: #334155;
            --text-muted: #64748B;
            --brand-primary: #4F46E5;
            --brand-primary-hover: #4338CA;
            --brand-light: #EEF2FF;
            --accent-emerald: #10B981;
            --shadow-sm: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
            --shadow-md: 0 4px 6px -1px rgba(0, 0, 0, 0.05), 0 2px 4px -1px rgba(0, 0, 0, 0.03);
            --shadow-lg: 0 10px 15px -3px rgba(0, 0, 0, 0.05), 0 4px 6px -2px rgba(0, 0, 0, 0.02);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
            background-color: var(--bg-canvas);
            color: var(--text-body);
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }

        /* Top Bar Navigation */
        .navbar-rippling {
            background: rgba(255, 255, 255, 0.9);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            padding: 16px 0;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .brand-text {
            font-weight: 800;
            font-size: 17px;
            letter-spacing: -0.3px;
            color: var(--text-heading);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .brand-icon {
            width: 32px;
            height: 32px;
            border-radius: 8px;
            background: var(--brand-primary);
            color: #FFFFFF;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 15px;
        }

        .btn-brand-primary {
            background: var(--brand-primary);
            color: #FFFFFF !important;
            font-weight: 600;
            font-size: 13px;
            padding: 9px 20px;
            border-radius: 8px;
            border: none;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .btn-brand-primary:hover {
            background: var(--brand-primary-hover);
            text-decoration: none;
            box-shadow: var(--shadow-md);
        }

        .btn-brand-outline {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            color: var(--text-heading);
            font-weight: 600;
            font-size: 13px;
            padding: 9px 18px;
            border-radius: 8px;
            transition: all 0.2s ease;
        }

        .btn-brand-outline:hover {
            border-color: #94A3B8;
            text-decoration: none;
            background: var(--bg-subtle);
        }

        /* Hero */
        .hero-wrap {
            padding: 100px 0 80px;
            background: radial-gradient(circle at 50% 0%, #EEF2FF 0%, transparent 60%);
        }

        .hero-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 6px 16px;
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 50px;
            color: var(--brand-primary);
            font-size: 12px;
            font-weight: 700;
            box-shadow: var(--shadow-sm);
            margin-bottom: 24px;
        }

        .hero-h1 {
            font-size: 54px;
            font-weight: 800;
            letter-spacing: -1.8px;
            line-height: 1.12;
            color: var(--text-heading);
            margin-bottom: 24px;
        }

        .hero-p {
            font-size: 18px;
            color: var(--text-muted);
            line-height: 1.6;
            max-width: 620px;
            margin-bottom: 36px;
        }

        /* Card Features */
        .feature-box {
            background: #FFFFFF;
            border: 1px solid var(--border-color);
            border-radius: 16px;
            padding: 32px;
            height: 100%;
            box-shadow: var(--shadow-sm);
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .feature-box:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: #CBD5E1;
        }

        .feature-icon-wrapper {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            background: var(--brand-light);
            color: var(--brand-primary);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            margin-bottom: 24px;
        }

        .feature-h3 {
            font-size: 19px;
            font-weight: 700;
            color: var(--text-heading);
            letter-spacing: -0.3px;
            margin-bottom: 12px;
        }

        .feature-p-desc {
            font-size: 14px;
            color: var(--text-muted);
            line-height: 1.6;
            margin: 0;
        }

        /* Stat Strip */
        .stat-strip {
            background: var(--bg-subtle);
            border-top: 1px solid var(--border-color);
            border-bottom: 1px solid var(--border-color);
            padding: 50px 0;
        }

        .stat-val {
            font-size: 42px;
            font-weight: 800;
            color: var(--text-heading);
            letter-spacing: -1.5px;
            font-family: 'Inter', sans-serif;
        }

        .stat-lbl {
            font-size: 13px;
            font-weight: 600;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 0.8px;
            margin-top: 4px;
        }

        footer {
            border-top: 1px solid var(--border-color);
            padding: 40px 0;
            font-size: 13px;
            color: var(--text-muted);
            background: #FFFFFF;
        }
    </style>
</head>
<body>

    <!-- Header Navigation -->
    <nav class="navbar navbar-expand-lg navbar-rippling sticky-top">
        <div class="container">
            <a class="brand-text" href="<?php echo base_url('landing'); ?>">
                <div class="brand-icon"><i class="fa-solid fa-cube"></i></div>
                <span><?php echo isset($settingsvalue->sitetitle) ? strtoupper($settingsvalue->sitetitle) : 'HRMS ENTERPRISE'; ?></span>
            </a>

            <div class="ml-auto d-flex align-items-center gap-2">
                <a href="<?php echo base_url('login'); ?>" class="btn-brand-outline mr-2">
                    <i class="fa-solid fa-lock mr-1"></i> Admin Login
                </a>
                <a href="<?php echo base_url('portal'); ?>" class="btn-brand-primary">
                    <i class="fa-solid fa-arrow-right-to-bracket mr-1"></i> Portal Karyawan
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-wrap">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-7">
                    <div class="hero-badge">
                        <i class="fa-solid fa-sparkles"></i> Enterprise HRIS & Biometric AI
                    </div>
                    <h1 class="hero-h1">
                        Sistem Penggajian & Presensi Biometrik Wajah Modern
                    </h1>
                    <p class="hero-p">
                        Platform manajemen SDM terpadu dengan verifikasi AI 512D Vector Embedding, Kalkulasi PPh 21 TER (PP 58/2023), serta Portal Mandiri Karyawan Real-Time.
                    </p>
                    <div class="d-flex align-items-center">
                        <a href="<?php echo base_url('portal'); ?>" class="btn-brand-primary mr-3 py-3 px-4" style="font-size: 14px;">
                            <i class="fa-solid fa-arrow-right-to-bracket mr-2"></i> Masuk Portal Karyawan
                        </a>
                        <a href="<?php echo base_url('login'); ?>" class="btn-brand-outline py-3 px-4" style="font-size: 14px;">
                            <i class="fa-solid fa-shield-halved mr-2"></i> Portal Admin / HRD
                        </a>
                    </div>
                </div>
                <div class="col-lg-5 mt-5 mt-lg-0">
                    <div class="feature-box">
                        <div class="d-flex align-items-center justify-content-between mb-4">
                            <span class="badge badge-light p-2 font-weight-bold" style="font-size: 11px; color: var(--brand-primary); background: var(--brand-light);">GUARDRAILS SYSTEM</span>
                            <small class="text-success font-weight-semibold"><i class="fa-solid fa-circle text-success mr-1" style="font-size: 8px;"></i> Active</small>
                        </div>
                        <h3 class="feature-h3">Spesifikasi Keamanan AI</h3>
                        <ul class="list-unstyled text-secondary" style="font-size: 14px; line-height: 2.2;">
                            <li><i class="fa-solid fa-check text-success mr-2"></i> Biometric 512D Vector Face Embedding</li>
                            <li><i class="fa-solid fa-check text-success mr-2"></i> Geofencing GPS Radiasi Maksimal 30 Meter</li>
                            <li><i class="fa-solid fa-check text-success mr-2"></i> ISO / GDPR Privacy Protection (Master Purged)</li>
                            <li><i class="fa-solid fa-check text-success mr-2"></i> PMK 168/2023 TER PPh 21 Engine</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats -->
    <section class="stat-strip">
        <div class="container">
            <div class="row text-center">
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="stat-val"><?php echo isset($total_employees) ? $total_employees : 0; ?></div>
                    <div class="stat-lbl">Karyawan Terdaftar</div>
                </div>
                <div class="col-md-4 mb-3 mb-md-0">
                    <div class="stat-val"><?php echo isset($total_departments) ? $total_departments : 0; ?></div>
                    <div class="stat-lbl">Departemen</div>
                </div>
                <div class="col-md-4">
                    <div class="stat-val"><?php echo isset($total_attendance_today) ? $total_attendance_today : 0; ?></div>
                    <div class="stat-lbl">Presensi Terverifikasi</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Features Grid -->
    <section class="py-5">
        <div class="container py-4">
            <div class="row">
                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-face-smile"></i>
                        </div>
                        <h3 class="feature-h3">Biometric AI Recognition</h3>
                        <p class="feature-p-desc">Verifikasi presensi kehadiran berbasis AI 512D Vector Signature dengan presisi Cosine Similarity 80%+.</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-calculator"></i>
                        </div>
                        <h3 class="feature-h3">Kalkulasi PPh 21 TER</h3>
                        <p class="feature-p-desc">Perhitungan otomatis gaji kotor, potongan BPJS, dan PPh 21 Tarif Efektif Rata-Rata (PP 58/2023).</p>
                    </div>
                </div>

                <div class="col-md-4 mb-4">
                    <div class="feature-box">
                        <div class="feature-icon-wrapper">
                            <i class="fa-solid fa-user-check"></i>
                        </div>
                        <h3 class="feature-h3">Portal Mandiri Karyawan</h3>
                        <p class="feature-desc">Akses independen bagi karyawan untuk cek kehadiran, unduh slip gaji, dan memantau status biometrik.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="text-center">
        <div class="container">
            <p class="m-0">
                &copy; <?php echo date('Y'); ?> <?php echo isset($settingsvalue->sitetitle) ? strtoupper($settingsvalue->sitetitle) : 'HRMS ENTERPRISE'; ?>. All Rights Reserved.
            </p>
        </div>
    </footer>

    <!-- Scripts -->
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
