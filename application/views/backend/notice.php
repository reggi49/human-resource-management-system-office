<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">Notice Board</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Notice Board</li>
                    </ol>
                </div>
            </div>
        <div class="container-fluid">
                <?php if($this->session->flashdata('feedback')): ?>
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        <i class="fa fa-check-circle mr-1"></i> <?php echo $this->session->flashdata('feedback'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
                <?php if($this->session->flashdata('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <i class="fa fa-exclamation-triangle mr-1"></i> <?php echo $this->session->flashdata('error'); ?>
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                    </div>
                <?php endif; ?>
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a data-toggle="modal" data-target="#noticemodel" data-whatever="@getbootstrap" class="text-white "><i class="" aria-hidden="true"></i> Add Notice </a></button>
                    </div>
                </div> 
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                    <h4 class="m-b-0 text-white"> Notice</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Judul Pengumuman</th>
                                                <th>Lampiran</th>
                                                <th>Tanggal</th>
                                                <th>Status TTD Karyawan</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                           <?php foreach($notice as $value): ?>
                                            <tr>
                                                <td><?php echo $value->id; ?></td>
                                                <td>
                                                    <strong><?php echo $value->title; ?></strong>
                                                    <?php if(!empty($value->is_mandatory)): ?>
                                                        <span class="badge badge-danger ml-1" style="font-size: 10px;">Wajib TTD</span>
                                                    <?php endif; ?>
                                                    <?php if(!empty($value->description)): ?>
                                                        <div class="text-muted" style="font-size: 11.5px;"><?php echo substr($value->description, 0, 80); ?>...</div>
                                                    <?php endif; ?>
                                                </td>
                                                <td>
                                                    <?php 
                                                        $has_file = !empty($value->file_url);
                                                        $ext = $has_file ? strtolower(pathinfo($value->file_url, PATHINFO_EXTENSION)) : '';
                                                        $is_img = in_array($ext, ['png', 'jpg', 'jpeg', 'gif', 'webp']);
                                                    ?>
                                                    <?php if($has_file && $is_img): ?>
                                                        <div class="d-flex align-items-center" style="gap: 8px;">
                                                            <a href="<?php echo base_url('assets/images/notice/'.$value->file_url); ?>" target="_blank" title="Klik untuk membuka ukuran penuh">
                                                                <img src="<?php echo base_url('assets/images/notice/'.$value->file_url); ?>" alt="Preview" class="rounded border shadow-xs" style="width: 44px; height: 44px; object-fit: cover;">
                                                            </a>
                                                            <div>
                                                                <a href="<?php echo base_url('assets/images/notice/'.$value->file_url); ?>" target="_blank" class="text-primary font-weight-bold d-block" style="font-size: 11.5px;">
                                                                    <i class="fa fa-image mr-1"></i> Buka Gambar
                                                                </a>
                                                                <small class="text-muted"><?php echo strtoupper($ext); ?></small>
                                                            </div>
                                                        </div>
                                                    <?php elseif($has_file): ?>
                                                        <a href="<?php echo base_url('assets/images/notice/'.$value->file_url); ?>" target="_blank" class="btn btn-sm btn-outline-secondary font-weight-bold">
                                                            <i class="fa fa-file-pdf text-danger mr-1"></i> Buka PDF/Doc
                                                        </a>
                                                    <?php else: ?>
                                                        <span class="text-muted"><i class="fa fa-minus"></i> Tanpa Lampiran</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td><?php echo date('d M Y', strtotime($value->date)); ?></td>
                                                <td>
                                                    <?php 
                                                        $signed = isset($value->total_signed) ? (int)$value->total_signed : 0;
                                                        $total = isset($value->total_employees) ? (int)$value->total_employees : 0;
                                                        $unsigned = $total - $signed;
                                                        $pct = $total > 0 ? round(($signed / $total) * 100) : 0;
                                                    ?>
                                                    <div style="min-width: 130px;">
                                                        <div class="d-flex align-items-center justify-content-between mb-1" style="font-size: 11px;">
                                                            <span class="text-success font-weight-bold"><i class="fa fa-check-circle mr-1"></i><?php echo $signed; ?> TTD</span>
                                                            <span class="text-danger font-weight-bold"><i class="fa fa-clock mr-1"></i><?php echo $unsigned; ?> Belum</span>
                                                        </div>
                                                        <div class="progress" style="height: 6px;">
                                                            <div class="progress-bar bg-success" role="progressbar" style="width: <?php echo $pct; ?>%;"></div>
                                                        </div>
                                                        <small class="text-muted" style="font-size: 10px;"><?php echo $pct; ?>% Selesai</small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <button type="button" class="btn btn-sm btn-info font-weight-bold btn-view-signatures" data-id="<?php echo $value->id; ?>">
                                                        <i class="fa fa-users mr-1"></i> Rekap Siapa yg Sudah / Belum TTD
                                                    </button>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Add Notice -->
                <div class="modal fade" id="noticemodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title font-weight-bold" id="exampleModalLabel1"><i class="fa fa-bullhorn text-info mr-1"></i> Tambah Pengumuman / Surat Edaran</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <form role="form" method="post" action="Published_Notice" id="btnSubmit" enctype="multipart/form-data">
                            <div class="modal-body">
                                <div class="form-group">
                                    <label class="control-label font-weight-bold">Judul Pengumuman / Edaran:</label>
                                    <input type="text" class="form-control" name="title" required placeholder="Contoh: Surat Edaran Libur Bersama Idul Fitri">
                                </div>
                                <div class="form-group">
                                    <label class="control-label font-weight-bold">Deskripsi / Isi Ketentuan:</label>
                                    <textarea class="form-control" name="description" rows="4" placeholder="Tuliskan rincian pengumuman, tanggal pelaksanaan libur, atau instruksi kerja..."></textarea>
                                </div>
                                <div class="form-group">
                                    <label class="control-label font-weight-bold">Upload Gambar Poster / Dokumen Surat (PNG, JPG, WEBP, PDF):</label>
                                    <input type="file" name="file_url" id="admin-notice-file-input" class="form-control" accept="image/*,.pdf,.doc,.docx">
                                    <small class="form-text text-muted">Format didukung: PNG, JPG, JPEG, WEBP, PDF. Tinggal pilih file gambar pengumuman.</small>
                                    <div id="admin-file-preview-wrap" class="mt-2 text-center d-none p-2 border rounded bg-light">
                                        <img id="admin-file-img-preview" src="" style="max-height: 180px; max-width: 100%; border-radius: 6px;" class="shadow-xs mb-1">
                                        <div id="admin-file-name-info" class="small text-muted font-weight-bold"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="control-label font-weight-bold">Tanggal Terbit:</label>
                                    <input type="date" name="nodate" class="form-control" value="<?php echo date('Y-m-d'); ?>" required>
                                </div>
                                <div class="form-group">
                                    <div class="checkbox checkbox-primary">
                                        <input id="is_mandatory" name="is_mandatory" type="checkbox" value="1" checked>
                                        <label for="is_mandatory" class="font-weight-bold text-danger">
                                            <i class="fa fa-signature mr-1"></i> Wajib Tanda Tangan / Konfirmasi Digital PIN oleh Seluruh Karyawan
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal">Batal</button>
                                <button type="submit" class="btn btn-primary font-weight-bold"><i class="fa fa-paper-plane mr-1"></i> Publikasikan Pengumuman</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Modal Rekap Tanda Tangan Karyawan (Lengkap: Siapa yg sudah & siapa yg belum) -->
                <div class="modal fade" id="signatureListModal" tabindex="-1" role="dialog" aria-hidden="true">
                    <div class="modal-dialog modal-xl" role="document">
                        <div class="modal-content border-0 shadow">
                            <div class="modal-header bg-dark text-white py-3">
                                <div>
                                    <h4 class="modal-title font-weight-bold mb-0">
                                        <i class="fa fa-file-signature text-warning mr-2"></i> Rekap Status Tanda Tangan Surat Edaran
                                    </h4>
                                    <small class="text-muted" id="rekap-notice-title"></small>
                                </div>
                                <button type="button" class="close text-white" data-dismiss="modal">&times;</button>
                            </div>
                            <div class="modal-body p-4 bg-light">
                                <!-- Stat Summary Cards -->
                                <div class="row mb-3">
                                    <div class="col-md-4">
                                        <div class="card card-body bg-white border p-3 rounded mb-0 shadow-xs">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="text-muted text-uppercase font-weight-bold" style="font-size: 11px;">Total Karyawan Wajib</div>
                                                    <h3 class="font-weight-bold text-dark mb-0" id="stat-total-emp">0</h3>
                                                </div>
                                                <div style="width: 44px; height: 44px; border-radius: 10px; background: #EEF2FF; color: #4F46E5; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                                    <i class="fa fa-users"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-white border p-3 rounded mb-0 shadow-xs" style="border-left: 4px solid #10B981 !important;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="text-success text-uppercase font-weight-bold" style="font-size: 11px;">Sudah Tanda Tangan (PIN)</div>
                                                    <h3 class="font-weight-bold text-success mb-0" id="stat-signed-emp">0</h3>
                                                </div>
                                                <div style="width: 44px; height: 44px; border-radius: 10px; background: #ECFDF5; color: #10B981; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                                    <i class="fa fa-check-circle"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card card-body bg-white border p-3 rounded mb-0 shadow-xs" style="border-left: 4px solid #EF4444 !important;">
                                            <div class="d-flex align-items-center justify-content-between">
                                                <div>
                                                    <div class="text-danger text-uppercase font-weight-bold" style="font-size: 11px;">Belum Tanda Tangan</div>
                                                    <h3 class="font-weight-bold text-danger mb-0" id="stat-unsigned-emp">0</h3>
                                                </div>
                                                <div style="width: 44px; height: 44px; border-radius: 10px; background: #FEF2F2; color: #EF4444; display: flex; align-items: center; justify-content: center; font-size: 18px;">
                                                    <i class="fa fa-clock"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Filter Navigation Tabs & Search -->
                                <div class="card bg-white border mb-3 shadow-xs">
                                    <div class="card-body p-2 d-flex align-items-center justify-content-between flex-wrap" style="gap: 10px;">
                                        <div class="btn-group" id="signature-filter-tabs">
                                            <button type="button" class="btn btn-sm btn-primary active filter-tab-btn" data-filter="all">
                                                Semua Karyawan <span class="badge badge-light ml-1" id="tab-badge-all">0</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-success filter-tab-btn" data-filter="signed">
                                                <i class="fa fa-check mr-1"></i> Sudah TTD <span class="badge badge-success ml-1" id="tab-badge-signed">0</span>
                                            </button>
                                            <button type="button" class="btn btn-sm btn-outline-danger filter-tab-btn" data-filter="unsigned">
                                                <i class="fa fa-exclamation-triangle mr-1"></i> Belum TTD <span class="badge badge-danger ml-1" id="tab-badge-unsigned">0</span>
                                            </button>
                                        </div>

                                        <div style="width: 260px;">
                                            <input type="text" id="rekap-search-input" class="form-control form-control-sm" placeholder="🔍 Cari nama karyawan / NIK...">
                                        </div>
                                    </div>
                                </div>

                                <!-- Table Data Karyawan -->
                                <div class="card bg-white border mb-0 shadow-xs">
                                    <div class="table-responsive">
                                        <table class="table table-hover table-bordered mb-0" id="table-rekap-signatures">
                                            <thead class="thead-light">
                                                <tr>
                                                    <th style="width: 40px;" class="text-center">No</th>
                                                    <th>Foto & Nama Karyawan</th>
                                                    <th>Departemen & Jabatan</th>
                                                    <th class="text-center" style="width: 170px;">Status Tanda Tangan</th>
                                                    <th>Waktu Tanda Tangan</th>
                                                    <th>Audit Info (IP / Device)</th>
                                                </tr>
                                            </thead>
                                            <tbody id="rekap-tbody">
                                                <tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin mr-1"></i> Memuat data...</td></tr>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <div class="modal-footer bg-white">
                                <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                                <button type="button" class="btn btn-primary font-weight-bold" onclick="window.print();"><i class="fa fa-print mr-1"></i> Cetak Berita Acara TTD</button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                document.addEventListener('DOMContentLoaded', function() {
                    let currentEmployeesData = [];
                    let currentActiveFilter = 'all';

                    function renderSignatureTable() {
                        const searchTerm = $('#rekap-search-input').val().toLowerCase().trim();
                        let filtered = currentEmployeesData.filter(function(emp) {
                            const isSigned = emp.sign_status === 'SIGNED';
                            
                            // Filter tab
                            if (currentActiveFilter === 'signed' && !isSigned) return false;
                            if (currentActiveFilter === 'unsigned' && isSigned) return false;

                            // Search query
                            if (searchTerm.length > 0) {
                                const name = (emp.full_name || '').toLowerCase();
                                const code = (emp.em_code || emp.em_id || '').toLowerCase();
                                const dep = (emp.dep_name || '').toLowerCase();
                                if (!name.includes(searchTerm) && !code.includes(searchTerm) && !dep.includes(searchTerm)) {
                                    return false;
                                }
                            }
                            return true;
                        });

                        let html = '';
                        if (filtered.length > 0) {
                            filtered.forEach(function(emp, idx) {
                                const isSigned = emp.sign_status === 'SIGNED';
                                const badge = isSigned 
                                    ? '<span class="badge badge-success font-weight-bold px-3 py-1" style="font-size:11px;"><i class="fa fa-check mr-1"></i> SUDAH TTD</span>' 
                                    : '<span class="badge badge-danger font-weight-bold px-3 py-1" style="font-size:11px;"><i class="fa fa-clock mr-1"></i> BELUM TTD</span>';
                                const signTime = emp.signed_at ? '<span class="text-success font-weight-bold">' + emp.signed_at + '</span>' : '<span class="text-muted">-</span>';
                                const ip = emp.ip_address ? '<code>' + emp.ip_address + '</code>' : '<span class="text-muted">-</span>';
                                const avatarSrc = emp.em_image ? '<?php echo base_url("assets/images/users/"); ?>' + emp.em_image : '<?php echo base_url("assets/images/users/user.png"); ?>';

                                html += `<tr class="${!isSigned ? 'table-warning' : ''}">
                                    <td class="text-center align-middle">${idx + 1}</td>
                                    <td class="align-middle">
                                        <div class="d-flex align-items-center" style="gap: 10px;">
                                            <img src="${avatarSrc}" class="rounded-circle border" style="width: 38px; height: 38px; object-fit: cover;" onerror="this.src='<?php echo base_url('assets/images/users/user.png'); ?>';">
                                            <div>
                                                <strong class="text-dark d-block" style="font-size: 13px;">${emp.full_name}</strong>
                                                <small class="text-muted">NIK / ID: ${emp.em_code ? emp.em_code : emp.em_id}</small>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="align-middle">
                                        <strong>${emp.dep_name ? emp.dep_name : '-'}</strong><br>
                                        <small class="text-muted">${emp.des_name ? emp.des_name : ''}</small>
                                    </td>
                                    <td class="text-center align-middle">${badge}</td>
                                    <td class="align-middle">${signTime}</td>
                                    <td class="align-middle">${ip}</td>
                                </tr>`;
                            });
                        } else {
                            html = '<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa fa-search mr-1"></i> Tidak ada data karyawan yang cocok dengan kriteria filter.</td></tr>';
                        }
                        $('#rekap-tbody').html(html);
                    }

                    $(document).on('click', '.btn-view-signatures', function() {
                        const noticeId = $(this).data('id');
                        $('#rekap-tbody').html('<tr><td colspan="6" class="text-center py-4 text-muted"><i class="fa fa-spinner fa-spin mr-1"></i> Memuat data tanda tangan...</td></tr>');
                        $('#signatureListModal').modal('show');

                        $.ajax({
                            url: '<?php echo base_url("notice/get_signatures_ajax/"); ?>' + noticeId,
                            type: 'GET',
                            dataType: 'json',
                            success: function(res) {
                                if (res.status === 'success') {
                                    $('#rekap-notice-title').text(res.notice.title + ' (' + res.notice.date + ')');
                                    
                                    // Update stat counters
                                    $('#stat-total-emp').text(res.total_all || 0);
                                    $('#stat-signed-emp').text(res.total_signed || 0);
                                    $('#stat-unsigned-emp').text(res.total_unsigned || 0);

                                    $('#tab-badge-all').text(res.total_all || 0);
                                    $('#tab-badge-signed').text(res.total_signed || 0);
                                    $('#tab-badge-unsigned').text(res.total_unsigned || 0);

                                    currentEmployeesData = res.employees || [];
                                    currentActiveFilter = 'all';
                                    $('.filter-tab-btn').removeClass('btn-primary btn-success btn-danger').addClass('btn-outline-secondary');
                                    $('.filter-tab-btn[data-filter="all"]').removeClass('btn-outline-secondary').addClass('btn-primary active');

                                    renderSignatureTable();
                                } else {
                                    $('#rekap-tbody').html(`<tr><td colspan="6" class="text-center text-danger py-4">${res.message}</td></tr>`);
                                }
                            },
                            error: function() {
                                $('#rekap-tbody').html('<tr><td colspan="6" class="text-center text-danger py-4">Gagal memuat rekap tanda tangan.</td></tr>');
                            }
                        });
                    });

                    // Filter tab button click
                    $(document).on('click', '.filter-tab-btn', function() {
                        $('.filter-tab-btn').removeClass('btn-primary btn-success btn-danger active').addClass('btn-outline-secondary');
                        currentActiveFilter = $(this).data('filter');

                        if (currentActiveFilter === 'all') {
                            $(this).removeClass('btn-outline-secondary').addClass('btn-primary active');
                        } else if (currentActiveFilter === 'signed') {
                            $(this).removeClass('btn-outline-secondary').addClass('btn-success active');
                        } else if (currentActiveFilter === 'unsigned') {
                            $(this).removeClass('btn-outline-secondary').addClass('btn-danger active');
                        }

                        renderSignatureTable();
                    });

                    // Search filter
                    $(document).on('keyup', '#rekap-search-input', function() {
                        renderSignatureTable();
                    });

                    // Live preview for upload notice file
                    $('#admin-notice-file-input').on('change', function(e) {
                        const file = e.target.files[0];
                        if (file) {
                            $('#admin-file-preview-wrap').removeClass('d-none');
                            $('#admin-file-name-info').text(file.name + ' (' + (file.size / 1024).toFixed(1) + ' KB)');
                            if (file.type.startsWith('image/')) {
                                const reader = new FileReader();
                                reader.onload = function(evt) {
                                    $('#admin-file-img-preview').attr('src', evt.target.result).removeClass('d-none');
                                };
                                reader.readAsDataURL(file);
                            } else {
                                $('#admin-file-img-preview').addClass('d-none');
                                $('#admin-file-name-info').html('<i class="fa fa-file-pdf text-danger fa-2x d-block mb-1"></i>' + file.name);
                            }
                        } else {
                            $('#admin-file-preview-wrap').addClass('d-none');
                        }
                    });
                });
                </script> 
   
<?php $this->load->view('backend/footer'); ?>
