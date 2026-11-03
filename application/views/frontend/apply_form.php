<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lamar: <?php echo htmlspecialchars($job->job_title); ?> - Prime Corporate Solution</title>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --bg-color: #0d1117;
            --card-bg: #161b22;
            --accent-gold: #e5b869;
            --accent-blue: #2f81f7;
            --text-main: #f0f6fc;
            --text-muted: #8b949e;
            --border-color: #30363d;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Outfit', sans-serif; }
        body { background-color: var(--bg-color); color: var(--text-main); line-height: 1.6; }
        .container { max-width: 900px; margin: 0 auto; padding: 0 20px; }

        nav { display: flex; justify-content: space-between; align-items: center; padding: 25px 0; border-bottom: 1px solid var(--border-color); }
        .logo { font-size: 22px; font-weight: 700; color: var(--accent-gold); text-decoration: none; display: flex; align-items: center; gap: 10px; }

        .apply-wrapper { margin: 50px 0 80px; }
        .job-detail-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; margin-bottom: 30px; }
        .job-detail-card h1 { color: var(--accent-gold); font-size: 32px; margin-bottom: 8px; }
        .job-detail-card .meta { color: var(--text-muted); margin-bottom: 20px; font-size: 15px; }

        .req-box { background: #0d1117; border: 1px solid var(--border-color); padding: 20px; border-radius: 8px; font-size: 15px; white-space: pre-line; color: #d0d7de; }

        .form-card { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 35px; }
        .form-card h3 { font-size: 22px; margin-bottom: 20px; color: var(--text-main); }

        .form-group { margin-bottom: 20px; }
        .form-group label { display: block; font-size: 14px; font-weight: 600; margin-bottom: 8px; color: var(--text-main); }
        .form-control { width: 100%; padding: 12px 15px; background: #0d1117; border: 1px solid var(--border-color); border-radius: 8px; color: #fff; font-size: 15px; }
        .form-control:focus { border-color: var(--accent-gold); outline: none; }

        .alert-success { background: rgba(46, 160, 67, 0.2); border: 1px solid #2ea043; color: #56d364; padding: 15px; border-radius: 8px; margin-bottom: 25px; }
        .alert-error { background: rgba(248, 81, 73, 0.2); border: 1px solid #f85149; color: #ff7b72; padding: 15px; border-radius: 8px; margin-bottom: 25px; }

        .btn-submit { background: linear-gradient(135deg, var(--accent-gold), #c49646); color: #000; padding: 14px 30px; border: none; border-radius: 25px; font-size: 16px; font-weight: 700; cursor: pointer; width: 100%; transition: opacity 0.3s; }
        .btn-submit:hover { opacity: 0.9; }

        footer { border-top: 1px solid var(--border-color); padding: 30px 0; text-align: center; color: var(--text-muted); font-size: 14px; }
    </style>
</head>
<body>

    <div class="container">
        <nav>
            <a href="<?php echo base_url(); ?>" class="logo"><i class="fa fa-diamond"></i> Prime Corporate Solution</a>
            <a href="<?php echo base_url('careers'); ?>" style="color: var(--text-muted); text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali ke Daftar Karir</a>
        </nav>

        <div class="apply-wrapper">
            <?php if($this->session->flashdata('success')): ?>
                <div class="alert-success">
                    <i class="fa fa-check-circle"></i> <?php echo $this->session->flashdata('success'); ?>
                </div>
            <?php endif; ?>

            <?php if($this->session->flashdata('error')): ?>
                <div class="alert-error">
                    <i class="fa fa-exclamation-circle"></i> <?php echo $this->session->flashdata('error'); ?>
                </div>
            <?php endif; ?>

            <div class="job-detail-card">
                <h1><?php echo htmlspecialchars($job->job_title); ?></h1>
                <div class="meta">
                    <i class="fa fa-building-o"></i> Departemen: <?php echo htmlspecialchars($job->department); ?> &nbsp;|&nbsp;
                    <i class="fa fa-calendar"></i> Tenggat: <?php echo $job->closing_date ? date('d M Y', strtotime($job->closing_date)) : 'Buka'; ?>
                </div>
                <h4 style="margin-bottom: 10px; font-size: 16px;">Syarat & Kualifikasi Pekerjaan:</h4>
                <div class="req-box"><?php echo htmlspecialchars($job->requirements); ?></div>
            </div>

            <div class="form-card">
                <h3><i class="fa fa-paper-plane"></i> Form Pengiriman Lamaran (Direct Candidate)</h3>
                <form action="<?php echo base_url('careers/submit_application'); ?>" method="post" enctype="multipart/form-data">
                    <input type="hidden" name="job_id" value="<?php echo $job->id; ?>">

                    <div class="form-group">
                        <label>Nama Lengkap Pelamar</label>
                        <input type="text" name="name" class="form-control" placeholder="Contoh: Budi Santoso" required>
                    </div>

                    <div class="form-group">
                        <label>Alamat Email Active</label>
                        <input type="email" name="email" class="form-control" placeholder="budi@email.com" required>
                    </div>

                    <div class="form-group">
                        <label>No. Telepon / WhatsApp</label>
                        <input type="text" name="phone" class="form-control" placeholder="08123456789">
                    </div>

                    <div class="form-group">
                        <label>Unggah Berkas CV (Format PDF / DOCX, Maks 5MB) <span style="color: #ff7b72;">*</span></label>
                        <input type="file" name="cv_file" class="form-control" accept=".pdf,.doc,.docx" required style="padding: 9px;">
                        <small style="color: var(--text-muted); display: block; margin-top: 5px;">
                            Berkas CV Anda akan langsung diekstrak dan dinilai skor kesesuaiannya oleh Gemini AI HR Parser.
                        </small>
                    </div>

                    <button type="submit" class="btn-submit"><i class="fa fa-send"></i> Kirim Lamaran & Ekstrak AI</button>
                </form>
            </div>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Prime Corporate Solution. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
