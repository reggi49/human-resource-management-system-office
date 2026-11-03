<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<div class="page-wrapper">
    <div class="message"></div>
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-percent text-warning m-r-10"></i>Master & Referensi PPh 21</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>">Home</a></li>
                <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Payroll/Salary_List">Payroll</a></li>
                <li class="breadcrumb-item active">Master PPh 21</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <!-- Banner Header Info -->
        <div class="row">
            <div class="col-12">
                <div class="card text-white shadow-sm m-b-20" style="background: linear-gradient(135deg, #f6a238 0%, #f39c12 100%);">
                    <div class="card-body p-4">
                        <div class="d-flex align-items-center">
                            <div class="m-r-20">
                                <span class="btn btn-circle btn-lg btn-light text-warning"><i class="fa fa-shield fa-2x"></i></span>
                            </div>
                            <div>
                                <h3 class="card-title text-white font-weight-bold m-b-5">Modul Master PPh 21 Karyawan</h3>
                                <p class="card-text text-white-50 m-0" style="font-size: 14px;">
                                    Panduan dan referensi resmi perhitungan Pajak Penghasilan Pasal 21 berdasarkan <strong>PP No. 58 Tahun 2023 / PMK 168 Tahun 2023</strong> (Skema TER Bulanan) & <strong>UU HPP No. 7 Tahun 2021</strong> (Tarif Progresif Tahunan).
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Top Stat Summary Cards -->
        <?php 
            $total_emp = count($employee_rekap);
            $total_pph_sebulan = array_sum(array_column($employee_rekap, 'pph21_sebulan'));
            $total_bruto = array_sum(array_column($employee_rekap, 'gaji_bruto'));
        ?>
        <div class="row">
            <div class="col-md-3 col-sm-6">
                <div class="card card-body p-3 shadow-sm border-0" style="border-left: 4px solid #f39c12 !important;">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="text-muted font-weight-bold m-b-0">Karyawan Terdaftar</p>
                            <h3 class="font-weight-bold text-dark m-0"><?php echo $total_emp; ?> <small class="text-muted" style="font-size:12px;">Orang</small></h3>
                        </div>
                        <div class="ml-auto"><i class="fa fa-users fa-2x text-warning opacity-5"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-body p-3 shadow-sm border-0" style="border-left: 4px solid #20c997 !important;">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="text-muted font-weight-bold m-b-0">Total Gaji Bruto</p>
                            <h3 class="font-weight-bold text-success m-0">Rp <?php echo number_format($total_bruto, 0, ',', '.'); ?></h3>
                        </div>
                        <div class="ml-auto"><i class="fa fa-money fa-2x text-success opacity-5"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-body p-3 shadow-sm border-0" style="border-left: 4px solid #e74c3c !important;">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="text-muted font-weight-bold m-b-0">Estimasi PPh 21 / Bulan</p>
                            <h3 class="font-weight-bold text-danger m-0">Rp <?php echo number_format($total_pph_sebulan, 0, ',', '.'); ?></h3>
                        </div>
                        <div class="ml-auto"><i class="fa fa-calculator fa-2x text-danger opacity-5"></i></div>
                    </div>
                </div>
            </div>
            <div class="col-md-3 col-sm-6">
                <div class="card card-body p-3 shadow-sm border-0" style="border-left: 4px solid #007bff !important;">
                    <div class="d-flex align-items-center">
                        <div>
                            <p class="text-muted font-weight-bold m-b-0">Dasar Peraturan</p>
                            <h4 class="font-weight-bold text-primary m-0">PP 58/2023</h4>
                            <small class="text-muted">& PMK 168/2023</small>
                        </div>
                        <div class="ml-auto"><i class="fa fa-book fa-2x text-primary opacity-5"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Navigation Tabs -->
        <div class="row m-t-10">
            <div class="col-12">
                <div class="card shadow-sm">
                    <!-- Nav tabs -->
                    <ul class="nav nav-tabs profile-tab" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active font-weight-bold" data-toggle="tab" href="#rekap" role="tab">
                                <i class="fa fa-users text-warning m-r-5"></i>Rekap PPh 21 Karyawan
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#ter" role="tab">
                                <i class="fa fa-table text-info m-r-5"></i>Tabel TER (PP 58/2023)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#ptkp" role="tab">
                                <i class="fa fa-id-card text-success m-r-5"></i>Referensi PTKP
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#progresif" role="tab">
                                <i class="fa fa-line-chart text-danger m-r-5"></i>Tarif Progresif (UU HPP)
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link font-weight-bold" data-toggle="tab" href="#simulasi" role="tab">
                                <i class="fa fa-calculator text-primary m-r-5"></i>Kalkulator & Simulasi
                            </a>
                        </li>
                    </ul>

                    <!-- Tab panes -->
                    <div class="tab-content">

                        <!-- TAB 1: REKAP KARYAWAN -->
                        <div class="tab-pane active" id="rekap" role="tabpanel">
                            <div class="card-body">
                                <div class="d-flex align-items-center m-b-15">
                                    <div>
                                        <h4 class="card-title font-weight-bold m-0"><i class="fa fa-list text-warning m-r-10"></i>Daftar Estimasi PPh 21 Karyawan Aktif</h4>
                                        <p class="text-muted m-0" style="font-size: 13px;">Review status PTKP, NPWP, Kategori TER, dan besaran potongan PPh 21 bulanan tiap staf HRD / Karyawan.</p>
                                    </div>
                                </div>
                                <div class="table-responsive">
                                    <table id="table_rekap_pph" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead class="bg-warning text-white">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">No</th>
                                                <th>NIP / Code</th>
                                                <th>Nama Karyawan</th>
                                                <th>Departemen</th>
                                                <th>NPWP</th>
                                                <th class="text-center">Status PTKP</th>
                                                <th class="text-center">Kategori TER</th>
                                                <th class="text-right">Gaji Bruto</th>
                                                <th class="text-center">Tarif (%)</th>
                                                <th class="text-right">PPh 21 / Bulan</th>
                                                <th class="text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach ($employee_rekap as $emp): 
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td><span class="badge badge-secondary font-weight-bold"><?php echo $emp['em_code']; ?></span></td>
                                                <td class="font-weight-bold text-dark">
                                                    <a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($emp['em_id']); ?>#pph" class="text-dark">
                                                        <?php echo $emp['name']; ?>
                                                    </a>
                                                </td>
                                                <td><?php echo $emp['department']; ?></td>
                                                <td>
                                                    <?php if($emp['npwp'] != 'Belum Ada'): ?>
                                                        <span class="text-success"><i class="fa fa-check-circle m-r-5"></i><?php echo $emp['npwp']; ?></span>
                                                    <?php else: ?>
                                                        <span class="badge badge-warning text-dark"><i class="fa fa-exclamation-triangle m-r-5"></i>Belum Ada</span>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-info px-2 py-1" style="font-size:12px;"><?php echo $emp['ptkp_status']; ?></span>
                                                </td>
                                                <td class="text-center">
                                                    <span class="badge badge-primary px-2 py-1" style="font-size:12px;">TER Kategori <?php echo $emp['ter_category']; ?></span>
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    Rp <?php echo number_format($emp['gaji_bruto'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-center font-weight-bold text-primary">
                                                    <?php echo $emp['ter_rate']; ?>
                                                </td>
                                                <td class="text-right font-weight-bold text-danger">
                                                    Rp <?php echo number_format($emp['pph21_sebulan'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-center">
                                                    <button class="btn btn-sm btn-info text-white btn-detail-pajak m-r-5" data-emp='<?php echo htmlspecialchars(json_encode($emp), ENT_QUOTES, "UTF-8"); ?>' data-toggle="tooltip" title="Rincian Pajak Tahunan & Biaya Jabatan">
                                                        <i class="fa fa-info-circle"></i> Detail
                                                    </button>
                                                    <a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($emp['em_id']); ?>" class="btn btn-sm btn-warning text-white" data-toggle="tooltip" title="Lihat Profil & Edit PPh 21">
                                                        <i class="fa fa-pencil"></i> Edit
                                                    </a>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 2: TABEL TER PP 58/2023 -->
                        <div class="tab-pane" id="ter" role="tabpanel">
                            <div class="card-body">
                                <div class="d-flex align-items-center justify-content-between m-b-15">
                                    <div>
                                        <h4 class="card-title font-weight-bold m-0"><i class="fa fa-table text-info m-r-10"></i>Tabel Tarif Efektif Rata-Rata (TER) PP 58 Tahun 2023</h4>
                                        <p class="text-muted m-0" style="font-size: 13px;">Digunakan untuk menghitung pemotongan PPh Pasal 21 Masa Bulanan (Januari - November).</p>
                                    </div>
                                    <div>
                                        <div class="btn-group" role="group">
                                            <button type="button" class="btn btn-outline-info active filter-ter" data-cat="ALL">Semua Kategori</button>
                                            <button type="button" class="btn btn-outline-info filter-ter" data-cat="A">Kategori A</button>
                                            <button type="button" class="btn btn-outline-info filter-ter" data-cat="B">Kategori B</button>
                                            <button type="button" class="btn btn-outline-info filter-ter" data-cat="C">Kategori C</button>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive m-t-10">
                                    <table id="table_ter_master" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead class="bg-info text-white">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">Tier</th>
                                                <th class="text-center">Kategori TER</th>
                                                <th class="text-right">Penghasilan Bruto Min (Rp)</th>
                                                <th class="text-right">Penghasilan Bruto Max (Rp)</th>
                                                <th class="text-center">Tarif TER (%)</th>
                                                <th>Contoh Hitung (Gaji Bruto × Rate)</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($ter_rates as $ter): ?>
                                            <tr class="ter-row ter-cat-<?php echo $ter->category; ?>">
                                                <td class="text-center font-weight-bold"><?php echo $ter->tier; ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-<?php echo $ter->category == 'A' ? 'primary' : ($ter->category == 'B' ? 'warning' : 'danger'); ?> px-2 py-1">
                                                        Kategori <?php echo $ter->category; ?>
                                                    </span>
                                                </td>
                                                <td class="text-right">
                                                    Rp <?php echo number_format($ter->min_gross, 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-right">
                                                    <?php if($ter->max_gross >= 999999999900): ?>
                                                        <span class="badge badge-success">> Rp 1,4 Miliar</span>
                                                    <?php else: ?>
                                                        Rp <?php echo number_format($ter->max_gross, 0, ',', '.'); ?>
                                                    <?php endif; ?>
                                                </td>
                                                <td class="text-center font-weight-bold text-info" style="font-size: 15px;">
                                                    <?php echo ($ter->rate * 100) . '%'; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">
                                                        Range Rp <?php echo number_format($ter->min_gross, 0, ',', '.'); ?> s/d <?php echo number_format(min($ter->max_gross, 1500000000), 0, ',', '.'); ?> ➔ Tax: <?php echo ($ter->rate * 100) . '%'; ?>
                                                    </small>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 3: REFERENSI PTKP -->
                        <div class="tab-pane" id="ptkp" role="tabpanel">
                            <div class="card-body">
                                <h4 class="card-title font-weight-bold m-b-5"><i class="fa fa-id-card text-success m-r-10"></i>Daftar PTKP & Pemetaan Kategori TER</h4>
                                <p class="text-muted m-b-20" style="font-size: 13px;">Penghasilan Tidak Kena Pajak (PTKP) sesuai ketentuan Direktorat Jenderal Pajak RI.</p>

                                <div class="row">
                                    <div class="col-md-4">
                                        <div class="card card-outline-primary shadow-sm">
                                            <div class="card-header bg-primary text-white">
                                                <h5 class="m-b-0 text-white font-weight-bold"><i class="fa fa-folder m-r-5"></i>TER KATEGORI A</h5>
                                            </div>
                                            <div class="card-body">
                                                <p style="font-size: 13px;">Berlaku untuk status perkawinan & tanggungan:</p>
                                                <ul class="list-group list-group-flush font-weight-bold">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        TK/0 (Tidak Kawin, 0 Tanggungan)
                                                        <span class="badge badge-primary">Rp 54.000.000 / thn</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        TK/1 (Tidak Kawin, 1 Tanggungan)
                                                        <span class="badge badge-primary">Rp 58.500.000 / thn</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        K/0 (Kawin, 0 Tanggungan)
                                                        <span class="badge badge-primary">Rp 58.500.000 / thn</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card card-outline-warning shadow-sm">
                                            <div class="card-header bg-warning text-white">
                                                <h5 class="m-b-0 text-white font-weight-bold"><i class="fa fa-folder m-r-5"></i>TER KATEGORI B</h5>
                                            </div>
                                            <div class="card-body">
                                                <p style="font-size: 13px;">Berlaku untuk status perkawinan & tanggungan:</p>
                                                <ul class="list-group list-group-flush font-weight-bold">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        TK/2 (Tidak Kawin, 2 Tanggungan)
                                                        <span class="badge badge-warning text-dark">Rp 63.000.000 / thn</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        TK/3 (Tidak Kawin, 3 Tanggungan)
                                                        <span class="badge badge-warning text-dark">Rp 67.500.000 / thn</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        K/1 (Kawin, 1 Tanggungan)
                                                        <span class="badge badge-warning text-dark">Rp 63.000.000 / thn</span>
                                                    </li>
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        K/2 (Kawin, 2 Tanggungan)
                                                        <span class="badge badge-warning text-dark">Rp 67.500.000 / thn</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-4">
                                        <div class="card card-outline-danger shadow-sm">
                                            <div class="card-header bg-danger text-white">
                                                <h5 class="m-b-0 text-white font-weight-bold"><i class="fa fa-folder m-r-5"></i>TER KATEGORI C</h5>
                                            </div>
                                            <div class="card-body">
                                                <p style="font-size: 13px;">Berlaku untuk status perkawinan & tanggungan:</p>
                                                <ul class="list-group list-group-flush font-weight-bold">
                                                    <li class="list-group-item d-flex justify-content-between align-items-center">
                                                        K/3 (Kawin, 3 Tanggungan)
                                                        <span class="badge badge-danger">Rp 72.000.000 / thn</span>
                                                    </li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="table-responsive m-t-20">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-success text-white">
                                            <tr>
                                                <th class="text-center" style="width: 50px;">No</th>
                                                <th>Kode PTKP</th>
                                                <th>Deskripsi Status</th>
                                                <th class="text-center">Kategori TER</th>
                                                <th class="text-right">Nominal PTKP Per Tahun</th>
                                                <th class="text-right">Nominal PTKP Per Bulan</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php 
                                            $no = 1;
                                            foreach($ptkp_list as $p): 
                                            ?>
                                            <tr>
                                                <td class="text-center"><?php echo $no++; ?></td>
                                                <td><span class="badge badge-info font-weight-bold" style="font-size: 13px;"><?php echo $p['code']; ?></span></td>
                                                <td class="font-weight-bold"><?php echo $p['desc']; ?></td>
                                                <td class="text-center">
                                                    <span class="badge badge-<?php echo $p['category'] == 'A' ? 'primary' : ($p['category'] == 'B' ? 'warning' : 'danger'); ?> px-2 py-1">
                                                        TER Kategori <?php echo $p['category']; ?>
                                                    </span>
                                                </td>
                                                <td class="text-right font-weight-bold">
                                                    Rp <?php echo number_format($p['amount'], 0, ',', '.'); ?>
                                                </td>
                                                <td class="text-right text-muted">
                                                    Rp <?php echo number_format($p['amount'] / 12, 0, ',', '.'); ?>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 4: TARIF PROGRESIF PASAL 17 -->
                        <div class="tab-pane" id="progresif" role="tabpanel">
                            <div class="card-body">
                                <h4 class="card-title font-weight-bold m-b-5"><i class="fa fa-line-chart text-danger m-r-10"></i>Tarif Progresif PPh Pasal 17 ayat (1) huruf a (UU HPP No. 7 Tahun 2021)</h4>
                                <p class="text-muted m-b-20" style="font-size: 13px;">Digunakan untuk penghitungan PPh 21 Terutang Setahun penuh (khususnya pada masa pajak Desember / perhitungan akhir).</p>

                                <div class="table-responsive">
                                    <table class="table table-bordered table-striped">
                                        <thead class="bg-danger text-white">
                                            <tr>
                                                <th class="text-center" style="width: 100px;">Lapisan</th>
                                                <th>Rentang Penghasilan Kena Pajak (PKP) Setahun</th>
                                                <th class="text-center" style="width: 150px;">Tarif Pajak (%)</th>
                                                <th>Keterangan UU HPP No. 7/2021</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($progresif_list as $prog): ?>
                                            <tr>
                                                <td class="text-center font-weight-bold">
                                                    <span class="badge badge-danger px-2 py-1"><?php echo $prog['tier']; ?></span>
                                                </td>
                                                <td class="font-weight-bold text-dark" style="font-size: 15px;">
                                                    <?php echo $prog['range']; ?>
                                                </td>
                                                <td class="text-center font-weight-bold text-danger" style="font-size: 18px;">
                                                    <?php echo $prog['rate']; ?>
                                                </td>
                                                <td>
                                                    <small class="text-muted">Dikenakan dari sisa PKP yang masuk rentang lapisan ini.</small>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>

                                <div class="alert alert-info m-t-20">
                                    <h5 class="font-weight-bold"><i class="fa fa-lightbulb-o m-r-5"></i>Catatan Mekanisme TER vs Progresif</h5>
                                    <p class="m-b-0" style="font-size: 13px;">
                                        1. <strong>Bulan Jan - Nov:</strong> Menggunakan tarif TER (PP 58/2023) × Gaji Bruto Bulanan.<br>
                                        2. <strong>Bulan Desember:</strong> Menghitung PPh 21 Setahun dengan Tarif Progresif Pasal 17 dikurangi PTKP. Selisih dengan PPh 21 yang sudah dipotong (Jan-Nov) menjadi potongan pajak bulan Desember.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <!-- TAB 5: KALKULATOR & SIMULASI -->
                        <div class="tab-pane" id="simulasi" role="tabpanel">
                            <div class="card-body">
                                <h4 class="card-title font-weight-bold m-b-5"><i class="fa fa-calculator text-primary m-r-10"></i>Kalkulator & Simulasi PPh 21 Interactive</h4>
                                <p class="text-muted m-b-20" style="font-size: 13px;">Alat bantu HRD untuk melakukan uji coba perhitungan pajak secara instant.</p>

                                <div class="row bg-light p-3 rounded border">
                                    <div class="col-md-5">
                                        <div class="form-group m-b-0">
                                            <label class="font-weight-bold">Gaji Bruto Bulanan (Rp)</label>
                                            <input type="number" id="sim_bruto" class="form-control font-weight-bold" style="font-size: 16px;" value="10000000" placeholder="Masukkan Nominal Gaji Bruto...">
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="form-group m-b-0">
                                            <label class="font-weight-bold">Status PTKP Karyawan</label>
                                            <select id="sim_ptkp" class="form-control custom-select">
                                                <option value="TK0" selected>TK/0 (Rp 54.000.000) - TER Kategori A</option>
                                                <option value="TK1">TK/1 (Rp 58.500.000) - TER Kategori A</option>
                                                <option value="TK2">TK/2 (Rp 63.000.000) - TER Kategori B</option>
                                                <option value="TK3">TK/3 (Rp 67.500.000) - TER Kategori B</option>
                                                <option value="K0">K/0 (Rp 58.500.000) - TER Kategori A</option>
                                                <option value="K1">K/1 (Rp 63.000.000) - TER Kategori B</option>
                                                <option value="K2">K/2 (Rp 67.500.000) - TER Kategori B</option>
                                                <option value="K3">K/3 (Rp 72.000.000) - TER Kategori C</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="col-md-3">
                                        <div class="form-group m-b-0">
                                            <label>&nbsp;</label>
                                            <button type="button" id="btn_sim_calc" class="btn btn-warning text-white btn-block waves-effect font-weight-bold">
                                                <i class="fa fa-refresh m-r-5"></i>Hitung Pajak
                                            </button>
                                        </div>
                                    </div>
                                </div>

                                <!-- Cards Hasil Perhitungan -->
                                <div class="row m-t-20">
                                    <div class="col-md-4">
                                        <div class="card p-3 text-center border-warning" style="background-color: #fff9db; border: 2px solid #ffe066;">
                                            <h6 class="text-warning font-weight-bold m-b-5"><i class="fa fa-calendar m-r-5"></i>POTONGAN PPh 21 SEBULAN (TER)</h6>
                                            <h2 class="text-warning font-weight-bold m-0" id="sim_card_sebulan">Rp 0</h2>
                                            <small class="text-dark font-weight-bold" id="sim_sub_ter">TER Kategori - (0%)</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card p-3 text-center border-danger" style="background-color: #fff5f5; border: 2px solid #feb2b2;">
                                            <h6 class="text-danger font-weight-bold m-b-5"><i class="fa fa-balance-scale m-r-5"></i>PPh 21 TERUTANG SETAHUN (PROGRESIF)</h6>
                                            <h2 class="text-danger font-weight-bold m-0" id="sim_card_setahun">Rp 0</h2>
                                            <small class="text-muted">Perhitungan Tahunan UU HPP</small>
                                        </div>
                                    </div>
                                    <div class="col-md-4">
                                        <div class="card p-3 text-center border-info" style="background-color: #ebf8ff; border: 2px solid #90cdf4;">
                                            <h6 class="text-info font-weight-bold m-b-5"><i class="fa fa-money m-r-5"></i>PKP SETAHUN</h6>
                                            <h2 class="text-info font-weight-bold m-0" id="sim_card_pkp">Rp 0</h2>
                                            <small class="text-muted">Penghasilan Kena Pajak</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>

    </div>

    <!-- Modal Detail Pajak Pertahun & Biaya Jabatan -->
    <div class="modal fade" id="modalDetailPajak" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content" style="border-radius: 12px;">
                <div class="modal-header bg-info text-white" style="border-top-left-radius: 12px; border-top-right-radius: 12px;">
                    <h5 class="modal-title font-weight-bold text-white"><i class="fa fa-calculator mr-2"></i> Rincian Perhitungan Pajak Pertahun — <span id="dt_emp_name">-</span></h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body p-4">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p class="mb-2"><strong>NIP / Code:</strong> <span id="dt_em_code" class="badge badge-secondary ml-1">-</span></p>
                            <p class="mb-2"><strong>Departemen:</strong> <span id="dt_department">-</span></p>
                            <p class="mb-2"><strong>Status PTKP:</strong> <span id="dt_ptkp_status" class="badge badge-info ml-1">-</span></p>
                        </div>
                        <div class="col-md-6">
                            <p class="mb-2"><strong>NPWP:</strong> <span id="dt_npwp" class="text-success font-weight-bold">-</span></p>
                            <p class="mb-2"><strong>Gaji Bruto Bulanan:</strong> <span id="dt_gaji_bruto" class="font-weight-bold text-dark ml-1">Rp 0</span></p>
                            <p class="mb-2"><strong>Kategori TER:</strong> <span id="dt_ter_category" class="badge badge-primary ml-1">-</span></p>
                        </div>
                    </div>

                    <div class="card border mb-3 shadow-none">
                        <div class="card-header bg-light font-weight-bold py-2"><i class="fa fa-minus-circle text-danger mr-1"></i> Rincian Pengurang & Penghasilan Neto</div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span>Biaya Jabatan (5%, Maks Rp 500.000/bln)</span>
                                <strong id="dt_biaya_jabatan" class="text-dark">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span>Penghasilan Neto Sebulan</span>
                                <strong id="dt_neto_sebulan" class="text-primary">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Penghasilan Neto Setahun (Neto × 12)</span>
                                <strong id="dt_neto_setahun" class="text-primary">Rp 0</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card border mb-3 shadow-none">
                        <div class="card-header bg-light font-weight-bold py-2"><i class="fa fa-file-text-o text-warning mr-1"></i> Perhitungan PKP & PPh 21 Tahunan (Progresif Pasal 17)</div>
                        <div class="card-body p-3">
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span>Penghasilan Tidak Kena Pajak (PTKP Setahun)</span>
                                <strong id="dt_ptkp_tahunan" class="text-secondary">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span class="font-weight-bold">Penghasilan Kena Pajak (PKP Setahun)</span>
                                <strong id="dt_pkp" class="text-dark" style="font-size: 15px;">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1 border-bottom">
                                <span>PPh 21 Terutang Setahun (Skema Progresif UU HPP)</span>
                                <strong id="dt_pph21_setahun_progresif" class="text-danger">Rp 0</strong>
                            </div>
                            <div class="d-flex justify-content-between py-1">
                                <span>Estimasi PPh 21 Per Bulan (Skema Progresif)</span>
                                <strong id="dt_pph21_sebulan_progresif" class="text-danger">Rp 0 / bulan</strong>
                            </div>
                        </div>
                    </div>

                    <div class="card border-info bg-light p-3 mb-0" style="border-left: 4px solid #0284c7 !important;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="font-weight-bold text-info mb-1">Potongan PPh 21 Bulanan Berjalan (Skema TER PP 58/2023)</h6>
                                <small class="text-muted">Tarif TER <span id="dt_ter_rate" class="font-weight-bold">0%</span> × Gaji Bruto</small>
                            </div>
                            <h4 class="font-weight-bold text-danger mb-0" id="dt_pph21_sebulan">Rp 0</h4>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary px-4" data-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <?php $this->load->view('backend/footer'); ?>

    <script type="text/javascript">
    $(document).ready(function() {
        // Handle Detail Pajak Modal
        $(document).on('click', '.btn-detail-pajak', function() {
            var data = $(this).data('emp');
            if (typeof data === 'string') {
                try { data = JSON.parse(data); } catch(e){}
            }
            if(!data) return;

            $('#dt_emp_name').text(data.name || '-');
            $('#dt_em_code').text(data.em_code || '-');
            $('#dt_department').text(data.department || '-');
            $('#dt_npwp').text(data.npwp || 'Belum Ada');
            $('#dt_ptkp_status').text(data.ptkp_status || 'TK0');
            $('#dt_ter_category').text('TER Kategori ' + (data.ter_category || 'A'));
            $('#dt_gaji_bruto').text('Rp ' + (parseFloat(data.gaji_bruto) || 0).toLocaleString('id-ID'));

            $('#dt_biaya_jabatan').text('Rp ' + (parseFloat(data.biaya_jabatan) || 0).toLocaleString('id-ID'));
            $('#dt_neto_sebulan').text('Rp ' + (parseFloat(data.neto_sebulan) || 0).toLocaleString('id-ID'));
            $('#dt_neto_setahun').text('Rp ' + (parseFloat(data.neto_setahun) || 0).toLocaleString('id-ID'));

            $('#dt_ptkp_tahunan').text('Rp ' + (parseFloat(data.ptkp_tahunan) || 0).toLocaleString('id-ID'));
            $('#dt_pkp').text('Rp ' + (parseFloat(data.pkp) || 0).toLocaleString('id-ID'));
            $('#dt_pph21_setahun_progresif').text('Rp ' + (parseFloat(data.pph21_setahun_progresif) || 0).toLocaleString('id-ID'));
            $('#dt_pph21_sebulan_progresif').text('Rp ' + (parseFloat(data.pph21_sebulan_progresif) || 0).toLocaleString('id-ID') + ' / bln');

            $('#dt_ter_rate').text(data.ter_rate || '0%');
            $('#dt_pph21_sebulan').text('Rp ' + (parseFloat(data.pph21_sebulan) || 0).toLocaleString('id-ID'));

            $('#modalDetailPajak').modal('show');
        });
        // Init DataTable for Rekap & TER
        if ($.fn.DataTable.isDataTable('#table_rekap_pph')) {
            $('#table_rekap_pph').DataTable().destroy();
        }
        $('#table_rekap_pph').DataTable({
            "pageLength": 10,
            "ordering": true,
            "dom": 'Bfrtip',
            "buttons": ['copy', 'csv', 'excel', 'pdf', 'print']
        });

        if ($.fn.DataTable.isDataTable('#table_ter_master')) {
            $('#table_ter_master').DataTable().destroy();
        }
        var terTable = $('#table_ter_master').DataTable({
            "pageLength": 25,
            "ordering": false
        });

        // TER Category Filter Buttons
        $('.filter-ter').on('click', function() {
            $('.filter-ter').removeClass('active btn-info').addClass('btn-outline-info');
            $(this).addClass('active btn-info').removeClass('btn-outline-info');

            var cat = $(this).data('cat');
            if (cat === 'ALL') {
                $('.ter-row').show();
            } else {
                $('.ter-row').hide();
                $('.ter-cat-' + cat).show();
            }
        });

        // Load TER Database array from PHP
        var ter_db = <?php echo json_encode($ter_rates); ?>;
        var ptkp_amounts = {
            'TK0': 54000000, 'TK1': 58500000, 'TK2': 63000000, 'TK3': 67500000,
            'K0':  58500000, 'K1':  63000000, 'K2':  67500000, 'K3':  72000000
        };

        function getTerCat(ptkpKey) {
            ptkpKey = ptkpKey.toUpperCase();
            if (['TK0', 'TK1', 'K0'].indexOf(ptkpKey) !== -1) return 'A';
            if (['TK2', 'TK3', 'K1', 'K2'].indexOf(ptkpKey) !== -1) return 'B';
            if (['K3'].indexOf(ptkpKey) !== -1) return 'C';
            return 'A';
        }

        function getTerRate(cat, bruto) {
            bruto = parseFloat(bruto) || 0;
            for (var i = 0; i < ter_db.length; i++) {
                if (ter_db[i].category === cat) {
                    var minG = parseFloat(ter_db[i].min_gross);
                    var maxG = parseFloat(ter_db[i].max_gross);
                    if (bruto >= minG && bruto <= maxG) {
                        return parseFloat(ter_db[i].rate);
                    }
                }
            }
            return 0;
        }

        function calcSimulasi() {
            var bruto = parseFloat($('#sim_bruto').val()) || 0;
            var ptkpKey = $('#sim_ptkp').val() || 'TK0';

            // TER Calculation
            var cat = getTerCat(ptkpKey);
            var rate = getTerRate(cat, bruto);
            var terTax = Math.round(bruto * rate);

            // Progressive Calculation
            var ptkpVal = ptkp_amounts[ptkpKey] || 54000000;
            var bjBln = Math.min(Math.round(bruto * 0.05), 500000);
            var netoBln = Math.max(0, bruto - bjBln);
            var netoThn = netoBln * 12;
            var pkpThn = Math.max(0, netoThn - ptkpVal);

            // UU HPP Brackets
            var remPkp = pkpThn;
            var taxThn = 0;
            var brackets = [
                { limit: 60000000, rate: 0.05 },
                { limit: 250000000, rate: 0.15 },
                { limit: 500000000, rate: 0.25 },
                { limit: 5000000000, rate: 0.30 },
                { limit: Infinity, rate: 0.35 }
            ];
            var prevLimit = 0;
            for (var j = 0; j < brackets.length; j++) {
                if (remPkp <= 0) break;
                var layerCap = brackets[j].limit - prevLimit;
                var taxable = Math.min(remPkp, layerCap);
                taxThn += taxable * brackets[j].rate;
                remPkp -= taxable;
                prevLimit = brackets[j].limit;
            }
            taxThn = Math.round(taxThn);

            // Render Results
            $('#sim_card_sebulan').text('Rp ' + terTax.toLocaleString('id-ID'));
            $('#sim_sub_ter').text('TER Kategori ' + cat + ' (' + (rate * 100).toFixed(2) + '%)');
            $('#sim_card_setahun').text('Rp ' + taxThn.toLocaleString('id-ID'));
            $('#sim_card_pkp').text('Rp ' + pkpThn.toLocaleString('id-ID'));
        }

        $('#btn_sim_calc').on('click', calcSimulasi);
        $('#sim_bruto, #sim_ptkp').on('change keyup', calcSimulasi);

        // Run default simulation
        calcSimulasi();
    });
    </script>
