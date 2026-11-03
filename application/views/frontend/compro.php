<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Virtue Leather Fabrics - Premium Synthetic Leather Solutions</title>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800&family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        :root {
            --bg-cream: #faf7f2;
            --bg-white: #ffffff;
            --text-dark: #2a2421;
            --text-muted: #6e655f;
            --accent-gold: #b8860b;
            --accent-warm: #8c5a3c;
            --border-light: #e8e2d8;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Outfit', sans-serif;
            scroll-behavior: smooth;
        }

        body {
            background-color: var(--bg-cream);
            color: var(--text-dark);
            line-height: 1.6;
        }

        /* Top Bar */
        .top-bar {
            background-color: #241e1b;
            color: #d1c7bd;
            padding: 8px 40px;
            font-size: 13px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.05);
        }

        .top-contacts {
            display: flex;
            gap: 25px;
        }

        .top-contacts a {
            color: #d1c7bd;
            text-decoration: none;
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .top-socials {
            display: flex;
            gap: 15px;
        }

        .top-socials a {
            color: #d1c7bd;
            text-decoration: none;
            transition: color 0.3s;
        }

        .top-socials a:hover {
            color: var(--accent-gold);
        }

        /* Main Header & Nav */
        header {
            background-color: var(--bg-white);
            box-shadow: 0 2px 15px rgba(0,0,0,0.04);
            position: sticky;
            top: 0;
            z-index: 1000;
        }

        .nav-container {
            max-width: 1300px;
            margin: 0 auto;
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 30px;
        }

        .brand-logo {
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
        }

        .brand-title {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            font-weight: 700;
            color: var(--text-dark);
            letter-spacing: 2px;
            line-height: 1.1;
        }

        .brand-sub {
            font-size: 11px;
            letter-spacing: 3px;
            color: var(--accent-warm);
            text-transform: uppercase;
        }

        .nav-menu {
            display: flex;
            gap: 28px;
            list-style: none;
            align-items: center;
        }

        .nav-menu a {
            text-decoration: none;
            color: var(--text-dark);
            font-weight: 600;
            font-size: 14px;
            letter-spacing: 0.5px;
            text-transform: uppercase;
            transition: color 0.3s;
        }

        .nav-menu a:hover {
            color: var(--accent-gold);
        }

        /* Hero Section */
        .hero-section {
            background: linear-gradient(135deg, #fdfbf7 0%, #f4ede2 100%);
            padding: 70px 30px 90px;
            position: relative;
            overflow: hidden;
        }

        .hero-container {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .hero-img-wrapper {
            position: relative;
            text-align: center;
        }

        .hero-img {
            width: 100%;
            max-width: 540px;
            border-radius: 16px;
            box-shadow: 0 20px 40px rgba(140, 90, 60, 0.15);
            transition: transform 0.5s ease;
        }

        .hero-img:hover {
            transform: scale(1.02);
        }

        .hero-content h1 {
            font-family: 'Cinzel', serif;
            font-size: 46px;
            font-weight: 700;
            line-height: 1.2;
            color: var(--text-dark);
            margin-bottom: 20px;
        }

        .hero-content h1 span {
            color: var(--accent-warm);
            font-style: italic;
        }

        .hero-content p {
            font-size: 17px;
            color: var(--text-muted);
            margin-bottom: 35px;
            max-width: 500px;
        }

        .btn-enquire {
            background: linear-gradient(135deg, var(--accent-warm), #6e4228);
            color: #fff;
            padding: 16px 36px;
            border-radius: 30px;
            text-decoration: none;
            font-weight: 600;
            font-size: 15px;
            letter-spacing: 1px;
            display: inline-block;
            box-shadow: 0 8px 20px rgba(140, 90, 60, 0.25);
            transition: all 0.3s;
        }

        .btn-enquire:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 25px rgba(140, 90, 60, 0.35);
        }

        /* Categories Section */
        .section-padding {
            padding: 90px 30px;
        }

        .section-header {
            text-align: center;
            margin-bottom: 55px;
        }

        .section-header h2 {
            font-family: 'Cinzel', serif;
            font-size: 36px;
            letter-spacing: 2px;
            color: var(--text-dark);
            margin-bottom: 10px;
            text-transform: uppercase;
        }

        .section-header p {
            color: var(--text-muted);
            font-size: 16px;
        }

        .categories-grid {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            gap: 30px;
        }

        .category-card {
            background: var(--bg-white);
            border-radius: 12px;
            overflow: hidden;
            border: 1px solid var(--border-light);
            transition: all 0.4s ease;
            box-shadow: 0 5px 15px rgba(0,0,0,0.03);
        }

        .category-card:hover {
            transform: translateY(-8px);
            box-shadow: 0 15px 30px rgba(0,0,0,0.08);
            border-color: var(--accent-gold);
        }

        .category-img {
            width: 100%;
            height: 240px;
            object-fit: cover;
        }

        .category-body {
            padding: 25px;
            text-align: center;
        }

        .category-body h3 {
            font-family: 'Cinzel', serif;
            font-size: 20px;
            margin-bottom: 10px;
            color: var(--text-dark);
        }

        .category-body p {
            color: var(--text-muted);
            font-size: 14px;
        }

        /* Products Showcase */
        .products-section {
            background-color: var(--bg-white);
            border-top: 1px solid var(--border-light);
            border-bottom: 1px solid var(--border-light);
        }

        .product-grid {
            max-width: 1300px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
            gap: 25px;
        }

        .product-card {
            background: var(--bg-cream);
            border-radius: 10px;
            padding: 20px;
            border: 1px solid var(--border-light);
            transition: all 0.3s;
        }

        .product-card:hover {
            border-color: var(--accent-warm);
        }

        .swatch-box {
            width: 100%;
            height: 180px;
            border-radius: 8px;
            background-size: cover;
            background-position: center;
            margin-bottom: 15px;
        }

        .product-card h4 {
            font-size: 17px;
            color: var(--text-dark);
            margin-bottom: 5px;
        }

        .product-card .spec {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 12px;
        }

        .product-badge {
            display: inline-block;
            background: rgba(184, 134, 11, 0.12);
            color: var(--accent-gold);
            padding: 4px 10px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
        }

        /* About Us & Visi Misi */
        .about-grid {
            max-width: 1200px;
            margin: 0 auto;
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 50px;
            align-items: center;
        }

        .vm-box {
            background: var(--bg-white);
            padding: 35px;
            border-radius: 12px;
            border-left: 4px solid var(--accent-warm);
            margin-bottom: 25px;
            box-shadow: 0 5px 20px rgba(0,0,0,0.03);
        }

        .vm-box h3 {
            font-family: 'Cinzel', serif;
            font-size: 22px;
            color: var(--text-dark);
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .vm-box h3 i {
            color: var(--accent-gold);
        }

        .vm-box p {
            color: var(--text-muted);
            font-size: 15px;
        }

        /* Footer */
        footer {
            background-color: #1e1916;
            color: #b5a99d;
            padding: 60px 30px 30px;
        }

        .footer-grid {
            max-width: 1300px;
            margin: 0 auto 40px;
            display: grid;
            grid-template-columns: 2fr 1fr 1fr 1.5fr;
            gap: 40px;
        }

        .footer-col h4 {
            font-family: 'Cinzel', serif;
            color: #fff;
            font-size: 18px;
            margin-bottom: 20px;
            letter-spacing: 1px;
        }

        .footer-col p {
            font-size: 14px;
            line-height: 1.8;
        }

        .footer-col ul {
            list-style: none;
        }

        .footer-col ul li {
            margin-bottom: 10px;
        }

        .footer-col ul li a {
            color: #b5a99d;
            text-decoration: none;
            transition: color 0.3s;
        }

        .footer-col ul li a:hover {
            color: var(--accent-gold);
        }

        .footer-bottom {
            border-top: 1px solid rgba(255,255,255,0.08);
            padding-top: 25px;
            text-align: center;
            font-size: 13px;
        }
    </style>
</head>
<body>

    <!-- Top Contact Bar -->
    <div class="top-bar">
        <div class="top-contacts">
            <a href="tel:+628123456789"><i class="fa fa-phone"></i> +62 (021) 555-8899</a>
            <a href="mailto:info@virtueleather.com"><i class="fa fa-envelope"></i> info@virtueleather.com</a>
            <span><i class="fa fa-map-marker"></i> Jakarta & Tangerang Industrial Estate</span>
        </div>
        <div class="top-socials">
            <a href="#"><i class="fa fa-facebook"></i></a>
            <a href="#"><i class="fa fa-instagram"></i></a>
            <a href="#"><i class="fa fa-linkedin"></i></a>
        </div>
    </div>

    <!-- Header Navigation -->
    <header>
        <div class="nav-container">
            <a href="<?php echo base_url(); ?>" class="brand-logo">
                <i class="fa fa-cube" style="color: var(--accent-warm); font-size: 28px;"></i>
                <div>
                    <div class="brand-title">VIRTUE FABRICS</div>
                    <div class="brand-sub">Synthetic Leather Solutions</div>
                </div>
            </a>
            <ul class="nav-menu">
                <li><a href="#home">Home</a></li>
                <li><a href="#categories">Categories</a></li>
                <li><a href="#products">Products</a></li>
                <li><a href="#about">About Us</a></li>
                <li><a href="#vision-mission">Visi & Misi</a></li>
                <li><a href="<?php echo base_url('careers'); ?>">Careers</a></li>
            </ul>
        </div>
    </header>

    <!-- Hero Section -->
    <section class="hero-section" id="home">
        <div class="hero-container">
            <div class="hero-img-wrapper">
                <img src="<?php echo base_url('assets/images/leather_hero.png'); ?>" alt="Synthetic Leather Rolls Stack" class="hero-img">
            </div>
            <div class="hero-content">
                <h1>Synthetic Leather That <span>Speaks Fashion & Elegance</span></h1>
                <p>Produsen dan distributor resmi bahan kulit sintetis PVC & PU kualitas tinggi untuk otomotif, mebel furniture, tas, dan industri fashion internasional.</p>
                <a href="#products" class="btn-enquire">Jelajahi Produk Kami <i class="fa fa-arrow-right" style="margin-left: 8px;"></i></a>
            </div>
        </div>
    </section>

    <!-- Categories Section -->
    <section class="section-padding" id="categories">
        <div class="section-header">
            <h2>Our Categories</h2>
            <p>Pilihan tekstur dan spesifikasi bahan kulit sintetis sesuai kebutuhan industri Anda</p>
        </div>

        <div class="categories-grid">
            <div class="category-card">
                <img src="<?php echo base_url('assets/images/leather_auto.png'); ?>" alt="Automotive Synthetic Leather" class="category-img">
                <div class="category-body">
                    <h3>Automotive Interior</h3>
                    <p>Bahan kulit sintetis perforated & nappa anti-UV, tahan panas, dan fleksibel untuk interior jok mobil premium.</p>
                </div>
            </div>

            <div class="category-card">
                <img src="<?php echo base_url('assets/images/leather_hero.png'); ?>" alt="Furniture & Sofa Leather" class="category-img" style="object-position: center bottom;">
                <div class="category-body">
                    <h3>Furniture & Sofa Upholstery</h3>
                    <p>Tekstur embossed kulit jeruk dan halus untuk pelapis sofa, kursi kantor, dan dekorasi interior rumah modern.</p>
                </div>
            </div>

            <div class="category-card">
                <img src="<?php echo base_url('assets/images/leather_hero.png'); ?>" alt="Fashion & Bag Leather" class="category-img" style="object-position: center top;">
                <div class="category-body">
                    <h3>Fashion & Accessories</h3>
                    <p>Koleski bahan PU sintetis lentur bermutu tinggi untuk pembuatan tas branded, dompet, sepatu, dan jaket fashion.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Products Showcase -->
    <section class="section-padding products-section" id="products">
        <div class="section-header">
            <h2>Featured Products</h2>
            <p>Koleksi varian unggulan kulit sintetis terlaris dengan berbagai pilihan warna</p>
        </div>

        <div class="product-grid">
            <div class="product-card">
                <div class="swatch-box" style="background-image: url('<?php echo base_url('assets/images/leather_auto.png'); ?>');"></div>
                <h4>Nappa Perforated Auto Grade</h4>
                <div class="spec">Thickness: 1.2mm | Width: 137cm</div>
                <span class="product-badge">Automotive Grade</span>
            </div>

            <div class="product-card">
                <div class="swatch-box" style="background-image: url('<?php echo base_url('assets/images/leather_hero.png'); ?>');"></div>
                <h4>Royal Cognac Embossed PU</h4>
                <div class="spec">Thickness: 1.4mm | Width: 140cm</div>
                <span class="product-badge">Sofa & Interior</span>
            </div>

            <div class="product-card">
                <div class="swatch-box" style="background-image: url('<?php echo base_url('assets/images/leather_hero.png'); ?>');"></div>
                <h4>Midnight Navy Microfiber Leather</h4>
                <div class="spec">Thickness: 1.0mm | Width: 137cm</div>
                <span class="product-badge">Fashion & Shoes</span>
            </div>

            <div class="product-card">
                <div class="swatch-box" style="background-image: url('<?php echo base_url('assets/images/leather_auto.png'); ?>');"></div>
                <h4>Espresso Grain PVC Heavy Duty</h4>
                <div class="spec">Thickness: 1.5mm | Width: 140cm</div>
                <span class="product-badge">Contract Furniture</span>
            </div>
        </div>
    </section>

    <!-- About Us & Visi Misi -->
    <section class="section-padding" id="about">
        <div class="section-header">
            <h2>About Virtue Fabrics</h2>
            <p>Perusahaan manufaktur & distributor bahan kulit sintetis tepercaya sejak 2012</p>
        </div>

        <div class="about-grid">
            <div>
                <p style="font-size: 16px; color: var(--text-muted); margin-bottom: 25px;">
                    Virtue Fabrics adalah produsen dan pemasok utama bahan kulit sintetis (PU & PVC Synthetic Leather) berstandar internasional. Kami menyediakan berbagai pilihan tekstur, ketebalan, dan warna terkini untuk memenuhi standar ketat manufaktur otomotif, furniture, serta industri kreatif fashion.
                </p>
                <p style="font-size: 16px; color: var(--text-muted);">
                    Dengan fasilitas riset dan kontrol kualitas mandiri, setiap meter produk kami diuji ketahanan tarik, ketahanan gesek (*martindale test*), serta formula ramah lingkungan (*eco-friendly*).
                </p>
            </div>

            <div id="vision-mission">
                <div class="vm-box">
                    <h3><i class="fa fa-eye"></i> Visi Kami</h3>
                    <p>Menjadi pemimpin industri kulit sintetis berstandar global di Asia Tenggara melalui inovasi tekstur, keunggulan kualitas bahan, dan keberlanjutan ramah lingkungan.</p>
                </div>

                <div class="vm-box">
                    <h3><i class="fa fa-bullseye"></i> Misi Kami</h3>
                    <p>Menghadirkan solusi material kulit sintetis inovatif yang mendukung estetika dan daya tahan produk klien, didukung dengan kepastian pasokan dan jaringan distribusi yang luas.</p>
                </div>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer>
        <div class="footer-grid">
            <div class="footer-col">
                <h4>VIRTUE FABRICS</h4>
                <p>Produsen & Distributor Bahan Kulit Sintetis Premium PVC & PU untuk Industri Otomotif, Furniture, dan Fashion.</p>
            </div>
            <div class="footer-col">
                <h4>Navigasi</h4>
                <ul>
                    <li><a href="#home">Home</a></li>
                    <li><a href="#categories">Categories</a></li>
                    <li><a href="#products">Products</a></li>
                    <li><a href="#about">About Us</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Kategori</h4>
                <ul>
                    <li><a href="#categories">Automotive Interior</a></li>
                    <li><a href="#categories">Sofa & Upholstery</a></li>
                    <li><a href="#categories">Fashion & Bags</a></li>
                </ul>
            </div>
            <div class="footer-col">
                <h4>Hubungi Kami</h4>
                <p><i class="fa fa-map-marker"></i> Jl. Industri Raya No. 88, Tangerang</p>
                <p><i class="fa fa-phone"></i> +62 (021) 555-8899</p>
                <p><i class="fa fa-envelope"></i> sales@virtueleather.com</p>
            </div>
        </div>

        <div class="footer-bottom">
            <p>&copy; <?php echo date('Y'); ?> Virtue Fabrics - Synthetic Leather Solutions. All Rights Reserved.</p>
        </div>
    </footer>

</body>
</html>
