<?php
$this->load->view('backend/header');
?>
<?php
$this->load->view('backend/sidebar');
?>
<div class="page-wrapper">
  <div class="message"></div>
  <div class="row page-titles">
    <div class="col-md-5 align-self-center">
      <h3 class="text-themecolor"><i class="fa fa-money"></i> Penggajian Indonesia</h3>
    </div>
    <div class="col-md-7 align-self-center">
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
        <li class="breadcrumb-item active">Penggajian</li>
      </ol>
    </div>
  </div>

  <div class="container-fluid">
    <!-- Filter Bar -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><i class="fa fa-filter m-r-5 text-primary"></i> Filter Karyawan & Periode</h5>
            <form method="post" action="" id="salaryform" class="row">
              <div class="col-md-4 col-sm-6 mb-3">
                <label class="font-weight-bold">Departemen</label>
                <select class="form-control custom-select" id="depid" name="depid" required>
                  <option value="#">Pilih Departemen</option>
                  <?php foreach ($department as $value): ?>
                  <option value="<?php echo $value->id; ?>"><?php echo $value->dep_name; ?></option>
                  <?php endforeach; ?>
                </select>
              </div>
              <div class="col-md-4 col-sm-6 mb-3">
                <label class="font-weight-bold">Bulan / Tahun</label>
                <input type='text' name="datetime" class="form-control mydatetimepicker" placeholder="Contoh: 07-2026"/>
              </div>
              <div class="col-md-2 col-sm-6 mb-3 d-flex align-items-end">
                <button type="submit" id="BtnSubmit" class="btn btn-primary btn-block">
                  <i class="fa fa-search m-r-5"></i> Tampilkan
                </button>
              </div>
              <div class="col-md-2 col-sm-6 mb-3 d-flex align-items-end">
                <button type="button" id="btnGenerateDummyAtt" class="btn btn-outline-success btn-block">
                  <i class="fa fa-calendar-check-o m-r-5"></i> Dummy Absen
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!-- Employee Salary Table -->
    <div class="row">
      <div class="col-12">
        <div class="card">
          <div class="card-body">
            <h5 class="card-title"><i class="fa fa-users m-r-5 text-primary"></i> Daftar Karyawan</h5>
            <div class="table-responsive">
              <table id="payrollTable" class="table table-hover table-striped table-bordered" width="100%">
                <thead>
                  <tr>
                    <th style="width: 120px;">PIN</th>
                    <th>Nama Karyawan</th>
                    <th style="width: 200px;">Gaji Pokok</th>
                    <th style="width: 180px;" class="text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody class="payroll">
                  <tr>
                    <td colspan="4" class="text-center text-muted p-30">
                      <i class="fa fa-info-circle m-r-5"></i> Pilih departemen dan bulan, lalu klik <strong>Tampilkan</strong>.
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- ==================== MODAL HITUNG GAJI ==================== -->
    <div class="modal fade" id="generatePayrollModal" tabindex="-1" role="dialog" aria-hidden="true">
      <div class="modal-dialog modal-xl" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h4 class="modal-title font-weight-bold"><i class="fa fa-calculator m-r-8"></i> Rincian Slip Gaji Karyawan</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <form method="post" action="pay_salary_add_record" id="generatePayrollForm">
            <div class="modal-body">

              <!-- Top Employee Selection Bar -->
              <div class="row mb-4">
                <div class="col-md-6 mb-2">
                  <label class="font-weight-bold">Nama Karyawan</label>
                  <select class="form-control custom-select" id="emid" name="emid" required>
                    <option value="#">Pilih Karyawan</option>
                    <?php foreach ($employee as $value): ?>
                    <option value="<?php echo $value->em_id; ?>">
                      <?php echo $value->first_name.' '.$value->last_name; ?>
                    </option>
                    <?php endforeach; ?>
                  </select>
                </div>
                <div class="col-md-6 mb-2">
                  <label class="font-weight-bold">Status PTKP (PPh 21)</label>
                  <select class="form-control custom-select" id="ptkpStatus" name="ptkp_status">
                    <option value="TK0">TK/0 — Tidak Kawin (PTKP Rp 54.000.000/thn)</option>
                    <option value="K0">K/0 — Kawin, 0 Tanggungan (PTKP Rp 58.500.000/thn)</option>
                    <option value="K1">K/1 — Kawin, 1 Tanggungan (PTKP Rp 63.000.000/thn)</option>
                    <option value="K2">K/2 — Kawin, 2 Tanggungan (PTKP Rp 67.500.000/thn)</option>
                    <option value="K3">K/3 — Kawin, 3 Tanggungan (PTKP Rp 72.000.000/thn)</option>
                  </select>
                </div>
              </div>

              <!-- Loading State -->
              <div id="payrollLoading" class="text-center py-5" style="display:none;">
                <i class="fa fa-circle-o-notch fa-spin fa-3x text-primary"></i>
                <p class="mt-3 text-muted font-weight-bold">Mengkalkulasi penggajian Indonesia...</p>
              </div>

              <!-- Error State -->
              <div id="payrollError" class="alert alert-danger" style="display:none;"></div>

              <!-- Main Results Section (2 Columns Flex/Grid) -->
              <div id="payrollResults" style="display:none;">
                <div class="row">
                  
                  <!-- === PANEL KIRI: PENDAPATAN === -->
                  <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="background-color: #f8fafc; border-left: 4px solid #10b981 !important;">
                      <div class="card-body p-3">
                        <h6 class="font-weight-bold text-success mb-3">
                          <i class="fa fa-plus-circle m-r-5"></i> RINCIAN PENDAPATAN (EARNINGS)
                        </h6>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Gaji Pokok</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Nominal Pokok dari Master Karyawan</small>
                          </div>
                          <span class="payroll-item-val" id="val_gaji_pokok">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Uang Makan</span>
                            <small class="text-muted d-block" style="font-size: 11px;" id="sub_uang_makan"><span id="val_days_attended">0</span> hari × Rp 50.000</small>
                          </div>
                          <span class="payroll-item-val" id="val_uang_makan">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Tunjangan Mingguan</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Rp 100.000 × 4 minggu/bulan</small>
                          </div>
                          <span class="payroll-item-val" id="val_tunjangan_mingguan">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Tunjangan Bulanan</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Tunjangan Tetap Operasional</small>
                          </div>
                          <span class="payroll-item-val" id="val_tunjangan_bulanan">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Bonus Absensi <span id="badge_bonus" class="badge ml-1">-</span></span>
                            <small class="text-muted d-block" style="font-size: 11px;">Diberikan jika tidak ada pelanggaran absen</small>
                          </div>
                          <span class="payroll-item-val" id="val_bonus_absensi">-</span>
                        </div>

                        <div class="payroll-item-row mt-2 pt-2 border-top">
                          <span class="font-weight-bold text-dark">TOTAL GAJI BRUTO</span>
                          <span class="font-weight-bold text-success" style="font-size: 16px;" id="val_gaji_bruto">-</span>
                        </div>
                      </div>
                    </div>
                  </div>

                  <!-- === PANEL KANAN: POTONGAN === -->
                  <div class="col-md-6 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="background-color: #f8fafc; border-left: 4px solid #ef4444 !important;">
                      <div class="card-body p-3">
                        <h6 class="font-weight-bold text-danger mb-3">
                          <i class="fa fa-minus-circle m-r-5"></i> POTONGAN KARYAWAN (DEDUCTIONS)
                        </h6>

<?php 
  $bpjs_jp_cap = isset($settingsvalue->bpjs_jp_cap) && !empty($settingsvalue->bpjs_jp_cap) ? (float)$settingsvalue->bpjs_jp_cap : 10042300;
  $bpjs_kes_cap = isset($settingsvalue->bpjs_kes_cap) && !empty($settingsvalue->bpjs_kes_cap) ? (float)$settingsvalue->bpjs_kes_cap : 12000000;
?>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">BPJS Kesehatan (1%) <i class="fa fa-info-circle text-info ml-1" data-toggle="tooltip" title="1% × Bruto (Maksimal cap Rp <?php echo number_format($bpjs_kes_cap, 0, ',', '.'); ?>)"></i></span>
                            <small class="text-muted d-block" style="font-size: 11px;" id="sub_bpjs_kes">1% × Bruto</small>
                          </div>
                          <span class="payroll-item-val" id="val_bpjs_kes">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">JHT — Hari Tua (2%) <i class="fa fa-info-circle text-info ml-1" data-toggle="tooltip" title="2% × Bruto"></i></span>
                            <small class="text-muted d-block" style="font-size: 11px;" id="sub_jht">2% × Bruto</small>
                          </div>
                          <span class="payroll-item-val" id="val_jht">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">JP — Pensiun (1%) <i class="fa fa-info-circle text-info ml-1" data-toggle="tooltip" title="1% × Bruto (Maksimal cap Rp <?php echo number_format($bpjs_jp_cap, 0, ',', '.'); ?>)"></i></span>
                            <small class="text-muted d-block" style="font-size: 11px;" id="sub_jp">1% × Bruto</small>
                          </div>
                          <span class="payroll-item-val" id="val_jp">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label font-weight-bold">Total BPJS Karyawan</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Total Kes (1%) + JHT (2%) + JP (1%)</small>
                          </div>
                          <span class="payroll-item-val text-dark" id="val_total_bpjs">-</span>
                        <div class="payroll-item-row mt-2">
                          <div>
                            <span class="payroll-item-label font-weight-bold">PPh 21 / bulan <i class="fa fa-info-circle text-info ml-1" data-toggle="tooltip" title="Hitung Tarif Efektif Rata-rata Bulanan (TER PP 58/2023)"></i></span>
                            <small class="text-muted d-block" style="font-size: 11px;" id="sub_pph21">TER PP 58/2023</small>
                          </div>
                          <span class="payroll-item-val text-danger" id="val_pph21">-</span>
                        </div>

                        <div class="payroll-item-row">
                          <div>
                            <span class="payroll-item-label">Angsuran Pinjaman</span>
                            <small class="text-muted d-block" style="font-size: 11px;">Potongan Kasbon / Loan</small>
                          </div>
                          <span class="payroll-item-val" id="val_loan">-</span>
                        </div>

                        <div class="payroll-item-row mt-2 pt-2 border-top">
                          <span class="font-weight-bold text-dark">TOTAL POTONGAN</span>
                          <span class="font-weight-bold text-danger" style="font-size: 16px;" id="val_total_potongan">-</span>
                        </div>
                      </div>
                    </div>
                  </div>

                </div><!-- end row -->

                <!-- === BANNER TAKE HOME PAY (THP) === -->
                <div class="row mb-3">
                  <div class="col-12">
                    <div class="card border-0 shadow-sm text-white" style="background: linear-gradient(135deg, #10b981 0%, #047857 100%); border-radius: 14px;">
                      <div class="card-body p-3 text-center">
                        <span class="text-uppercase tracking-wider font-weight-bold" style="font-size: 12px; opacity: 0.9;">Take Home Pay (Gaji Bersih Diterima)</span>
                        <h2 class="font-weight-bold my-1 text-white" id="val_thp" style="font-size: 28px;">Rp 0</h2>
                        <small style="opacity: 0.85;">Formula: Gaji Bruto dikurangi Total Potongan Karyawan</small>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Accordion Beban Perusahaan -->
                <div class="row mb-3">
                  <div class="col-12">
                    <div class="card border mb-0">
                      <div class="card-header p-2 bg-light cursor-pointer" data-toggle="collapse" data-target="#bebanPerusahaan" style="cursor:pointer;">
                        <small class="font-weight-bold text-muted"><i class="fa fa-building m-r-5"></i> Lihat Informasi Beban Perusahaan (BPJS Employer Share 10.24%)</small>
                      </div>
                      <div id="bebanPerusahaan" class="collapse">
                        <div class="card-body p-3">
                          <div class="payroll-item-row">
                            <span class="payroll-item-label">BPJS Kesehatan Perusahaan (4%)</span>
                            <span class="payroll-item-val" id="val_bpjs_kes_co">-</span>
                          </div>
                          <div class="payroll-item-row">
                            <span class="payroll-item-label">JHT Perusahaan (3.7%)</span>
                            <span class="payroll-item-val" id="val_jht_co">-</span>
                          </div>
                          <div class="payroll-item-row">
                            <span class="payroll-item-label">JP Perusahaan (2%)</span>
                            <span class="payroll-item-val" id="val_jp_co">-</span>
                          </div>
                          <div class="payroll-item-row">
                            <span class="payroll-item-label">JKK (0.24%) + JKM (0.3%)</span>
                            <span class="payroll-item-val" id="val_jkk_jkm_co">-</span>
                          </div>
                          <div class="payroll-item-row font-weight-bold border-top pt-2">
                            <span class="payroll-item-label font-weight-bold">Total Beban Perusahaan</span>
                            <span class="payroll-item-val text-primary" id="val_total_bpjs_co">-</span>
                          </div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Payment Method Options -->
                <div class="row">
                  <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">Status Pembayaran</label><br>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="st_paid" value="Paid" checked>
                      <label class="form-check-label" for="st_paid">Paid</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="status" id="st_process" value="Process">
                      <label class="form-check-label" for="st_process">Process</label>
                    </div>
                  </div>
                  <div class="col-md-6 mb-2">
                    <label class="font-weight-bold">Metode Pembayaran</label><br>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="paid_type" id="pt_bank" value="Bank" checked>
                      <label class="form-check-label" for="pt_bank">Transfer Bank</label>
                    </div>
                    <div class="form-check form-check-inline">
                      <input class="form-check-input" type="radio" name="paid_type" id="pt_cash" value="Hand Cash">
                      <label class="form-check-label" for="pt_cash">Cash</label>
                    </div>
                  </div>
                </div>

              </div><!-- end #payrollResults -->

              <!-- Hidden form inputs -->
              <input type="hidden" name="paydate" value="<?php echo date('Y-m-d'); ?>">
              <input type="hidden" name="month" id="hid_month">
              <input type="hidden" name="year" id="hid_year">
              <input type="hidden" name="gaji_pokok" id="hid_gaji_pokok">
              <input type="hidden" name="uang_makan" id="hid_uang_makan">
              <input type="hidden" name="tunjangan_mingguan" id="hid_tunjangan_mingguan">
              <input type="hidden" name="tunjangan_bulanan" id="hid_tunjangan_bulanan">
              <input type="hidden" name="bonus_absensi" id="hid_bonus_absensi">
              <input type="hidden" name="gaji_bruto" id="hid_gaji_bruto">
              <input type="hidden" name="total_bpjs_karyawan" id="hid_total_bpjs">
              <input type="hidden" name="pph21_sebulan" id="hid_pph21">
              <input type="hidden" name="loan_amount" id="hid_loan">
              <input type="hidden" name="loan_id" id="hid_loan_id">
              <input type="hidden" name="total_potongan" id="hid_total_potongan">
              <input type="hidden" name="thp" id="hid_thp">

            </div>

            <div class="modal-footer">
              <button type="button" class="btn btn-secondary" data-dismiss="modal">
                <i class="fa fa-times m-r-5"></i> Batal
              </button>
              <button type="button" class="btn btn-info" id="btnRecalculate" style="display:none;">
                <i class="fa fa-refresh m-r-5"></i> Hitung Ulang
              </button>
              <button type="submit" class="btn btn-success font-weight-bold" id="btnSimpan" style="display:none;">
                <i class="fa fa-save m-r-5"></i> Simpan Slip Gaji
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>
    <!-- ==================== END MODAL ==================== -->

  </div><!-- end container-fluid -->

  <script type="text/javascript">
  $(document).ready(function(){

    function formatRp(num) {
      if(num === null || num === undefined || isNaN(num)) return 'Rp 0';
      return 'Rp ' + parseInt(num).toLocaleString('id-ID');
    }

    // Initialize Bootstrap Tooltips
    $('[data-toggle="tooltip"]').tooltip();

    // 1. Submit Filter Karyawan
    $("#BtnSubmit").on("click", function(event){
      event.preventDefault();
      var depid = $('#depid').val();
      var datetime = $('.mydatetimepicker').val();

      if(depid === '#' || !depid) {
        alert('Silakan pilih departemen terlebih dahulu.');
        return;
      }

      $.ajax({
        url: "<?php echo base_url(); ?>Payroll/load_employee_by_deptID_for_pay?date_time="+datetime+"&dep_id="+depid,
        type: "GET",
        success: function(response) {
          $('.payroll').html(response);
        },
        error: function() {
          alert('Gagal memuat data karyawan.');
        }
      });
    });

    // 2. Click "Hitung Gaji" Modal Trigger
    $(document).on('click', '.salaryGenerateModal', function(e){
      e.preventDefault();
      $('#generatePayrollModal').modal('show');

      var emid = $(this).data('id');
      var month = $(this).data('month');
      var year = $(this).data('year');

      $('#emid').val(emid);
      $('#hid_month').val(month);
      $('#hid_year').val(year);

      doCalculation(emid, month, year);
    });

    // 3. Recalculate Button
    $('#btnRecalculate').on('click', function(){
      var emid = $('#emid').val();
      var month = $('#hid_month').val();
      var year = $('#hid_year').val();
      doCalculation(emid, month, year);
    });

    // 4. Perform Ajax Payroll Calculation
    function doCalculation(emid, month, year) {
      var ptkp = $('#ptkpStatus').val();

      $('#payrollLoading').show();
      $('#payrollResults').hide();
      $('#payrollError').hide();
      $('#btnSimpan').hide();
      $('#btnRecalculate').hide();

      $.ajax({
        url: '<?php echo base_url(); ?>Payroll/generate_payroll_for_each_employee',
        method: 'GET',
        data: {
          month: month,
          year: year,
          employeeID: emid,
          ptkp_status: ptkp
        },
        dataType: 'json',
      }).done(function(res){
        $('#payrollLoading').hide();

        if(res.error) {
          $('#payrollError').text(res.error).show();
          return;
        }

        // Pendapatan
        $('#val_gaji_pokok').text(formatRp(res.gaji_pokok));
        $('#val_days_attended').text(res.days_attended);
        $('#sub_uang_makan').text(res.days_attended + ' hari × Rp 50.000');
        $('#val_uang_makan').text(formatRp(res.uang_makan));
        $('#val_tunjangan_mingguan').text(formatRp(res.tunjangan_mingguan));
        $('#val_tunjangan_bulanan').text(formatRp(res.tunjangan_bulanan));
        $('#val_bonus_absensi').text(formatRp(res.bonus_absensi));
        $('#val_gaji_bruto').text(formatRp(res.gaji_bruto));

        if(res.bonus_absensi > 0) {
          $('#badge_bonus').removeClass('badge-warning badge-secondary').addClass('badge-success').text('✓ Hadir Penuh');
        } else {
          $('#badge_bonus').removeClass('badge-success badge-secondary').addClass('badge-warning').text('✗ Tidak Penuh');
        }

        // Potongan
        var jpCapVal = <?php echo $bpjs_jp_cap; ?>;
        var kesCapVal = <?php echo $bpjs_kes_cap; ?>;

        $('#val_bpjs_kes').text(formatRp(res.bpjs_kes_karyawan));
        $('#sub_bpjs_kes').text('1% × ' + formatRp(Math.min(res.gaji_bruto, kesCapVal)));
        
        $('#val_jht').text(formatRp(res.jht_karyawan));
        $('#sub_jht').text('2% × ' + formatRp(res.gaji_bruto));

        $('#val_jp').text(formatRp(res.jp_karyawan));
        var jpBaseLabel = res.gaji_bruto > jpCapVal ? formatRp(jpCapVal) + ' (cap max)' : formatRp(res.gaji_bruto);
        $('#sub_jp').text('1% × ' + jpBaseLabel);

        $('#val_total_bpjs').text(formatRp(res.total_bpjs_karyawan));

        $('#val_biaya_jabatan').text(formatRp(res.biaya_jabatan));
        $('#sub_biaya_jabatan').text('5% × Bruto ' + formatRp(res.gaji_bruto) + ' (max 500k)');

        $('#val_pkp').text(formatRp(res.pkp));
        $('#sub_pkp').text('(Neto ' + formatRp(res.neto_sebulan) + ' × 12) - PTKP ' + formatRp(res.ptkp_tahunan));

        $('#val_pph21').text(formatRp(res.pph21_sebulan));
        $('#sub_pph21').text('Skema TER ' + res.ter_category + ' (' + res.ter_rate_percent + ') × Bruto ' + formatRp(res.gaji_bruto));

        $('#val_loan').text(formatRp(res.loan_amount));
        $('#val_total_potongan').text(formatRp(res.total_potongan));

        // THP
        $('#val_thp').text(formatRp(res.thp));

        // Beban Perusahaan
        $('#val_bpjs_kes_co').text(formatRp(res.bpjs_kes_perusahaan));
        $('#val_jht_co').text(formatRp(res.jht_perusahaan));
        $('#val_jp_co').text(formatRp(res.jp_perusahaan));
        $('#val_jkk_jkm_co').text(formatRp(res.jkk_perusahaan + res.jkm_perusahaan));
        $('#val_total_bpjs_co').text(formatRp(res.total_bpjs_perusahaan));

        // Hidden Inputs
        $('#hid_gaji_pokok').val(res.gaji_pokok);
        $('#hid_uang_makan').val(res.uang_makan);
        $('#hid_tunjangan_mingguan').val(res.tunjangan_mingguan);
        $('#hid_tunjangan_bulanan').val(res.tunjangan_bulanan);
        $('#hid_bonus_absensi').val(res.bonus_absensi);
        $('#hid_gaji_bruto').val(res.gaji_bruto);
        $('#hid_total_bpjs').val(res.total_bpjs_karyawan);
        $('#hid_pph21').val(res.pph21_sebulan);
        $('#hid_loan').val(res.loan_amount);
        $('#hid_total_potongan').val(res.total_potongan);
        $('#hid_loan_id').val(res.loan_id);
        $('#hid_thp').val(res.thp);
        $('#hid_month').val(res.month);
        $('#hid_year').val(res.year);

        // Re-initialize Tooltips for dynamically loaded contents
        $('[data-toggle="tooltip"]').tooltip();

        $('#payrollResults').show();
        $('#btnSimpan').show();
        $('#btnRecalculate').show();

      }).fail(function(xhr){
        $('#payrollLoading').hide();
        $('#payrollError').text('Gagal menghitung gaji. Silakan coba lagi.').show();
      });
    }

    // 5. Generate Dummy Attendance
    $('#btnGenerateDummyAtt').on('click', function(e){
      e.preventDefault();
      var datetime = $('.mydatetimepicker').val();
      var month = '', year = '';
      if(datetime){
        var parts = datetime.split('-');
        if(parts.length >= 2){ month = parts[0]; year = parts[1]; }
      }
      if(!month || !year){
        var d = new Date();
        month = d.getMonth() + 1;
        year = d.getFullYear();
      }
      if(confirm('Generate absensi dummy (Senin-Jumat, 08:00-17:00) untuk periode ' + month + '-' + year + '?')){
        $.ajax({
          url: '<?php echo base_url(); ?>Payroll/generate_dummy_attendance',
          type: 'POST',
          data: {month: month, year: year},
          dataType: 'json',
          success: function(res){ alert(res.message); },
          error: function(){ alert('Gagal generate absensi dummy'); }
        });
      }
    });

    // 6. Save Form Submit
    $('#generatePayrollForm').on('submit', function(e){
      e.preventDefault();
      var formData = $(this).serialize();
      $.ajax({
        url: '<?php echo base_url(); ?>Payroll/pay_salary_add_record',
        type: 'POST',
        data: formData,
        success: function(response){
          if(response.indexOf('Successfully') !== -1 || response.indexOf('added') !== -1) {
            alert('Slip gaji berhasil disimpan!');
            $('#generatePayrollModal').modal('hide');
          } else {
            alert('Respon: ' + response);
          }
        },
        error: function(){
          alert('Gagal menyimpan slip gaji.');
        }
      });
    });

  });
  </script>
<?php
$this->load->view('backend/footer');
?>