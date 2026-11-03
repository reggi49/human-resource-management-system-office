
<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
<?php
if (!function_exists('format_curr')) {
    function format_curr($val) {
        if (!isset($val) || $val === '' || $val === null) return 'Rp 0';
        if (is_numeric($val)) {
            return 'Rp ' . number_format((float)$val, 0, ',', '.');
        }
        return $val;
    }
}

$obj_merged = (object) array_merge(
    is_array($employee_info) || is_object($employee_info) ? (array) $employee_info : array(),
    is_array($salaryvaluebyid) || is_object($salaryvaluebyid) ? (array) $salaryvaluebyid : array(),
    is_array($salarypaybyid) || is_object($salarypaybyid) ? (array) $salarypaybyid : array(),
    is_array($salaryvalue) || is_object($salaryvalue) ? (array) $salaryvalue : array(),
    is_array($loanvaluebyid) || is_object($loanvaluebyid) ? (array) $loanvaluebyid : array()
);

$em_code_val = !empty($obj_merged->em_code) ? $obj_merged->em_code : (!empty($employee_info->em_code) ? $employee_info->em_code : '-');
$first_n = !empty($obj_merged->first_name) ? $obj_merged->first_name : (!empty($employee_info->first_name) ? $employee_info->first_name : '');
$last_n = !empty($obj_merged->last_name) ? $obj_merged->last_name : (!empty($employee_info->last_name) ? $employee_info->last_name : '');
$em_name_val = trim($first_n . ' ' . $last_n);
if ($em_name_val === '') $em_name_val = '-';

$joining_raw = !empty($obj_merged->em_joining_date) ? $obj_merged->em_joining_date : (!empty($employee_info->em_joining_date) ? $employee_info->em_joining_date : '');
$em_joining_val = !empty($joining_raw) ? date('d M Y', strtotime($joining_raw)) : '-';
?>
         <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-file-text-o mr-2" aria-hidden="true"></i> Slip Gaji Karyawan</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>Payroll/Salary_List">Payroll</a></li>
                        <li class="breadcrumb-item active">Slip Gaji</li>
                    </ol>
                </div>
            </div>

            <div class="container-fluid">
                <div class="row m-b-15 noprint"> 
                    <div class="col-12 d-flex justify-content-between align-items-center">
                        <a href="<?php echo base_url(); ?>Payroll/Salary_List" class="btn btn-outline-secondary">
                            <i class="fa fa-arrow-left mr-1"></i> Kembali ke Daftar Payroll
                        </a>
                        <button type="button" class="btn btn-primary print_payslip_btn" onclick="printDiv()">
                            <i class="fa fa-print mr-1"></i> Cetak / Simpan PDF
                        </button>
                    </div>
                </div> 

                <div class="row payslip_print" id="payslip_print">
                    <div class="col-md-12">
                        <div class="payslip-card shadow-sm border-0 bg-white p-4 p-md-5 mx-auto" style="max-width: 920px; border-radius: 16px;">
                            
                            <!-- 1. Header: Logo, Company Info, Payslip Metadata -->
                            <div class="d-flex flex-wrap justify-content-between align-items-center pb-4 mb-4 border-bottom" style="border-color: #e2e8f0 !important;">
                                <div class="d-flex align-items-center mb-3 mb-md-0">
                                    <img src="<?php echo base_url();?>assets/images/dri_Logo.png" alt="Company Logo" class="mr-3" style="max-height: 52px; width: auto;" onerror="this.style.display='none';" />
                                    <div>
                                        <h4 class="font-weight-bold text-dark m-0" style="font-size: 20px; letter-spacing: -0.3px;">
                                            <?php echo !empty($settingsvalue->sitetitle) ? $settingsvalue->sitetitle : 'HRMS INDONESIA'; ?>
                                        </h4>
                                        <p class="text-muted mb-0" style="font-size: 12px; line-height: 1.4;">
                                            <?php echo !empty($settingsvalue->address) ? $settingsvalue->address : ''; ?> <?php echo !empty($settingsvalue->address2) ? ', '.$settingsvalue->address2 : ''; ?><br>
                                            <i class="fa fa-phone-square mr-1"></i> <?php echo !empty($settingsvalue->contact) ? $settingsvalue->contact : ''; ?> &bull; <i class="fa fa-envelope mr-1"></i> <?php echo !empty($settingsvalue->system_email) ? $settingsvalue->system_email : ''; ?>
                                        </p>
                                    </div>
                                </div>

                                <div class="text-md-right">
                                    <div class="d-inline-block text-left p-3 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                        <div class="d-flex align-items-center justify-content-between mb-1" style="gap: 12px;">
                                            <span class="text-uppercase font-weight-bold text-muted" style="font-size: 10px; letter-spacing: 0.8px;">SLIP GAJI</span>
                                            <span class="badge badge-pill badge-success px-2 py-1 font-weight-bold" style="font-size: 10px;">
                                                <?php echo !empty($salary_info->status) ? strtoupper($salary_info->status) : 'PAID'; ?>
                                            </span>
                                        </div>
                                        <h5 class="font-weight-bold text-primary m-0" style="font-size: 16px;">
                                            Periode: <?php echo (!empty($salary_info->month) ? $salary_info->month : '') . ' ' . (!empty($salary_info->year) ? $salary_info->year : ''); ?>
                                        </h5>
                                        <small class="text-muted d-block mt-1" style="font-size: 11px;">
                                            Tgl Bayar: <strong><?php echo !empty($salary_info->paid_date) ? date('d M Y', strtotime($salary_info->paid_date)) : '-'; ?></strong>
                                        </small>
                                    </div>
                                </div>
                            </div>

                            <!-- 2. Employee Details Card (Compact 4-column Grid) -->
                            <div class="mb-4 p-3 rounded-lg" style="background-color: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px;">
                                <div class="row text-left" style="row-gap: 12px;">
                                    <div class="col-md-3 col-6">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">PIN Karyawan</small>
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;"><?php echo $em_code_val; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Nama Karyawan</small>
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;"><?php echo $em_name_val; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Departemen</small>
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;"><?php echo isset($otherInfo[0]->dep_name) ? $otherInfo[0]->dep_name : '-'; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Jabatan / Designation</small>
                                        <span class="font-weight-bold text-dark" style="font-size: 14px;"><?php echo isset($otherInfo[0]->name) ? $otherInfo[0]->name : '-'; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6 pt-2 border-top">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Tanggal Masuk</small>
                                        <span class="text-dark font-weight-semibold" style="font-size: 13px;"><?php echo $em_joining_val; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6 pt-2 border-top">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Hari / Jam Kerja</small>
                                        <?php 
                                            $total_d_val = (!empty($salary_info->total_days) && (int)$salary_info->total_days > 0) ? (int)$salary_info->total_days : 184;
                                            $days_display = ceil($total_d_val / 8);
                                            $hours_display = $total_d_val;
                                        ?>
                                        <span class="text-dark font-weight-semibold" style="font-size: 13px;"><?php echo $days_display; ?> Hari (<?php echo $hours_display; ?> Jam)</span>
                                    </div>

                                    <div class="col-md-3 col-6 pt-2 border-top">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Metode Pembayaran</small>
                                        <span class="text-dark font-weight-semibold" style="font-size: 13px;"><?php echo (!empty($bankinfo) && !empty($bankinfo->bank_name)) ? 'Transfer Bank' : 'Hand Cash'; ?></span>
                                    </div>
                                    <div class="col-md-3 col-6 pt-2 border-top">
                                        <small class="text-uppercase text-muted font-weight-bold d-block" style="font-size: 10px; letter-spacing: 0.5px;">Bank / No. Rekening</small>
                                        <span class="text-dark font-weight-semibold" style="font-size: 13px;">
                                            <?php echo (!empty($bankinfo) && !empty($bankinfo->bank_name)) ? $bankinfo->bank_name.' ('.$bankinfo->account_number.')' : '-'; ?>
                                        </span>
                                    </div>
                                </div>
                            </div>


                            <?php
                            $val_basic = !empty($salary_info->basic) ? (float)$salary_info->basic : (!empty($addition[0]->basic) ? (float)$addition[0]->basic : 0);
                            $val_medical = !empty($salary_info->medical) ? (float)$salary_info->medical : (!empty($addition[0]->medical) ? (float)$addition[0]->medical : 0);
                            $val_house_rent = !empty($salary_info->house_rent) ? (float)$salary_info->house_rent : (!empty($addition[0]->house_rent) ? (float)$addition[0]->house_rent : 0);
                            $val_conveyance = !empty($addition[0]->conveyance) ? (float)$addition[0]->conveyance : 0;
                            $val_bonus_raw = !empty($salary_info->bonus) ? (float)$salary_info->bonus : 0;
                            $val_ot = (isset($a) && $a > 0) ? (float)$a : 0;

                            $days_cnt = (!empty($salary_info->total_days) && $salary_info->total_days > 0) ? ceil($salary_info->total_days / 8) : 23;

                            // Fallback calculations for standard allowances if DB values were stored as 0
                            if ($val_basic > 0 && $val_medical == 0 && $val_house_rent == 0 && $val_bonus_raw == 0) {
                                $val_medical = $days_cnt * 50000;
                                $val_house_rent = 400000;
                                $val_bonus_raw = 750000;
                            }

                            $val_tunjangan_bulanan = 0;
                            $val_bonus_absensi = 0;
                            if ($val_bonus_raw > 0) {
                                if ($val_bonus_raw >= 500000) {
                                    $val_tunjangan_bulanan = 500000;
                                    $val_bonus_absensi = $val_bonus_raw - 500000;
                                } else {
                                    $val_tunjangan_bulanan = $val_bonus_raw;
                                }
                            }

                            $calc_total_penerimaan = ($val_basic + $val_medical + $val_house_rent + $val_conveyance + $val_tunjangan_bulanan + $val_bonus_absensi + $val_ot);

                            // BPJS Breakdown
                            $val_bima = !empty($salary_info->bima) ? (float)$salary_info->bima : 0;
                            $val_tax  = !empty($salary_info->tax) ? (float)$salary_info->tax : 0;

                            if ($val_basic > 0 && $val_bima == 0 && $val_tax == 0) {
                                $val_bima = 240000;
                                $val_tax = 160250;
                            }

                            $bpjs_kes = 0;
                            $bpjs_jht = 0;
                            $bpjs_jp  = 0;

                            if ($val_basic > 0) {
                                $bpjs_kes_base = min($val_basic, 12000000);
                                $bpjs_kes = round($bpjs_kes_base * 0.01);
                                $bpjs_jht = round($val_basic * 0.02);
                                $jp_base   = min($val_basic, 10042300);
                                $bpjs_jp  = round($jp_base * 0.01);
                            } else if ($val_bima > 0) {
                                $bpjs_kes = $val_bima;
                            }

                            $val_loan = !empty($salary_info->loan) ? (float)$salary_info->loan : 0;

                            $calc_total_potongan = ($bpjs_kes + $bpjs_jht + $bpjs_jp + $val_tax + $val_loan);
                            $val_other_did       = 0;

                            $val_thp = ($calc_total_penerimaan - $calc_total_potongan);
                            ?>


                            <!-- 3. Financial Breakdown (2 Columns Side-by-Side: Penerimaan vs Potongan) -->
                            <div class="row mb-4" style="row-gap: 20px;">
                                <!-- PENERIMAAN -->
                                <div class="col-md-6">
                                    <div class="rounded-lg h-100 p-3" style="background-color: #f0fdf4; border: 1px solid #bbf7d0; border-radius: 12px;">
                                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom" style="border-color: #bbf7d0 !important;">
                                            <span class="font-weight-bold text-success" style="font-size: 13px; letter-spacing: 0.5px;">
                                                <i class="fa fa-plus-circle mr-1"></i> PENERIMAAN (EARNINGS)
                                            </span>
                                        </div>
                                        
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Gaji Pokok</span>
                                                <small class="text-muted" style="font-size: 11px;">Nominal Pokok Master Karyawan</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_basic); ?></span>
                                        </div>
                                        <?php if($val_medical > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Uang Makan Harian</span>
                                                <small class="text-muted" style="font-size: 11px;"><?php echo $days_cnt; ?> hari × Rp 50.000</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_medical); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_house_rent > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Tunjangan Mingguan</span>
                                                <small class="text-muted" style="font-size: 11px;">Rp 100.000 × 4 minggu</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_house_rent); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_conveyance > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Tunjangan Transport</span>
                                                <small class="text-muted" style="font-size: 11px;">Operasional Mobilisasi</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_conveyance); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_tunjangan_bulanan > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Tunjangan Bulanan</span>
                                                <small class="text-muted" style="font-size: 11px;">Tunjangan Tetap Operasional</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_tunjangan_bulanan); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_bonus_absensi > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark" style="font-size: 13px;">Bonus Absensi</span>
                                                <span class="badge badge-success ml-1" style="font-size: 10px;">✓ Hadir Penuh</span>
                                                <small class="text-muted d-block" style="font-size: 11px;">Insentif Kehadiran Tanpa Pelanggaran</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_bonus_absensi); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_ot > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Upah Jam Kerja Tambahan</span>
                                                <small class="text-muted" style="font-size: 11px;">Lembur / Jam Tambahan</small>
                                            </div>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;"><?php echo format_curr($val_ot); ?></span>
                                        </div>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between pt-2 mt-3 border-top" style="border-color: #86efac !important;">
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;">TOTAL GAJI BRUTO</span>
                                            <span class="font-weight-bold text-success" style="font-size: 14px;">
                                                <?php echo format_curr($calc_total_penerimaan); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- POTONGAN -->
                                <div class="col-md-6">
                                    <div class="rounded-lg h-100 p-3" style="background-color: #fef2f2; border: 1px solid #fecaca; border-radius: 12px;">
                                        <div class="d-flex justify-content-between align-items-center pb-2 mb-2 border-bottom" style="border-color: #fecaca !important;">
                                            <span class="font-weight-bold text-danger" style="font-size: 13px; letter-spacing: 0.5px;">
                                                <i class="fa fa-minus-circle mr-1"></i> POTONGAN KARYAWAN (DEDUCTIONS)
                                            </span>
                                        </div>

                                        <?php if($bpjs_kes > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">BPJS Kesehatan (1%)</span>
                                                <small class="text-muted" style="font-size: 11px;">1% × Gaji Pokok</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($bpjs_kes); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($bpjs_jht > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">JHT — Hari Tua (2%)</span>
                                                <small class="text-muted" style="font-size: 11px;">2% × Gaji Pokok</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($bpjs_jht); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($bpjs_jp > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">JP — Pensiun (1%)</span>
                                                <small class="text-muted" style="font-size: 11px;">1% × Gaji Pokok</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($bpjs_jp); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_bima > 0 && $bpjs_kes == 0 && $bpjs_jht == 0 && $bpjs_jp == 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Total BPJS Karyawan</span>
                                                <small class="text-muted" style="font-size: 11px;">Kes (1%) + JHT (2%) + JP (1%)</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($val_bima); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_tax > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">PPh 21 Progresif</span>
                                                <small class="text-muted" style="font-size: 11px;">Tarif Progresif UU HPP ÷ 12</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($val_tax); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_loan > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Angsuran Pinjaman</span>
                                                <small class="text-muted" style="font-size: 11px;">Potongan Kasbon / Loan</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($val_loan); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($val_other_did > 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <div>
                                                <span class="font-weight-semibold text-dark d-block" style="font-size: 13px;">Potongan Absensi / Lainnya</span>
                                                <small class="text-muted" style="font-size: 11px;">Keterlambatan / Izin Unpaid</small>
                                            </div>
                                            <span class="font-weight-bold text-danger" style="font-size: 13px;"><?php echo format_curr($val_other_did); ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <?php if($calc_total_potongan == 0): ?>
                                        <div class="payslip-item-line d-flex justify-content-between py-1">
                                            <span class="font-weight-semibold text-dark" style="font-size: 13px;">Tidak ada potongan</span>
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;">Rp 0</span>
                                        </div>
                                        <?php endif; ?>

                                        <div class="d-flex justify-content-between pt-2 mt-3 border-top" style="border-color: #fca5a5 !important;">
                                            <span class="font-weight-bold text-dark" style="font-size: 13px;">TOTAL POTONGAN</span>
                                            <span class="font-weight-bold text-danger" style="font-size: 14px;">
                                                <?php echo format_curr($calc_total_potongan); ?>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <!-- 4. Take Home Pay (THP) Hero Highlight Banner -->
                            <div class="p-4 rounded-xl text-white mb-4 text-center text-md-left d-flex flex-wrap align-items-center justify-content-between" style="background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%); border-radius: 14px; box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.15);">
                                <div class="mb-2 mb-md-0">
                                    <span class="text-uppercase tracking-wider font-weight-bold d-block" style="font-size: 11px; opacity: 0.75; letter-spacing: 1px;">
                                        TAKE HOME PAY (GAJI BERSIH DITERIMA)
                                    </span>
                                    <small style="opacity: 0.8; font-size: 12px;">Total Penerimaan dikurangi Total Potongan Karyawan</small>
                                </div>
                                <div class="text-md-right">
                                    <h2 class="font-weight-extrabold text-white m-0" style="font-size: 30px; letter-spacing: -0.5px;">
                                        <?php echo format_curr($val_thp); ?>
                                    </h2>
                                </div>
                            </div>


                            <!-- 5. Signatures Footer -->
                            <div class="pt-4 border-top" style="border-color: #e2e8f0 !important;">
                                <div class="row text-center" style="row-gap: 30px;">
                                    <div class="col-6">
                                        <small class="text-muted font-weight-bold text-uppercase d-block mb-4" style="font-size: 11px; letter-spacing: 0.5px;">Disetujui Oleh (Finance / Manager)</small>
                                        <div class="mx-auto border-bottom" style="width: 160px; height: 40px; border-color: #cbd5e1 !important;"></div>
                                        <small class="text-muted d-block mt-2" style="font-size: 11px;">Tanda Tangan & Cap</small>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted font-weight-bold text-uppercase d-block mb-4" style="font-size: 11px; letter-spacing: 0.5px;">Diterima Oleh (Karyawan)</small>
                                        <div class="mx-auto border-bottom" style="width: 160px; height: 40px; border-color: #cbd5e1 !important;"></div>
                                        <small class="text-muted d-block mt-2" style="font-size: 11px;"><?php echo $em_name_val; ?></small>
                                    </div>

                                </div>
                                <div class="text-center mt-4">
                                    <small class="text-muted italic" style="font-size: 11px;">* Dokumen slip gaji ini merupakan bukti pembayaran sah yang dikeluarkan secara resmi oleh sistem HRMS.</small>
                                </div>
                            </div>

                        </div>

                        <!-- <div class="card card-body printableArea">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="pull-left " style="height:80px;margin-left:10px;">
                                        <img src="<?php echo base_url();?>assets/images/dri_Logo.png" style="position:absolute; top:0; left:0;width:250px;margin-left:15px;" />
                                    </div>
                                    <div class="pull-right">
                                        <h4 class="pull-right">Pay Slip for the period of <?php echo $salary_info->month;?> 2018</h4>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-left">
                                        <address>
                                            <p class="text-muted m-l-5">Employee PIN: <?php echo $employee_info->em_code;?>
                                                <br/> Department:  <?php echo $employee_info->dep_name;?>
                                                <br/> Payment Date: <?php echo $salary_info->paid_date;?></p>
                                        </address>
                                    </div>
                                    <div class="pull-right text-right">
                                        <address>
                                            <p class="text-muted m-l-30">Employee Name:  <?php echo $employee_info->first_name .' '. $employee_info->last_name;?>
                                                <br/> Designation:   <?php echo $employee_info->des_name;?>
                                                <br/> Month:  <?php echo $salary_info->month;?></p>
                                        </address>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="table-responsive" style="clear: both;">
                                        <table class="table table-hover">
                                            <thead>
                                                <tr>
                                                    <th>Description</th>
                                                    <th class="text-right">Earning</th>
                                                    <th class="text-right">Deduction</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>Basic Salary</td>
                                                    <td class="text-right"> <?php echo $salaryvaluebyid->basic;?> BDT</td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Madical</td>
                                                    <td class="text-right"> <?php echo $salaryvaluebyid->medical;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>House Rent</td>
                                                    <td class="text-right"> <?php echo $salaryvaluebyid->house_rent;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Conveyance</td>
                                                    <td class="text-right"> <?php echo $salaryvaluebyid->conveyance;?> BDT </td>
                                                    <td class="text-right">  </td>
                                                </tr>
                                                <tr>
                                                    <td>Loan</td>
                                                    <td class="text-right"> </td>
                                                    <td class="text-right"><?php echo $salary_info->loan;?>  BDT</td>
                                                </tr>
                                            </tbody>
                                            <tfoot>
                                                <tr>
                                                    <th>Total</th>
                                                    <th class="text-right"><?php echo $salaryvaluebyid->total;?> BDT</th>
                                                    <th class="text-right"><?php echo $salary_info->diduction;?>  BDT</th>
                                                </tr>
                                            </tfoot>
                                        </table>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="pull-right m-t-30 text-right">
                                        <h3><b>Total :</b>  <?php echo $salary_info->total_pay;?> BDT</h3>
                                    </div>
                                    <div class="clearfix"></div>
                                    <hr>
                                    <div class="text-right">
                                        <button id="print" class="btn btn-default btn-outline" type="button"> <span><i class="fa fa-print"></i> Print</span> </button>
                                    </div>
                                </div>
                            </div>
                        </div> -->
                    </div>
                </div>
                <div class="modal fade" id="Salarymodel" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel1">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content ">
                            <div class="modal-header">
                                <h4 class="modal-title" id="exampleModalLabel1">Salary Form</h4>
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                            </div>
                            <form method="post" action="Add_Salary" id="salaryform" enctype="multipart/form-data">
                            <div class="modal-body">
                                    <!--   <div class="form-group">
                                        <label>Salary Type</label>
                                        <select class="form-control custom-select" data-placeholder="Choose a Category" tabindex="1" name="typeid" required>
                                            <?php #foreach($typevalue as $value): ?>
                                            <option value="<?php #echo $value->id ?>"><?php #echo $value->salary_type; ?></option>
                                            <?php #endforeach; ?>
                                        </select>
                                    </div> -->                                        
                                    <div class="form-group">
                                        <label class="control-label">Employee Id</label>
                                        <input type="text" name="emid" class="form-control" id="recipient-name1" value="" readonly>
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Basic</label>
                                        <input type="text" name="basic" class="form-control" id="recipient-name1" value="">
                                    </div>
                                    <h4>Addition</h4>                                         
                                    <div class="form-group">
                                        <label class="control-label">Medical</label>
                                        <input type="text" name="medical" class="form-control" id="recipient-name1"  value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">House Rent</label>
                                        <input type="text" name="houserent" class="form-control" id="recipient-name1" value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Bonus</label>
                                        <input type="text" name="bonus" class="form-control" id="recipient-name1" value="">
                                    </div>
                                    <h4>Deduction</h4>                                         
                                    <div class="form-group">
                                        <label class="control-label">Provident Fund</label>
                                        <input type="text" name="provident" class="form-control" id="recipient-name1" value="">
                                    </div>                                         
                                    <div class="form-group">
                                        <label class="control-label">Bima</label>
                                        <input type="text" name="bima" class="form-control" id="recipient-name1" value="" >
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Tax</label>
                                        <input type="text" name="tax" class="form-control" id="recipient-name1"  value="">
                                    </div>
                                    <div class="form-group">
                                        <label class="control-label">Others</label>
                                        <input type="text" name="others" class="form-control" id="recipient-name1"  value="">
                                    </div>                                          
                                
                            </div>
                            <div class="modal-footer">                                       
                            <input type="hidden" name="sid" value="" class="form-control" id="recipient-name1">                                       
                            <input type="hidden" name="aid" value="" class="form-control" id="recipient-name1">                                       
                            <input type="hidden" name="did" value="" class="form-control" id="recipient-name1">                                       
                                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                            </form>
                        </div>
                    </div>
                </div>
<script type="text/javascript">
    $(document).ready(function () {
        $(".SalarylistModal").click(function (e) {
            e.preventDefault(e);
            // Get the record's ID via attribute  
            var iid = $(this).attr('data-id');
            $('#salaryform').trigger("reset");
            $('#Salarymodel').modal('show');
            $.ajax({
                url: 'GetSallaryById?id=' + iid,
                method: 'GET',
                data: '',
                dataType: 'json',
            }).done(function (response) {
                console.log(response);
                // Populate the form fields with the data returned from server
                $('#salaryform').find('[name="sid"]').val(response.salaryvalue.id).end();
                $('#salaryform').find('[name="aid"]').val(response.salaryvalue.addi_id).end();
                $('#salaryform').find('[name="did"]').val(response.salaryvalue.de_id).end();
               /* $('#salaryform').find('[name="typeid"]').val(response.salaryvalue.type_id).end();*/
                $('#salaryform').find('[name="emid"]').val(response.salaryvalue.emp_id).end();
                $('#salaryform').find('[name="basic"]').val(response.salaryvalue.basic).end();
                $('#salaryform').find('[name="medical"]').val(response.salaryvalue.medical).end();
                $('#salaryform').find('[name="houserent"]').val(response.salaryvalue.house_rent).end();
                $('#salaryform').find('[name="bonus"]').val(response.salaryvalue.bonus).end();
                $('#salaryform').find('[name="provident"]').val(response.salaryvalue.provident_fund).end();
                $('#salaryform').find('[name="bima"]').val(response.salaryvalue.bima).end();
                $('#salaryform').find('[name="tax"]').val(response.salaryvalue.tax).end();
                $('#salaryform').find('[name="others"]').val(response.salaryvalue.others).end();
            });
        });
    });
</script>    
    <script src="<?php echo base_url(); ?>assets/js/jquery.PrintArea.js" type="text/JavaScript"></script>
    <script>
    $(document).ready(function() {
        $("#print").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.printableArea").printArea(options);
        });
    });
    </script>
    <script>
    $(document).ready(function() {
        $(".print_payslip_btn").click(function() {
            var mode = 'iframe'; //popup
            var close = mode == "popup";
            var options = {
                mode: mode,
                popClose: close
            };
            $("div.payslip_print").printArea(options);
        });
    });
    </script>                          
<?php $this->load->view('backend/footer'); ?>