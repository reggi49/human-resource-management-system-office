<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="container-fluid">
        <div class="row page-titles">
            <div class="col-md-7 align-self-center">
                <h3 class="text-themecolor"><i class="fa fa-users"></i> Peringkat Kandidat AI: <?php echo htmlspecialchars($job->job_title); ?></h3>
            </div>
            <div class="col-md-5 align-self-center text-right">
                <a href="<?php echo base_url('recruitment'); ?>" class="btn btn-secondary"><i class="fa fa-arrow-left"></i> Kembali ke Lowongan</a>
            </div>
        </div>

        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-body">
                        <h4 class="card-title">Hasil Penilaian & Ranking Otomatis AI</h4>
                        <p class="text-muted">Kandidat diurutkan berdasarkan kesesuaian skor (*Match Score %*) tertinggi terhadap syarat lowongan.</p>

                        <div class="table-responsive mt-3">
                            <table class="table table-bordered table-striped">
                                <thead>
                                    <tr>
                                        <th>Rank</th>
                                        <th>Nama Pelamar</th>
                                        <th>Kontak (Email / Telp)</th>
                                        <th>AI Match Score</th>
                                        <th>Analisis Kesesuaian AI</th>
                                        <th>Status</th>
                                        <th>Berkas CV</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if(!empty($candidates)): $rank = 1; foreach($candidates as $c): ?>
                                    <tr>
                                        <td><strong>#<?php echo $rank++; ?></strong></td>
                                        <td><strong><?php echo htmlspecialchars($c->name); ?></strong></td>
                                        <td>
                                            <small><?php echo htmlspecialchars($c->email); ?></small><br>
                                            <small class="text-muted"><?php echo htmlspecialchars($c->phone); ?></small>
                                        </td>
                                        <td>
                                            <?php 
                                                $score = intval($c->match_score);
                                                $badge_class = ($score >= 75) ? 'badge-success' : (($score >= 50) ? 'badge-warning' : 'badge-danger');
                                            ?>
                                            <span class="badge <?php echo $badge_class; ?> p-2" style="font-size: 14px;">
                                                <?php echo $score; ?>% Match
                                            </span>
                                        </td>
                                        <td>
                                            <p class="mb-1"><strong>Ringkasan:</strong> <?php echo htmlspecialchars($c->experience_summary); ?></p>
                                            <small class="text-muted"><strong>Alasan AI:</strong> <?php echo htmlspecialchars($c->match_reason); ?></small>
                                        </td>
                                        <td>
                                            <span class="badge badge-info"><?php echo $c->status; ?></span>
                                        </td>
                                        <td>
                                            <?php if(!empty($c->cv_file) && file_exists(FCPATH . $c->cv_file)): ?>
                                                <a href="<?php echo base_url($c->cv_file); ?>" target="_blank" class="btn btn-sm btn-outline-danger">
                                                    <i class="fa fa-file-pdf-o"></i> Lihat PDF
                                                </a>
                                            <?php else: ?>
                                                <span class="text-muted">-</span>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Belum ada kandidat yang diproses untuk lowongan ini. Taruh berkas PDF CV di <code>uploads/jobs/job_<?php echo $job->id; ?>/</code> lalu klik "Batch Scan AI".</td>
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

<?php $this->load->view('backend/footer'); ?>
