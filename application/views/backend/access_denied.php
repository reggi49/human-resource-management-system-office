<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor">Akses Ditolak (403 Forbidden)</h3>
            </div>
            <div class="col-md-7 align-self-center">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                    <li class="breadcrumb-item active">Access Denied</li>
                </ol>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h1 class="display-3 text-danger"><i class="fa fa-exclamation-triangle"></i> 403</h1>
                        <h2 class="text-muted">Akses Dibatasi</h2>
                        <p class="lead mt-3">Anda tidak memiliki hak akses / izin yang cukup untuk membuka halaman ini.</p>
                        <a href="<?php echo base_url(); ?>" class="btn btn-info btn-rounded mt-4">Kembali ke Dashboard</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>
