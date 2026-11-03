<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-5 align-self-center">
                <h3 class="text-themecolor"><i class="fa fa-briefcase"></i> Rekrutmen & Batch AI CV Scanner</h3>
            </div>
            <div class="col-md-7 align-self-center text-right">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addJobModal">
                    <i class="fa fa-plus"></i> Tambah Lowongan Baru
                </button>
            </div>
        </div>

        <?php if($this->session->flashdata('feedback')): ?>
            <div class="alert alert-success alert-dismissible fade show">
                <?php echo $this->session->flashdata('feedback'); ?>
                <button type="button" class="close" data-dismiss="alert">&times;</button>
            </div>
        <?php endif; ?>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Daftar Lowongan Pekerjaan</h4>
                        <h6 class="card-subtitle">Kelola posisi pekerjaan dan lakukan pemindaian serentak (*Batch Scan*) pada CV pelamar.</h6>
                        
                        <div class="table-responsive mt-4">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Posisi Pekerjaan</th>
                                        <th>Departemen</th>
                                        <th>Tenggat Lowongan</th>
                                        <th>Status</th>
                                        <th>Folder Upload CV</th>
                                        <th>Aksi Batch & Kandidat</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($jobs)): foreach($jobs as $j): ?>
                                    <tr>
                                        <td>#<?php echo $j->id; ?></td>
                                        <td><strong><?php echo htmlspecialchars($j->job_title); ?></strong></td>
                                        <td><?php echo htmlspecialchars($j->department); ?></td>
                                        <td><?php echo $j->closing_date ? date('d M Y', strtotime($j->closing_date)) : '-'; ?></td>
                                        <td>
                                            <?php if($j->status == 'OPEN'): ?>
                                                <span class="badge badge-success">Buka (Open)</span>
                                            <?php elseif($j->status == 'PROCESSING'): ?>
                                                <span class="badge badge-warning">Sedang Memindai AI...</span>
                                            <?php else: ?>
                                                <span class="badge badge-secondary">Selesai (Completed)</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <code>uploads/jobs/job_<?php echo $j->id; ?>/</code>
                                        </td>
                                        <td>
                                            <a href="<?php echo base_url('recruitment/close_and_scan/'.$j->id); ?>" class="btn btn-sm btn-primary" onclick="return confirm('Mulai Batch Scan AI untuk seluruh CV di folder ini?')">
                                                <i class="fa fa-cogs"></i> Batch Scan AI
                                            </a>
                                            <a href="<?php echo base_url('recruitment/candidates/'.$j->id); ?>" class="btn btn-sm btn-info">
                                                <i class="fa fa-users"></i> Lihat Pelamar & Peringkat
                                            </a>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada lowongan pekerjaan. Klik "Tambah Lowongan Baru" di atas.</td>
                                    </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Tambah Lowongan -->
<div class="modal fade" id="addJobModal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <form action="<?php echo base_url('recruitment/create_job'); ?>" method="post">
                <div class="modal-header">
                    <h5 class="modal-title"><i class="fa fa-plus-circle"></i> Tambah Lowongan Pekerjaan Baru</h5>
                    <button type="button" class="close" data-dismiss="alert" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label>Judul Posisi Pekerjaan <span class="text-danger">*</span></label>
                        <input type="text" name="job_title" class="form-control" placeholder="Contoh: Senior Backend Developer" required>
                    </div>
                    <div class="form-group">
                        <label>Departemen</label>
                        <input type="text" name="department" class="form-control" placeholder="Contoh: IT / Engineering">
                    </div>
                    <div class="form-group">
                        <label>Syarat & Kualifikasi (Digunakan AI untuk Match Scoring) <span class="text-danger">*</span></label>
                        <textarea name="requirements" class="form-control" rows="5" placeholder="Contoh: Min 3 tahun pengalaman PHP, CodeIgniter/Laravel, MySQL, REST API, pemahaman git dan Docker." required></textarea>
                    </div>
                    <div class="form-group">
                        <label>Tenggat Penutupan Lowongan</label>
                        <input type="date" name="closing_date" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary"><i class="fa fa-save"></i> Simpan Lowongan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php $this->load->view('backend/footer'); ?>
