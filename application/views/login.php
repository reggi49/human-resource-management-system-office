<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <!-- Tell the browser to be responsive to screen width -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="Kerjanasional merupakan aplikasi pelamar kerja Indonesia secara online. Memudahkan pencari kerja untuk mendapatkan pekerjaan.">
    <meta name="author" content="kerjanasional.id">
    <!-- Start HTTPS Verification -->
    <meta http-equiv="Content-Security-Policy" content="upgrade-insecure-requests">

    <!-- DNS Prefetch -->
    <link rel="dns-prefetch" href="https://hris.kerjanasional.id">

    <!-- SSL Verification -->
    <meta name="theme-color" content="#4f46e5">

    <!-- Open Graph Metadata for SEO and Social Media -->
    <meta property="og:title" content="kerjanasional.id">
    <meta property="og:description" content="Kerjanasional merupakan aplikasi pelamar kerja Indonesia secara online. Memudahkan pencari kerja untuk mendapatkan pekerjaan.">
    <meta property="og:url" content="https://hris.kerjanasional.id">
    <meta property="og:type" content="website">
    <meta property="og:image" content="<?php echo base_url(); ?>assets/images/logo-icon.png">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="assets/images/favicon.png">
    <title>HRMS Portal | Sign In</title>
    <!-- Google Fonts & Material Design Icons -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/MaterialDesign-Webfont/2.0.46/css/materialdesignicons.min.css">
    <!-- Bootstrap Core CSS -->
    <link href="<?php echo base_url(); ?>assets/plugins/bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS -->
    <link href="<?php echo base_url(); ?>assets/css/style.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/css/custom-modern.css" rel="stylesheet">
    <!-- Theme color -->
    <link href="<?php echo base_url(); ?>assets/css/colors/blue.css" id="theme" rel="stylesheet">
</head>

<body>
    <!-- Preloader -->
    <div class="preloader">
        <svg class="circular" viewBox="25 25 50 50">
            <circle class="path" cx="50" cy="50" r="20" fill="none" stroke-width="2" stroke-miterlimit="10" /> 
        </svg>
    </div>

    <!-- Main Wrapper Modern Login -->
    <div class="login-wrapper-modern">
        <div class="login-card-modern">
            <div class="logo-area">
                <img src="<?php echo base_url(); ?>assets/images/logo-icon.png" alt="Logo HRMS" />
                <h2 class="login-title">Selamat Datang</h2>
                <p class="login-subtitle">Masukkan kredensial Anda untuk mengakses HRMS Portal</p>
            </div>

            <?php if(!empty($this->session->flashdata('feedback'))){ ?>
            <div class="message-alert-danger">
                <i class="mdi mdi-alert-circle-outline font-18"></i>
                <span><?php echo $this->session->flashdata('feedback'); ?></span>
            </div>
            <?php } ?>

            <form class="form-horizontal" method="post" id="loginform" action="login/Login_Auth">
                <div class="form-group">
                    <label for="email_input">Username / Email</label>
                    <div class="input-icon-wrapper">
                        <i class="mdi mdi-account-outline"></i>
                        <input id="email_input" class="form-control" name="email" value="<?php if(isset($_COOKIE['email'])) { echo $_COOKIE['email']; } ?>" type="text" required placeholder="name@company.com">
                    </div>
                </div>

                <div class="form-group">
                    <label for="password_input">Password</label>
                    <div class="input-icon-wrapper">
                        <i class="mdi mdi-lock-outline"></i>
                        <input id="password_input" class="form-control" name="password" value="<?php if(isset($_COOKIE['password'])) { echo $_COOKIE['password']; } ?>" type="password" required placeholder="••••••••">
                    </div>
                </div>

                <div class="form-group m-t-30">
                    <button class="login-btn-modern waves-effect waves-light" type="submit">
                        <i class="mdi mdi-login m-r-5"></i> Masuk Sekarang
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- All Jquery -->
    <script src="<?php echo base_url(); ?>assets/plugins/jquery/jquery.min.js"></script>
    <!-- Bootstrap tether Core JavaScript -->
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/popper.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/plugins/bootstrap/js/bootstrap.min.js"></script>
    <!-- slimscrollbar scrollbar JavaScript -->
    <script src="<?php echo base_url(); ?>assets/js/jquery.slimscroll.js"></script>
    <!--Wave Effects -->
    <script src="<?php echo base_url(); ?>assets/js/waves.js"></script>
    <!--Custom JavaScript -->
    <script src="<?php echo base_url(); ?>assets/js/custom.min.js"></script>
</body>

</html>