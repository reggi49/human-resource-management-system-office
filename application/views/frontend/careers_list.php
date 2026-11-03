<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Karir & Lowongan Pekerjaan - Prime Corporate Solution</title>
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
        .container { max-width: 1100px; margin: 0 auto; padding: 0 20px; }

        nav { display: flex; justify-content: space-between; align-items: center; padding: 25px 0; border-bottom: 1px solid var(--border-color); }
        .logo { font-size: 22px; font-weight: 700; color: var(--accent-gold); text-decoration: none; display: flex; align-items: center; gap: 10px; }

        .page-header { padding: 60px 0 40px; text-align: center; }
        .page-header h1 { font-size: 38px; color: var(--accent-gold); margin-bottom: 10px; }
        .page-header p { color: var(--text-muted); font-size: 18px; }

        .job-list { display: flex; flex-direction: column; gap: 20px; padding-bottom: 80px; }
        .job-item { background: var(--card-bg); border: 1px solid var(--border-color); border-radius: 12px; padding: 30px; display: flex; justify-content: space-between; align-items: center; transition: border-color 0.3s; }
        .job-item:hover { border-color: var(--accent-gold); }

        .job-info h3 { font-size: 22px; color: var(--text-main); margin-bottom: 6px; }
        .job-info .meta { color: var(--text-muted); font-size: 14px; display: flex; gap: 20px; }

        .btn-apply { background: linear-gradient(135deg, var(--accent-gold), #c49646); color: #000; padding: 12px 24px; border-radius: 25px; text-decoration: none; font-weight: 700; white-space: nowrap; }

        footer { border-top: 1px solid var(--border-color); padding: 30px 0; text-align: center; color: var(--text-muted); font-size: 14px; }
    </style>
</head>
<body>

    <div class="container">
        <nav>
            <a href="<?php echo base_url(); ?>" class="logo"><i class="fa fa-diamond"></i> Prime Corporate Solution</a>
            <a href="<?php echo base_url(); ?>" style="color: var(--text-muted); text-decoration: none;"><i class="fa fa-arrow-left"></i> Kembali ke Beranda</a>
        </nav>

        <div class="page-header">
            <h1>Peluang Karir Perusahaan</h1>
            <p>Bergabunglah dengan tim inovatif kami. Kirimkan CV terbaik Anda untuk seleksi AI HR Parser.</p>
        </div>

        <div class="job-list">
            <?php if(!empty($jobs)): foreach($jobs as $j): ?>
            <div class="job-item">
                <div class="job-info">
                    <h3><?php echo htmlspecialchars($j->job_title); ?></h3>
                    <div class="meta">
                        <span><i class="fa fa-building-o"></i> <?php echo htmlspecialchars($j->department); ?></span>
                        <span><i class="fa fa-calendar"></i> Tenggat: <?php echo $j->closing_date ? date('d M Y', strtotime($j->closing_date)) : 'Buka'; ?></span>
                    </div>
                </div>
                <a href="<?php echo base_url('careers/apply/'.$j->id); ?>" class="btn-apply"><i class="fa fa-paper-plane"></i> Lamar Sekarang</a>
            </div>
            <?php endforeach; else: ?>
            <p style="text-align: center; color: var(--text-muted); padding: 40px 0;">Belum ada lowongan pekerjaan aktif saat ini.</p>
            <?php endif; ?>
        </div>
    </div>

    <footer>
        <div class="container">
            <p>&copy; <?php echo date('Y'); ?> Prime Corporate Solution. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
