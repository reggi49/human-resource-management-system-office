<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
      <div class="page-wrapper">
            <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor">Settings</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Settings</li>
                    </ol>
                </div>
            </div>
           <?php echo validation_errors(); ?>
           <?php echo $this->upload->display_errors(); ?>
           <?php echo $this->session->flashdata('formdata'); ?>
           <?php echo $this->session->flashdata('feedback'); ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-md-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"> Settings  </h4>
                            </div>
                            <div class="card-body">
                                <div class="table_body">
                                    <form action="Add_Settings" id="fileUploadForm"  method="post" enctype="multipart/form-data" accept-charset="utf-8">
                                        <div class="form-group clearfix">
                                            <label for="" class="col-md-3">Upload site logo</label>
                                            <div class="col-md-9">
                                                <div class="file_prev inb">
                                                <?php if($settingsvalue->sitelogo){ ?>
                                                    <img src="<?php echo base_url(); ?>assets/images/<?php echo $settingsvalue->sitelogo; ?>" height="100" width="167">
                                                    <?php } else { ?>
                                                    <img src="<?php echo base_url(); ?>assets/img/ci-logo.png" height="100" width="167">
                                                <?php } ?>
                                                </div>
                                                <label for="img_url" class="custom-file-upload"><i class="fa fa-camera" aria-hidden="true"></i> Upload Logo</label>
                                                <input type="file" value="" class="" id="img_url" name="img_url" aria-describedby="fileHelp">
                                            </div>
                                        </div>
                                        <div class="form-group clearfix">
                                            <label for="title" class="col-md-3">Site Title</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="title" value="<?php echo $settingsvalue->sitetitle; ?>" id="title" placeholder="Title..." required minlength="7" maxlength="120">
                                            </div>
                                        </div>                                    
                                        <div class="form-group clearfix">
                                            <label for="description" class="col-md-3">Description</label>
                                            <div class="col-md-9">
                                                <textarea class="form-control" id="description" value="<?php echo $settingsvalue->description; ?>" name="description" rows="6" required minlength="20" maxlength="512"><?php echo $settingsvalue->description; ?></textarea>
                                            </div>                                        
                                        </div>                                                                
                                        <div class="form-group clearfix">
                                            <label for="copyright" class="col-md-3">Copyright</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="copyright" value="<?php echo $settingsvalue->copyright; ?>" id="copyright" placeholder="copyright...">
                                            </div>
                                        </div>                                
                                        <div class="form-group clearfix">
                                            <label for="contact" class="col-md-3">Contact</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="contact" value="<?php echo $settingsvalue->contact; ?>" id="contact" placeholder="contact...">
                                            </div>
                                        </div>                                    
                                        <div class="form-group clearfix">
                                            <label for="currency" class="col-md-3">Currency</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="currency" value="<?php echo $settingsvalue->currency; ?>" id="currency" placeholder="currency...">
                                            </div>
                                        </div>                                    
                                        <div class="form-group clearfix">
                                            <label for="currency" class="col-md-3">Symbol</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="symbol" value="<?php echo $settingsvalue->symbol; ?>" id="symbol" placeholder="symbol...">
                                            </div>
                                        </div>                                    
                                        <div class="form-group clearfix">
                                            <label for="email" class="col-md-3">System Email</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="email" id="email" value="<?php echo $settingsvalue->system_email; ?>" placeholder="email...">
                                            </div>
                                        </div>                                    
                                        <div class="form-group clearfix">
                                            <label for="address" class="col-md-3">Address</label>
                                            <div class="col-md-9">
                                                <input type="text" class="form-control" name="address" id="address" value="<?php echo $settingsvalue->address; ?>" placeholder="address...">
                                            </div>
                                        </div>                                    
                                        <hr>
                                        <h5 class="font-weight-bold text-info mb-3">
                                            <i class="fa fa-shield"></i> Kelompok Konfigurasi BPJS (Batas Maksimal / Cap)
                                        </h5>
                                        
                                        <div class="form-group clearfix">
                                            <label for="bpjs_jp_cap" class="col-md-3">BPJS Ketenagakerjaan - Limit Pensiun (JP Cap)</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="bpjs_jp_cap" id="bpjs_jp_cap" value="<?php echo isset($settingsvalue->bpjs_jp_cap) && !empty($settingsvalue->bpjs_jp_cap) ? $settingsvalue->bpjs_jp_cap : 10042300; ?>" placeholder="10042300" required>
                                                <small class="text-muted d-block mt-1">Batas maksimal gaji pengenaan iuran JP (Jaminan Pensiun 1% Karyawan + 2% Perusahaan). Disesuaikan tiap tahun.</small>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix">
                                            <label for="bpjs_kes_cap" class="col-md-3">BPJS Kesehatan - Limit Maksimal (Cap)</label>
                                            <div class="col-md-9">
                                                <input type="number" class="form-control" name="bpjs_kes_cap" id="bpjs_kes_cap" value="<?php echo isset($settingsvalue->bpjs_kes_cap) && !empty($settingsvalue->bpjs_kes_cap) ? $settingsvalue->bpjs_kes_cap : 12000000; ?>" placeholder="12000000" required>
                                                <small class="text-muted d-block mt-1">Batas maksimal gaji pengenaan iuran BPJS Kesehatan (1% Karyawan + 4% Perusahaan).</small>
                                            </div>
                                        </div>

                                        <div class="form-group clearfix mt-4">
                                            <div class="col-md-9 col-md-offset-3">
                                                <input type="hidden" name="id" value="<?php echo $settingsvalue->id; ?>"/>
                                                <button type="submit" name="submit" id="btnSubmit" class="btn btn-custom">Submit</button>
                                                <span class="flashmessage"><?php echo $this->session->flashdata('feedback'); ?></span>
                                            </div>
                                        </div>
                                        
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
    <?php $this->load->view('backend/footer'); ?>