<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Portal Login | <?php echo isset($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS Enterprise'; ?></title>
    
    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- FontAwesome & Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css">

    <style>
        :root {
            --bg-page: #0F172A;
            --card-bg: rgba(30, 41, 59, 0.85);
            --accent-green: #10B981;
            --accent-indigo: #6366F1;
            --border-color: rgba(255, 255, 255, 0.12);
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
            background-color: var(--bg-page);
            color: #F8FAFC;
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-image: radial-gradient(circle at 15% 15%, rgba(99, 102, 241, 0.15) 0%, transparent 40%),
                              radial-gradient(circle at 85% 85%, rgba(16, 185, 129, 0.15) 0%, transparent 40%);
        }

        .login-card {
            width: 100%;
            max-width: 440px;
            background: var(--card-bg);
            backdrop-filter: blur(16px);
            border: 1px solid var(--border-color);
            border-radius: 24px;
            padding: 40px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        .brand-badge {
            width: 54px;
            height: 54px;
            background: linear-gradient(135deg, var(--accent-indigo), #4F46E5);
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            color: #FFF;
            margin: 0 auto 20px;
            box-shadow: 0 10px 25px rgba(99, 102, 241, 0.4);
        }

        .form-control-custom {
            background: rgba(15, 23, 42, 0.6);
            border: 1px solid var(--border-color);
            color: #F8FAFC;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 14px;
            transition: all 0.2s ease;
        }

        .form-control-custom:focus {
            background: rgba(15, 23, 42, 0.9);
            border-color: var(--accent-indigo);
            color: #FFF;
            box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.25);
        }

        .btn-portal-login {
            background: linear-gradient(135deg, var(--accent-indigo), #4F46E5);
            border: none;
            color: #FFF;
            font-weight: 700;
            font-size: 15px;
            padding: 14px;
            border-radius: 12px;
            width: 100%;
            transition: all 0.2s ease;
            box-shadow: 0 10px 20px rgba(99, 102, 241, 0.3);
        }

        .btn-portal-login:hover {
            transform: translateY(-2px);
            box-shadow: 0 15px 30px rgba(99, 102, 241, 0.5);
        }
    </style>
</head>
<body>

    <div class="login-card">
        <div class="text-center">
            <div class="brand-badge">
                <i class="fa-solid fa-id-badge"></i>
            </div>
            <h3 class="font-weight-bold mb-1" style="font-size: 22px;">Portal Karyawan</h3>
            <p class="text-secondary mb-4" style="font-size: 13px;">Masuk ke Employee Self-Service Dashboard</p>
        </div>

        <?php if($this->session->flashdata('feedback')): ?>
            <div class="alert alert-danger rounded-lg text-center font-weight-bold py-2 mb-3" style="font-size: 13px;">
                <i class="fa-solid fa-circle-exclamation mr-1"></i> <?php echo $this->session->flashdata('feedback'); ?>
            </div>
        <?php endif; ?>

        <form action="<?php echo base_url('portal/login_auth'); ?>" method="POST">
            <div class="form-group mb-3">
                <label class="font-weight-bold text-secondary mb-1" style="font-size: 12px;">NIK / EMAIL KARYAWAN</label>
                <div class="input-group">
                    <input type="text" name="email" class="form-control form-control-custom" placeholder="Contoh: Doe1234 atau email@perusahaan.com" required autofocus>
                </div>
            </div>

            <div class="form-group mb-4">
                <label class="font-weight-bold text-secondary mb-1" style="font-size: 12px;">PASSWORD</label>
                <div class="input-group">
                    <input type="password" name="password" class="form-control form-control-custom" placeholder="Masukkan password akun Anda" required>
                </div>
            </div>

            <button type="submit" class="btn-portal-login">
                Masuk ke Portal <i class="fa-solid fa-arrow-right ml-2"></i>
            </button>
        </form>

        <div class="text-center mt-4 pt-3 border-top border-secondary opacity-50 d-flex justify-content-between align-items-center">
            <small class="text-secondary font-weight-bold">HRMS Enterprise &copy; <?php echo date('Y'); ?></small>
            <a href="<?php echo base_url('login'); ?>" class="text-secondary font-weight-bold" style="font-size: 11px; text-decoration: none;"><i class="fa-solid fa-lock mr-1"></i> Admin Login</a>
        </div>
    </div>

</body>
</html>
