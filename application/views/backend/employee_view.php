<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
                     <div class="page-wrapper">
                     <div class="message"></div>
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-user-secret" style="color:#9f0a0b"></i> <?php echo $basic->first_name .' '.$basic->last_name; ?></h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Profile</li>
                    </ol>
                </div>
            </div>
                <?php $degvalue = $this->employee_model->getdesignation(); ?>
                <?php $depvalue = $this->employee_model->getdepartment(); ?>
                <?php $divvalue = $this->employee_model->getdivision(); ?>
            <div class="container-fluid">
                <div class="row">
                    <div class="col-lg-12 col-xlg-12 col-md-12">
                        <div class="card">
                            <!-- Nav tabs -->
                            <ul class="nav nav-tabs profile-tab" role="tablist">
                                <li class="nav-item"> <a class="nav-link active" data-toggle="tab" href="#home" role="tab" style="font-size: 14px;"><i class="fa fa-user text-primary m-r-5"></i> Personal Info </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#profile" role="tab" style="font-size: 14px;"><i class="fa fa-map-marker text-danger m-r-5"></i> Address </a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#education" role="tab" style="font-size: 14px;"><i class="fa fa-graduation-cap text-success m-r-5"></i> Education</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#experience" role="tab" style="font-size: 14px;"><i class="fa fa-briefcase text-info m-r-5"></i> Experience</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bank" role="tab" style="font-size: 14px;"><i class="fa fa-university text-purple m-r-5"></i> Bank Account</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#document" role="tab" style="font-size: 14px;"><i class="fa fa-file-pdf-o text-danger m-r-5"></i> Document</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#salary" role="tab" style="font-size: 14px;"><i class="fa fa-money text-success m-r-5"></i> Salary</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#bpjs" role="tab" style="font-size: 14px;"><i class="fa fa-shield text-info m-r-5"></i> BPJS</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#pph" role="tab" style="font-size: 14px;"><i class="fa fa-percent text-warning m-r-5"></i> PPh 21</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#leave" role="tab" style="font-size: 14px;"><i class="fa fa-calendar-check-o text-primary m-r-5"></i> Leave</a> </li>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#social" role="tab" style="font-size: 14px;"><i class="fa fa-globe text-info m-r-5"></i> Social Media</a> </li>
                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password" role="tab" style="font-size: 14px;"><i class="fa fa-key text-dark m-r-5"></i> Change Password</a> </li>
                                <?php } else { ?>
                                <li class="nav-item"> <a class="nav-link" data-toggle="tab" href="#password1" role="tab" style="font-size: 14px;"><i class="fa fa-key text-dark m-r-5"></i> Change Password</a> </li>                                
                                <?php } ?>
                            </ul>
                            <!-- Tab panes -->

                            <div class="tab-content">
                                <div class="tab-pane active" id="home" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
                                            <div class="row">
                                                <div class="col-md-4">
                        <div class="card">
                            <div class="card-body">
                                <center class="m-t-30">
                                   <?php if(!empty($basic->em_image)){ ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basic->em_image; ?>" class="img-circle" width="150" />
                                    <?php } else { ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/user.png" class="img-circle" width="150" alt="<?php echo $basic->first_name ?>" title="<?php echo $basic->first_name ?>"/>                                   
                                    <?php } ?>
                                    <h4 class="card-title m-t-10"><?php echo $basic->first_name .' '.$basic->last_name; ?></h4>
                                    <h6 class="card-subtitle"><?php echo $basic->des_name; ?></h6>
                                </center>
                            </div>
                            <div>
                                <hr> </div>
                            <div class="card-body"> <small class="text-muted">Email address </small>
                                <h6><?php echo $basic->em_email; ?></h6> <small class="text-muted p-t-30 db">Phone</small>
                                <h6><?php echo $basic->em_phone; ?></h6> 
                                <small class="text-muted p-t-30 db">Social Profile</small>
                                <br/>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->facebook ?>" target="_blank"><i class="fa fa-facebook"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->twitter ?>" target="_blank"><i class="fa fa-twitter"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->skype_id ?>" target="_blank"><i class="fa fa-skype"></i></a>
                                <a class="btn btn-circle btn-secondary" href="<?php if(!empty($socialmedia->google_Plus)) echo $socialmedia->google_Plus ?>" target="_blank"><i class="fa fa-google"></i></a>
                            </div>
                        </div>                                                    
                                                </div>
                                                <div class="col-md-8">
				                                <form class="row" action="Update" method="post" enctype="multipart/form-data">
				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>No. Urut (No) </label>
				                                        <input type="number" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="No" name="emp_no" value="<?php echo isset($basic->emp_no) ? $basic->emp_no : ''; ?>"> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Employee PIN (NIK) </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="ID" name="eid" value="<?php echo $basic->em_code; ?>" required > 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>First Name</label>
				                                        <input type="text" class="form-control form-control-line" placeholder="Your first name" name="fname" value="<?php echo $basic->first_name; ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Last Name </label>
				                                        <input type="text" id="" name="lname" class="form-control form-control-line" value="<?php echo $basic->last_name; ?>" placeholder="Your last name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
				                                    </div>
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>Blood Group </label>
                                                        <select name="blood" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_blood_group; ?>" class="form-control custom-select">
                                                            <option value="<?php echo $basic->em_blood_group; ?>"><?php echo $basic->em_blood_group; ?></option>
                                                            <option value="O+">O+</option>
                                                            <option value="O-">O-</option>
                                                            <option value="A+">A+</option>
                                                            <option value="A-">A-</option>
                                                            <option value="B+">B+</option>
                                                            <option value="B-">B-</option>
                                                            <option value="AB+">AB+</option>
                                                        </select>
                                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Gender </label>
				                                        <select name="gender" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                           
				                                            <option value="<?php echo $basic->em_gender; ?>"><?php echo $basic->em_gender; ?></option>
				                                            <option value="Male">Male</option>
				                                            <option value="Female">Female</option>
				                                        </select>
				                                    </div>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>User Type </label>
                                                        <select name="role" class="form-control custom-select" required >
				                                            <option value="<?php echo $basic->em_role; ?>"><?php echo $basic->em_role; ?></option>
                                                            <option value="HR">HR</option>
                                                            <option value="EMPLOYEE">Employee</option>
                                                            <option value="ADMIN">Super Admin</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 
                                                    <div class="form-group col-md-4 m-t-10">
                                                        <label>Status </label>
                                                        <select name="status" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select" required >
				                                            <option value="<?php echo $basic->status; ?>"><?php echo $basic->status; ?></option>
                                                            <option value="ACTIVE">ACTIVE</option>
                                                            <option value="INACTIVE">INACTIVE</option>
                                                        </select>
                                                    </div>
                                                    <?php } ?>				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Date Of Birth </label>
				                                        <input type="date" id="example-email2" name="dob" class="form-control" placeholder="" value="<?php echo $basic->em_birthday; ?>" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Tempat Lahir </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" placeholder="Tempat Lahir" name="birth_place" value="<?php echo isset($basic->birth_place) ? $basic->birth_place : ''; ?>"> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>TTL Lengkap </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" placeholder="Tempat, Tanggal Lahir" name="ttl" value="<?php echo isset($basic->ttl) ? $basic->ttl : ''; ?>"> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>NID Number </label>
				                                        <input type="text" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control"placeholder="NID Number" name="nid" value="<?php echo $basic->em_nid; ?>" minlength="10" required> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Contact Number </label>
				                                        <input type="text" class="form-control" placeholder="" name="contact" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_phone; ?>" minlength="10" maxlength="15" required> 
				                                    </div>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Division</label>
				                                        <select name="division" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                            <option value="<?php echo isset($basic->division_id) ? $basic->division_id : ''; ?>"><?php echo !empty($basic->div_name) ? $basic->div_name . ' (' . $basic->div_code . ')' : 'Select Division'; ?></option>
                                             <?Php foreach($divvalue as $value): ?>
                                              <option value="<?php echo $value->id ?>"><?php echo $value->div_name ?> (<?php echo $value->div_code ?>)</option>
                                             <?php endforeach; ?>
				                                        </select>
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Department</label>
				                                        <select name="dept" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                            <option value="<?php echo $basic->id; ?>"><?php echo $basic->dep_name; ?></option>
                                             <?Php foreach($depvalue as $value): ?>
                                              <option value="<?php echo $value->id ?>"><?php echo $value->dep_name ?></option>
                                             <?php endforeach; ?>
				                                        </select>
				                                    </div>
				                                    <?php } ?>
                                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>  <?php } else { ?> 				                                    
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Designation </label>
				                                        <select name="deg" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control custom-select">
				                                            <option value="<?php echo $basic->id; ?>"><?php echo $basic->des_name; ?></option>
                                            <?Php foreach($degvalue as $value): ?>
                                            <option value="<?php echo $value->id ?>"><?php echo $value->des_name ?></option>
                                            <?php endforeach; ?>
				                                        </select>
				                                    </div>
				                                    <?php } ?>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Date Of Joining </label>
				                                        <input type="date" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> id="example-email2" name="joindate" class="form-control" value="<?php echo $basic->em_joining_date; ?>" placeholder=""> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Contract End Date</label>
				                                        <input type="date" id="example-email2" name="leavedate" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_contact_end; ?>" placeholder=""> 
				                                    </div>
				                                    <div class="form-group col-md-4 m-t-10">
				                                        <label>Email </label>
				                                        <input type="email" id="example-email2" name="email" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php echo $basic->em_email; ?>" placeholder="email@mail.com" minlength="7" required> 
				                                    </div>

                                                    <!-- Status Pernikahan, Tanggungan & PTKP Klasifikasi -->
                                                    <div class="col-md-12 m-t-10">
                                                        <h5 class="font-weight-bold text-info"><i class="fa fa-users m-r-5"></i>Status Pernikahan & Tanggungan (Klasifikasi PTKP PPh 21)</h5>
                                                        <hr class="m-t-5 m-b-15">
                                                    </div>

                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold">Status Pernikahan</label>
                                                        <select name="marital_status" id="marital_status_select" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?> class="form-control custom-select">
                                                            <?php $m_status = !empty($basic->em_marital_status) ? $basic->em_marital_status : 'Single'; ?>
                                                            <option value="Single" <?php echo ($m_status=='Single' || $m_status=='TK' || $m_status=='Tidak Kawin' || $m_status=='Belum Menikah') ? 'selected' : ''; ?>>Belum Menikah / Tidak Kawin (TK)</option>
                                                            <option value="Married" <?php echo ($m_status=='Married' || $m_status=='Kawin' || $m_status=='K' || $m_status=='Menikah') ? 'selected' : ''; ?>>Menikah / Kawin (K)</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold">Jumlah Tanggungan</label>
                                                        <select name="dependents" id="dependents_select" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?> class="form-control custom-select">
                                                            <?php $deps = isset($basic->em_dependents) ? intval($basic->em_dependents) : 0; ?>
                                                            <option value="0" <?php echo ($deps == 0) ? 'selected' : ''; ?>>0 Tanggungan</option>
                                                            <option value="1" <?php echo ($deps == 1) ? 'selected' : ''; ?>>1 Tanggungan</option>
                                                            <option value="2" <?php echo ($deps == 2) ? 'selected' : ''; ?>>2 Tanggungan</option>
                                                            <option value="3" <?php echo ($deps >= 3) ? 'selected' : ''; ?>>3 atau Lebih Tanggungan</option>
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold text-success">Golongan PTKP (Otomatis)</label>
                                                        <input type="text" id="ptkp_auto_display" class="form-control font-weight-bold bg-light text-success" readonly value="TK/0 - Tidak Kawin, 0 Tanggungan (Rp 54.000.000/thn)">
                                                    </div>
				                                    <div class="form-group col-md-12 m-t-10">
                                   <?php if(!empty($basic->em_image)){ ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/<?php echo $basic->em_image; ?>" class="img-circle" width="150" />
                                    <?php } else { ?>
                                    <img src="<?php echo base_url(); ?>assets/images/users/user.png" class="img-circle" width="150" alt="<?php echo $basic->first_name ?>" title="<?php echo $basic->first_name ?>"/>                                   
                                    <?php } ?>
                                                        <label>Image </label>
                                                        <input type="file" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="image_url" class="form-control" value=""> 
                                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                        <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
				                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
				                                        <button type="button" class="btn btn-info">Cancel</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                                </div>
                                        </div>
				                        </div>
                                    </div>
                                </div>
                                <!--second tab-->
                                <div class="tab-pane" id="profile" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
			                        		<h3 class="card-title">Permanent Contact Information</h3>
			                                <form class="row" action="Parmanent_Address" method="post" enctype="multipart/form-data">
			                                    <div class="form-group col-md-12 m-t-5">
			                                        <label>Address</label>
			                                        <textarea name="paraddress" value="<?php if(!empty($permanent->address)) echo $permanent->address  ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" rows="3" minlength="7" required><?php if(!empty($permanent->address)) echo $permanent->address  ?></textarea>
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>City</label>
			                                        <input type="text" name="parcity" class="form-control form-control-line" placeholder="" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php if(!empty($permanent->city)) echo $permanent->city ?>" minlength="2" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Country</label>
			                                        <input type="text" name="parcountry" class="form-control form-control-line" placeholder="" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> value="<?php if(!empty($permanent->country)) echo $permanent->country ?>" minlength="2" required> 
			                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>			                                    
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($permanent->id)) echo $permanent->id  ?>">                                                    
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>			                                    
			                                    </form>
			                                    
			                                    <div class="">
			                        				<h3 class="col-md-12">Present Contact Information</h3>
			                                    </div>
			                                    <hr>
			                                <form class="row" action="Present_Address" method="post" enctype="multipart/form-data">			                                    
			                                    <div class="form-group col-md-12 m-t-5">
			                                        <label>Address</label>
			                                        <textarea name="presaddress" value="<?php if(!empty($present->address)) echo $present->address  ?>" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" rows="3" minlength="7" required><?php if(!empty($present->address)) echo $present->address  ?></textarea>
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>City</label>
			                                        <input type="text" name="prescity" class="form-control form-control-line" value="<?php if(!empty($present->address)) echo $present->city  ?>" placeholder=" City name" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Country</label>
			                                        <input type="text" name="prescountry" class="form-control form-control-line" placeholder="" value="<?php if(!empty($present->address)) echo $present->country  ?>" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
                                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>			                                    
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($present->id)) echo $present->id  ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>
			                                </form>
				                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="education" role="tabpanel">
                                    <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Certificate name</th>
                                    <th>Institute </th>
                                    <th>Result </th>
                                    <th>year</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>Certificate name</th>
                                    <th>Institute </th>
                                    <th>Result </th>
                                    <th>year</th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($education as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->edu_type ?></td>
                                    <td><?php echo $value->institute ?></td>
                                    <td><?php echo $value->result ?></td>
                                    <td><?php echo $value->year ?></td>
                                   <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                    <td class="jsgrid-align-center ">
                                        <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light education" data-id="<?php echo $value->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                        <a onclick="confirm('Are you sure want to delet this Value?')" href="#" title="Delete" class="btn btn-sm btn-info waves-effect waves-light edudelet"  data-id="<?php echo $value->id ?>"><i class="fa fa-trash-o"></i></a>
                                    </td>
                                    <?php } ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                    
                                    </div>
                                    <div class="card">
                                      
	                                    <div class="card-body">
			                                <form class="row" action="Add_Education" method="post" enctype="multipart/form-data" id="insert_education">
			                                	<span id="error"></span>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Degree Name</label>
			                                        <input type="text" name="name" class="form-control form-control-line" placeholder=" Degree Name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="1" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Institute name</label>
			                                        <input type="text" name="institute" class="form-control form-control-line" placeholder=" Institute name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="3" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Result</label>
			                                        <input type="text" name="result" class="form-control form-control-line" placeholder=" Result" minlength="2" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Passing Year</label>
			                                        <input type="text" name="year" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="Passing Year"> 
			                                    </div>
			                                  <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
			                                    <div class="form-actions col-md-6">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                    <?php } ?>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="experience" role="tabpanel">
                                    <div class="card">
                <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>Company name</th>
                                    <th>Position </th>
                                    <th>Work Duration </th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>Company name</th>
                                    <th>Position </th>
                                    <th>Work Duration </th>
                                    <th>Action</th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($experience as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->exp_company ?></td>
                                    <td><?php echo $value->exp_com_position ?></td>
                                    <td><?php echo $value->exp_workduration ?></td>
                                    <td class="jsgrid-align-center ">
                                       <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                       <?php } else { ?>
                                        <a href="#" title="Edit" class="btn btn-sm btn-info waves-effect waves-light experience" data-id="<?php echo $value->id ?>"><i class="fa fa-pencil-square-o"></i></a>
                                        <a onclick="confirm('Are you sure want to delet this Value?')" href="#" title="Delete" class="btn btn-sm btn-info waves-effect waves-light deletexp" data-id="<?php echo $value->id ?>"><i class="fa fa-trash-o"></i></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                     
	                                    <div class="card-body">
			                                <form class="row" action="Add_Experience" method="post" enctype="multipart/form-data">
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label> Company Name</label>
			                                    	    <input type="text" name="company_name" class="form-control form-control-line company_name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Company Name" minlength="2" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Position</label>
			                                    	    <input type="text" name="position_name" class="form-control form-control-line position_name" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Position" minlength="3" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Address</label>
			                                    	    <input type="text" name="address" class="form-control form-control-line duty" placeholder=" Duty" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> minlength="7" required> 
			                                    	</div>
			                                    	<div class="form-group col-md-6 m-t-5">
			                                    	    <label>Working Duration</label>
			                                    	    <input type="text" name="work_duration" class="form-control form-control-line working_period" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> placeholder="Working Duration" required> 
			                                    	</div>
			                                 <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
		                                    	<div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                
		                                    	    <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
		                                    	</div>
		                                    	<?php } ?>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="bank" role="tabpanel">
                                    <div class="card">
	                                    <div class="card-body">
			                                <form class="row" action="Add_bank_info" method="post" enctype="multipart/form-data">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label> Bank Holder Name</label>
			                                        <input type="text" name="holder_name" value="<?php if(!empty($bankinfo->holder_name)) echo $bankinfo->holder_name  ?>" class="form-control form-control-line" placeholder="Bank Holder Name" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Name</label>
			                                        <input type="text" name="bank_name" value="<?php if(!empty($bankinfo->bank_name)) echo $bankinfo->bank_name  ?>" class="form-control form-control-line" placeholder="Bank Name" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Branch Name</label>
			                                        <input type="text" name="branch_name" value="<?php if(!empty($bankinfo->branch_name)) echo $bankinfo->branch_name  ?>" class="form-control form-control-line" placeholder=" Branch Name"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Account Number</label>
			                                        <input type="text" name="account_number" value="<?php if(!empty($bankinfo->account_number)) echo $bankinfo->account_number ?>" class="form-control form-control-line" minlength="5" required> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bank Account Type</label>
			                                        <input type="text" name="account_type" value="<?php if(!empty($bankinfo->account_type)) echo $bankinfo->account_type ?>" class="form-control form-control-line" placeholder="Bank Account Type"> 
			                                    </div>
			                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">
                                                    <input type="hidden" name="id" value="<?php if(!empty($bankinfo->id)) echo $bankinfo->id  ?>">
			                                        <button type="submit" class="btn btn-info"> <i class="fa fa-check"></i> Save</button>
			                                    </div>
			                                </form>
					                    </div>
                                    </div>
                                </div>
                                <div class="tab-pane" id="document" role="tabpanel">
                                    <div class="card-body">
                    <div class="table-responsive ">
                        <table id="example23" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <th>ID </th>
                                    <th>File Title</th>
                                    <th>File </th>
                                </tr>
                            </thead>
                            <tfoot>
                                <tr>
                                    <th>ID </th>
                                    <th>File Title</th>
                                    <th>File </th>
                                </tr>
                            </tfoot>
                            <tbody>
                               <?php foreach($fileinfo as $value): ?>
                                <tr>
                                    <td><?php echo $value->id ?></td>
                                    <td><?php echo $value->file_title ?></td>
                                    <td><a href="<?php echo base_url(); ?>assets/images/users/<?php echo $value->file_url ?>" target="_blank"><?php echo $value->file_url ?></a></td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>                                    
                                    <div class="card-body">
                                        <form class="row" action="Add_File" method="post" enctype="multipart/form-data">
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="">File Title</label>
                                                <input type="text" name="title" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" required="" aria-invalid="false" minlength="5" required>
                                            </div>
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="">File</label>
                                                <input type="file" name="file_url" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" required="" aria-invalid="false" required>
                                            </div>
                                            <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="em_id" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <button type="submit" class="btn btn-success">Add File</button>
                                                </div>
                                            </div>
                                            <?php } ?>
                                        </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="leave" role="tabpanel">
                                <div class="row">
                                    <div class="col-md-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <h4 class="card-title"> Leave</h4>
                                            <form action="Assign_leave" method="post" enctype="multipart/form-data">
                                                <div class="form-group">
                                                <label class="">Leave Type</label>                                 
                                                 <select name="typeid" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="select2 form-control custom-select" style="width: 100%" id="" required>
                                                  <option value="">Select Here...</option>
                                                   <?php foreach($leavetypes as $value): ?>
                                                    <option value="<?php echo $value->type_id ?>"><?php echo $value->name ?></option>
                                                    <?php endforeach; ?>
                                                </select>          
                                                </div>
			                                 <div class="form-group">
			                                    	<label>day</label>
			                                    	<input type="number" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="noday" class="form-control form-control-line noday" placeholder="Leave Day" required> 
			                                 </div>

                                                <div class="form-group">
                                                <label class="">Year</label>                                 <select name="year" class="select2 form-control custom-select" style="width: 100%" id="" required>
                                                 <option value="">Select Here...</option>
                                                  <?php 
                                                   for ($x = 2016; $x < 3000; $x++){
                                                    echo '<option value='.$x.'>'.$x.'</option>';            
                                                   }
                                                    ?>
                                                </select>          
                                                </div>
                                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>                 
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="em_id" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <button type="submit" class="btn btn-success">Submit</button>
                                                </div>
                                            </div>                                                                     <?php } ?>         
                                            </form>
			                            </div>
			                        </div>                                          
                                    </div>
                                    <div class="col-md-6">
			                        <div class="card">
			                            <div class="card-body">
			                                <h4 class="card-title"> Leave/<?php echo date('Y') ?></h4>
                                            <table class="display nowrap table table-hover table-striped table-bordered" width="50%">
                                                <thead>
                                                   <tr class="m-t-50">
                                                    <th>Type</th>
                                                    <th>Dayout/Day</th>       
                                                   </tr>
                                                </thead>
                                                <tbody>
                                                   <?php foreach($Leaveinfo as $value): ?>
                                                    <tr>
                                                        <td><?php echo $value->name; ?></td>
                                                        <td><?php echo $value->total_day; ?>/<?php echo $value->day; ?></td>
                                                    </tr>
                                                    <?php endforeach; ?>
                                                </tbody>
                                            </table>
			                            </div>
			                        </div>                                     
                                    </div>
                                  
                                </div>
                                </div>
                                <div class="tab-pane" id="password1" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Reset_Password_Hr" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Password</label>
				                                        <input type="text" class="form-control" name="new1" value="" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Confirm Password</label>
				                                        <input type="text" id="" name="new2" class="form-control " required minlength="6"> 
				                                    </div>
				                                    <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="social" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Save_Social" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Facebook</label>
				                                        <input type="url" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="facebook" value="<?php if(!empty($socialmedia->facebook)) echo $socialmedia->facebook ?>" placeholder="www.facebook.com"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Twitter</label>
				                                        <input type="text" class="form-control" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> name="twitter" value="<?php if(!empty($socialmedia->twitter)) echo $socialmedia->twitter ?>" > 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Google +</label>
				                                        <input type="text" id="" name="google" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control " value="<?php if(!empty($socialmedia->google_plus)) echo $socialmedia->google_plus ?>"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Skype</label>
				                                        <input type="text" id="" name="skype" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control " value="<?php if(!empty($socialmedia->skype_id)) echo $socialmedia->skype_id ?>"> 
				                                    </div>
				                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
                                                    <input type="hidden" name="id" value="<?php if(!empty($socialmedia->id)) echo $socialmedia->id ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                    <?php } ?>
				                                </form>
                                    </div>
                                </div>
                                <div class="tab-pane" id="password" role="tabpanel">
                                    <div class="card-body">
				                                <form class="row" action="Reset_Password" method="post" enctype="multipart/form-data">
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Old Password</label>
				                                        <input type="text" class="form-control" name="old" value="" placeholder="old password" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Password</label>
				                                        <input type="text" class="form-control" name="new1" value="" required minlength="6"> 
				                                    </div>
				                                    <div class="form-group col-md-6 m-t-20">
				                                        <label>Confirm Password</label>
				                                        <input type="text" id="" name="new2" class="form-control " required minlength="6"> 
				                                    </div>
				                                    <div class="form-actions col-md-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>">                                                   
				                                        <button type="submit" class="btn btn-info pull-right"> <i class="fa fa-check"></i> Save</button>
				                                    </div>
				                                </form>
                                    </div>
                                </div>

                                <div class="tab-pane" id="salary" role="tabpanel">
                                    <div class="card">
				                        <div class="card-body">
			                        		<h3 class="card-title">Basic Slary</h3>
			                                <form action="Add_Salary" method="post" enctype="multipart/form-data">
                                           <div class="row">
                                            <div class="form-group col-md-6 m-t-5">
                                                <label class="control-label">Salary Type</label>
                                                <select class="form-control <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> custom-select" data-placeholder="Choose a Category" tabindex="1" name="typeid" required>
                                                   <?php if(empty($salaryvalue->salary_type)){ ?>
                                                    <?php } else { ?>
                                                    <option value="<?php echo $salaryvalue->id; ?>"><?php echo $salaryvalue->salary_type; ?></option>                         <?php } ?>                                      
                                                   <?php foreach($typevalue as $value): ?>
                                                    <option value="<?php echo $value->id; ?>"><?php echo $value->salary_type; ?></option>
                                                    <?php endforeach; ?>
                                                </select>
                                            </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Total Salary</label>
			                                        <input type="text" name="total" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line total" placeholder="total salary" value="<?php if(!empty($salaryvalue->total)) echo $salaryvalue->total ?>" minlength="3" required> 
			                                    </div>
                                                </div>
                                                 
			                                    <h3 class="card-title">Addition</h3>
			                                    <div class="row">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Basic</label>
			                                        <input type="text" name="basic" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line basic" placeholder="Basic..." value="<?php if(!empty($salaryvalue->basic)) echo $salaryvalue->basic ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>House Rent</label>
			                                        <input type="text" name="houserent" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line houserent" placeholder="medical..." value="<?php if(!empty($salaryvalue->house_rent)) echo $salaryvalue->house_rent ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Medical</label>
			                                        <input type="text" name="medical" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line medical" placeholder="medical..." value="<?php if(!empty($salaryvalue->medical)) echo $salaryvalue->medical ?>" > 
			                                    </div> 
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Conveyance</label>
			                                        <input type="text" name="conveyance" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line conveyance" placeholder="conveyance..." value="<?php if(!empty($salaryvalue->conveyance)) echo $salaryvalue->conveyance ?>" > 
			                                    </div>
                                                </div>
                                                
			                                    <h3 class="card-title">Deduction</h3>
			                                    <div class="row">
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Bima</label>
			                                        <input type="text" name="bima" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="bima..." value="<?php if(!empty($salaryvalue->bima)) echo $salaryvalue->bima ?>"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Tax</label>
			                                        <input type="text" name="tax" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="tax..." value="<?php if(!empty($salaryvalue->tax)) echo $salaryvalue->tax ?>" > 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Provident Fund</label>
			                                        <input type="text" name="provident" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="Provident..." value="<?php if(!empty($salaryvalue->provident_fund)) echo $salaryvalue->provident_fund ?>"> 
			                                    </div>
			                                    <div class="form-group col-md-6 m-t-5">
			                                        <label>Others</label>
			                                        <input type="text" name="others" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control form-control-line" placeholder="others..." value="<?php if(!empty($salaryvalue->others)) echo $salaryvalue->others ?>"> 
			                                    </div>
                                                </div>
                                                <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?>
                                                    <?php } else { ?>
                                            <div class="form-group">
                                                <div class="col-sm-12">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>"> 
                                                    <?php if(!empty($salaryvalue->salary_id)){ ?>    
                                                    <input type="hidden" name="sid" value="<?php echo $salaryvalue->salary_id; ?>">                                               <?php } ?> 
                                                    <?php if(!empty($salaryvalue->addi_id)){ ?>    
                                                    <input type="hidden" name="aid" value="<?php echo $salaryvalue->addi_id; ?>">                                                  <?php } ?> 
                                                    <?php if(!empty($salaryvalue->de_id)){ ?>
                                                    <input type="hidden" name="did" value="<?php echo $salaryvalue->de_id; ?>">
                                                    <?php } ?>                                                   
                                                    <button <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?> type="submit" style="float: right" class="btn btn-success">Add Salary</button>
                                                </div>
                                                <?php } ?>
                                            </div>                                                		                                    
			                                    </form>
				                        </div>
                                    </div>
                                </div>

                                <!-- Tab BPJS -->
                                <div class="tab-pane" id="bpjs" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                            <h3 class="card-title"><i class="fa fa-id-card text-info m-r-10"></i>Informasi Keikutsertaan BPJS</h3>
                                            <form action="Save_Bpjs" method="post" enctype="multipart/form-data">
                                                <div class="row">
                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold">Nomor BPJS Kesehatan</label>
                                                        <input type="text" name="em_bpjs_kes" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" placeholder="Contoh: 0001234567890" value="<?php echo !empty($basic->em_bpjs_kes) ? $basic->em_bpjs_kes : '' ?>"> 
                                                    </div> 
                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold">Nomor BPJS Ketenagakerjaan (KPJ)</label>
                                                        <input type="text" name="em_bpjs_tk" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" placeholder="Contoh: 19012345678" value="<?php echo !empty($basic->em_bpjs_tk) ? $basic->em_bpjs_tk : '' ?>"> 
                                                    </div>
                                                    <div class="form-group col-md-4 m-t-5">
                                                        <label class="font-weight-bold">Status BPJS</label>
                                                        <select name="bpjs_status" class="form-control custom-select" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?>>
                                                            <option value="ACTIVE" <?php echo (!empty($basic->bpjs_status) && $basic->bpjs_status == 'ACTIVE') ? 'selected' : ''; ?>>Aktif</option>
                                                            <option value="INACTIVE" <?php echo (!empty($basic->bpjs_status) && $basic->bpjs_status == 'INACTIVE') ? 'selected' : ''; ?>>Tidak Aktif</option>
                                                        </select>
                                                    </div>
                                                </div>
                                                <?php if($this->session->userdata('user_type') != 'EMPLOYEE'){ ?>
                                                <div class="form-group m-b-20 text-right">
                                                    <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>"> 
                                                    <button type="submit" class="btn btn-info"><i class="fa fa-save m-r-5"></i>Simpan Data BPJS</button>
                                                </div>
                                                <?php } ?>
                                            </form>
                                            <hr>

                                            <h3 class="card-title m-t-20"><i class="fa fa-calculator text-success m-r-10"></i>Kalkulator & Simulasi Rumus Potongan BPJS</h3>
                                            <p class="text-muted">Simulasi perhitungan potongan gaji karyawan dan beban iuran perusahaan secara realtime.</p>
                                            
                                            <div class="row m-t-15">
                                                <div class="col-md-8">
                                                    <div class="form-group">
                                                        <label class="font-weight-bold">Gaji Pokok / Dasar Pengenaan BPJS (Rp)</label>
                                                        <input type="number" id="bpjs_calc_salary" class="form-control font-weight-bold" style="font-size: 15px;" value="<?php echo !empty($salaryvalue->total) ? $salaryvalue->total : (!empty($salaryvalue->basic) ? $salaryvalue->basic : 0); ?>" placeholder="Masukkan Gaji Pokok...">
                                                        <small class="form-text text-muted">Secara otomatis terisi dari Total Salary karyawan saat ini.</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4">
                                                    <div class="form-group">
                                                        <label>&nbsp;</label>
                                                        <button type="button" id="btn_recalc_bpjs" class="btn btn-success waves-effect waves-light btn-block"><i class="fa fa-refresh m-r-5"></i>Hitung Ulang</button>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Summary Cards BPJS -->
                                            <div class="row m-t-15">
                                                <div class="col-md-4 m-b-10">
                                                    <div class="p-3 text-center rounded" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                                        <h6 class="text-danger font-weight-bold m-b-5"><i class="fa fa-user m-r-5"></i>POTONGAN KARYAWAN</h6>
                                                        <h3 class="text-danger font-weight-bold m-0" id="card_total_potongan_karyawan">Rp 0</h3>
                                                        <small class="text-muted">Dipotong dari Gaji Karyawan</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 m-b-10">
                                                    <div class="p-3 text-center rounded" style="background-color: #ebf8ff; border: 1px solid #90cdf4;">
                                                        <h6 class="text-info font-weight-bold m-b-5"><i class="fa fa-building m-r-5"></i>BEBAN PERUSAHAAN</h6>
                                                        <h3 class="text-info font-weight-bold m-0" id="card_total_beban_perusahaan">Rp 0</h3>
                                                        <small class="text-muted">Ditanggung Perusahaan</small>
                                                    </div>
                                                </div>
                                                <div class="col-md-4 m-b-10">
                                                    <div class="p-3 text-center rounded" style="background-color: #f0fff4; border: 1px solid #9ae6b4;">
                                                        <h6 class="text-success font-weight-bold m-b-5"><i class="fa fa-shield m-r-5"></i>TOTAL IURAN BPJS</h6>
                                                        <h3 class="text-success font-weight-bold m-0" id="card_total_iuran_keseluruhan">Rp 0</h3>
                                                        <small class="text-muted">Total Bayar ke BPJS</small>
                                                    </div>
                                                </div>
                                            </div>

                                            <!-- Detailed Calculation Table -->
                                            <div class="table-responsive m-t-20">
                                                <table class="table table-bordered table-striped text-center">
                                                    <thead class="bg-primary text-white">
                                                        <tr>
                                                            <th class="text-left">Program BPJS</th>
                                                            <th>Tarif Karyawan (%)</th>
                                                            <th class="text-danger">Potongan Karyawan (Rp)</th>
                                                            <th>Tarif Perusahaan (%)</th>
                                                            <th class="text-info">Beban Perusahaan (Rp)</th>
                                                            <th class="bg-success text-white">Total Iuran (Rp)</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        <tr>
                                                            <td class="text-left font-weight-bold">
                                                                BPJS Kesehatan
                                                                <br><small class="text-muted">Batas Maksimal Gaji Rp 12.000.000</small>
                                                            </td>
                                                            <td>1,00 %</td>
                                                            <td class="text-danger font-weight-bold" id="tbl_kes_karyawan">Rp 0</td>
                                                            <td>4,00 %</td>
                                                            <td class="text-info font-weight-bold" id="tbl_kes_perusahaan">Rp 0</td>
                                                            <td class="font-weight-bold" id="tbl_kes_total">Rp 0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left font-weight-bold">
                                                                BPJS TK - JHT (Jaminan Hari Tua)
                                                                <br><small class="text-muted">Pengenaan dari Gaji Pokok</small>
                                                            </td>
                                                            <td>2,00 %</td>
                                                            <td class="text-danger font-weight-bold" id="tbl_jht_karyawan">Rp 0</td>
                                                            <td>3,70 %</td>
                                                            <td class="text-info font-weight-bold" id="tbl_jht_perusahaan">Rp 0</td>
                                                            <td class="font-weight-bold" id="tbl_jht_total">Rp 0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left font-weight-bold">
                                                                BPJS TK - JP (Jaminan Pensiun)
                                                                <br><small class="text-muted">Batas Maksimal Gaji Rp 10.042.300</small>
                                                            </td>
                                                            <td>1,00 %</td>
                                                            <td class="text-danger font-weight-bold" id="tbl_jp_karyawan">Rp 0</td>
                                                            <td>2,00 %</td>
                                                            <td class="text-info font-weight-bold" id="tbl_jp_perusahaan">Rp 0</td>
                                                            <td class="font-weight-bold" id="tbl_jp_total">Rp 0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left font-weight-bold">
                                                                BPJS TK - JKK (Jaminan Kecelakaan Kerja)
                                                                <br><small class="text-muted">Tingkat Risiko Standar (0.24%)</small>
                                                            </td>
                                                            <td>0,00 %</td>
                                                            <td class="text-danger font-weight-bold" id="tbl_jkk_karyawan">Rp 0</td>
                                                            <td>0,24 %</td>
                                                            <td class="text-info font-weight-bold" id="tbl_jkk_perusahaan">Rp 0</td>
                                                            <td class="font-weight-bold" id="tbl_jkk_total">Rp 0</td>
                                                        </tr>
                                                        <tr>
                                                            <td class="text-left font-weight-bold">
                                                                BPJS TK - JKM (Jaminan Kematian)
                                                                <br><small class="text-muted">Ditanggung Perusahaan (0.30%)</small>
                                                            </td>
                                                            <td>0,00 %</td>
                                                            <td class="text-danger font-weight-bold" id="tbl_jkm_karyawan">Rp 0</td>
                                                            <td>0,30 %</td>
                                                            <td class="text-info font-weight-bold" id="tbl_jkm_perusahaan">Rp 0</td>
                                                            <td class="font-weight-bold" id="tbl_jkm_total">Rp 0</td>
                                                        </tr>
                                                    </tbody>
                                                    <tfoot class="bg-light font-weight-bold">
                                                        <tr style="font-size: 15px;">
                                                            <td class="text-left">TOTAL ALL BPJS</td>
                                                            <td>4,00 %</td>
                                                            <td class="text-danger" id="tbl_total_karyawan">Rp 0</td>
                                                            <td>10,24 %</td>
                                                            <td class="text-info" id="tbl_total_perusahaan">Rp 0</td>
                                                            <td class="text-success" id="tbl_total_semua">Rp 0</td>
                                                        </tr>
                                                    </tfoot>
                                                </table>
                                            </div>

                                            <!-- Rules Card -->
                                            <div class="alert alert-info m-t-20 b-l border-info">
                                                <h5 class="text-info font-weight-bold"><i class="fa fa-info-circle m-r-5"></i>Catatan Regulasi Perhitungan BPJS (Indonesia)</h5>
                                                <ul class="m-b-0 p-l-20" style="font-size: 13px;">
                                                    <li><b>BPJS Kesehatan:</b> Total iuran adalah 5% (1% Karyawan + 4% Perusahaan). Dasar pengenaan maksimal adalah <b>Rp 12.000.000</b>. Potongan maksimal karyawan = Rp 120.000.</li>
                                                    <li><b>BPJS TK JHT:</b> Total iuran adalah 5.7% (2% Karyawan + 3.7% Perusahaan) dari Gaji Pokok.</li>
                                                    <li><b>BPJS TK JP:</b> Total iuran adalah 3% (1% Karyawan + 2% Perusahaan). Dasar pengenaan maksimal adalah <b>Rp 10.042.300</b>. Potongan maksimal karyawan = Rp 100.423.</li>
                                                    <li><b>BPJS TK JKK & JKM:</b> Seluruhnya ditanggung oleh perusahaan (JKK 0.24%, JKM 0.30%) dan tidak memotong gaji karyawan.</li>
                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                $(document).ready(function() {
                                    function formatRupiah(number) {
                                        return 'Rp ' + Math.round(number).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                    }

                                    window.calculateBPJS = function calculateBPJS() {
                                        var salary = parseFloat($('#bpjs_calc_salary').val()) || 0;
                                        
                                        // BPJS Kesehatan (Cap Rp 12.000.000)
                                        var base_kes = Math.min(salary, 12000000);
                                        var kes_karyawan = Math.round(base_kes * 0.01);
                                        var kes_perusahaan = Math.round(base_kes * 0.04);
                                        var kes_total = kes_karyawan + kes_perusahaan;

                                        // BPJS TK - JHT (2% & 3.7%)
                                        var jht_karyawan = Math.round(salary * 0.02);
                                        var jht_perusahaan = Math.round(salary * 0.037);
                                        var jht_total = jht_karyawan + jht_perusahaan;

                                        // BPJS TK - JP (Cap Rp 10.042.300 - 1% & 2%)
                                        var base_jp = Math.min(salary, 10042300);
                                        var jp_karyawan = Math.round(base_jp * 0.01);
                                        var jp_perusahaan = Math.round(base_jp * 0.02);
                                        var jp_total = jp_karyawan + jp_perusahaan;

                                        // BPJS TK - JKK (0.24% Perusahaan)
                                        var jkk_karyawan = 0;
                                        var jkk_perusahaan = Math.round(salary * 0.0024);
                                        var jkk_total = jkk_perusahaan;

                                        // BPJS TK - JKM (0.30% Perusahaan)
                                        var jkm_karyawan = 0;
                                        var jkm_perusahaan = Math.round(salary * 0.0030);
                                        var jkm_total = jkm_perusahaan;

                                        // Totals
                                        var total_karyawan = kes_karyawan + jht_karyawan + jp_karyawan;
                                        var total_perusahaan = kes_perusahaan + jht_perusahaan + jp_perusahaan + jkk_perusahaan + jkm_perusahaan;
                                        var total_keseluruhan = total_karyawan + total_perusahaan;

                                        // Update Cards
                                        $('#card_total_potongan_karyawan').text(formatRupiah(total_karyawan));
                                        $('#card_total_beban_perusahaan').text(formatRupiah(total_perusahaan));
                                        $('#card_total_iuran_keseluruhan').text(formatRupiah(total_keseluruhan));

                                        // Update Table Cells
                                        $('#tbl_kes_karyawan').text(formatRupiah(kes_karyawan));
                                        $('#tbl_kes_perusahaan').text(formatRupiah(kes_perusahaan));
                                        $('#tbl_kes_total').text(formatRupiah(kes_total));

                                        $('#tbl_jht_karyawan').text(formatRupiah(jht_karyawan));
                                        $('#tbl_jht_perusahaan').text(formatRupiah(jht_perusahaan));
                                        $('#tbl_jht_total').text(formatRupiah(jht_total));

                                        $('#tbl_jp_karyawan').text(formatRupiah(jp_karyawan));
                                        $('#tbl_jp_perusahaan').text(formatRupiah(jp_perusahaan));
                                        $('#tbl_jp_total').text(formatRupiah(jp_total));

                                        $('#tbl_jkk_karyawan').text(formatRupiah(jkk_karyawan));
                                        $('#tbl_jkk_perusahaan').text(formatRupiah(jkk_perusahaan));
                                        $('#tbl_jkk_total').text(formatRupiah(jkk_total));

                                        $('#tbl_jkm_karyawan').text(formatRupiah(jkm_karyawan));
                                        $('#tbl_jkm_perusahaan').text(formatRupiah(jkm_perusahaan));
                                        $('#tbl_jkm_total').text(formatRupiah(jkm_total));

                                        $('#tbl_total_karyawan').text(formatRupiah(total_karyawan));
                                        $('#tbl_total_perusahaan').text(formatRupiah(total_perusahaan));
                                        $('#tbl_total_semua').text(formatRupiah(total_keseluruhan));
                                    }

                                    // Trigger calculation on page load and input change
                                    calculateBPJS();
                                    $('#bpjs_calc_salary').on('input keyup change', function() {
                                        calculateBPJS();
                                    });
                                    $('#btn_recalc_bpjs').click(function() {
                                        calculateBPJS();
                                    });
                                    $('a[href="#bpjs"]').on('shown.bs.tab', function (e) {
                                        calculateBPJS();
                                    });
                                });
                                </script>

                                <!-- Tab PPh 21 -->
                                <div class="tab-pane" id="pph" role="tabpanel">
                                    <div class="card">
                                        <div class="card-body">
                                        <h3 class="card-title"><i class="fa fa-file-text-o text-warning m-r-10"></i>Informasi Perpajakan (PPh 21 & NPWP)</h3>
                                        <form action="Save_Pph" method="post" enctype="multipart/form-data">
                                            <div class="row">
                                                <div class="form-group col-md-6 m-t-5">
                                                    <label class="font-weight-bold">Nomor NPWP Karyawan</label>
                                                    <input type="text" name="em_npwp" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> readonly <?php } ?> class="form-control" placeholder="Contoh: 12.345.678.9-012.000" value="<?php echo !empty($basic->em_npwp) ? $basic->em_npwp : '' ?>"> 
                                                    <small class="form-text text-muted">Nomor Pokok Wajib Pajak (15 digit)</small>
                                                </div> 
                                                <div class="form-group col-md-6 m-t-5">
                                                    <label class="font-weight-bold">Status PTKP (Penghasilan Tidak Kena Pajak)</label>
                                                    <select name="em_ptkp" id="em_ptkp_select" class="form-control custom-select" <?php if($this->session->userdata('user_type')=='EMPLOYEE'){ ?> disabled <?php } ?>>
                                                        <?php 
                                                        $current_ptkp = !empty($basic->em_ptkp) ? $basic->em_ptkp : 'TK0';
                                                        $ptkp_options = array(
                                                            'TK0' => 'TK/0 - Tidak Kawin, 0 Tanggungan (Rp 54.000.000/thn)',
                                                            'TK1' => 'TK/1 - Tidak Kawin, 1 Tanggungan (Rp 58.500.000/thn)',
                                                            'TK2' => 'TK/2 - Tidak Kawin, 2 Tanggungan (Rp 63.000.000/thn)',
                                                            'TK3' => 'TK/3 - Tidak Kawin, 3 Tanggungan (Rp 67.500.000/thn)',
                                                            'K0'  => 'K/0 - Kawin, 0 Tanggungan (Rp 58.500.000/thn)',
                                                            'K1'  => 'K/1 - Kawin, 1 Tanggungan (Rp 63.000.000/thn)',
                                                            'K2'  => 'K/2 - Kawin, 2 Tanggungan (Rp 67.500.000/thn)',
                                                            'K3'  => 'K/3 - Kawin, 3 Tanggungan (Rp 72.000.000/thn)'
                                                        );
                                                        foreach($ptkp_options as $key => $label) {
                                                            $selected = ($current_ptkp == $key) ? 'selected' : '';
                                                            echo "<option value='{$key}' {$selected}>{$label}</option>";
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
                                            <?php if($this->session->userdata('user_type') != 'EMPLOYEE'){ ?>
                                            <div class="form-group m-b-20 text-right">
                                                <input type="hidden" name="emid" value="<?php echo $basic->em_id; ?>"> 
                                                <button type="submit" class="btn btn-warning text-white"><i class="fa fa-save m-r-5"></i>Simpan Data PPh 21</button>
                                            </div>
                                            <?php } ?>
                                        </form>
                                        <hr>

                                        <h3 class="card-title m-t-20"><i class="fa fa-calculator text-primary m-r-10"></i>Kalkulator & Simulasi Rumus PPh 21 (UU HPP No. 7/2021)</h3>
                                        <p class="text-muted">Simulasi perhitungan pajak penghasilan karyawan sebulan dan setahun berdasarkan tarif progresif Pasal 17 UU HPP.</p>

                                        <div class="row m-t-15">
                                            <div class="col-md-5">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Gaji Bruto Bulanan (Rp)</label>
                                                    <input type="number" id="pph_calc_salary" class="form-control font-weight-bold" style="font-size: 15px;" value="<?php echo !empty($salaryvalue->total) ? $salaryvalue->total : (!empty($salaryvalue->basic) ? $salaryvalue->basic : 0); ?>" placeholder="Masukkan Gaji Bruto...">
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="form-group">
                                                    <label class="font-weight-bold">Status PTKP Simulasi</label>
                                                    <select id="pph_calc_ptkp" class="form-control custom-select">
                                                        <option value="TK0" <?php echo ($current_ptkp=='TK0')?'selected':''; ?>>TK/0 (Rp 54.000.000)</option>
                                                        <option value="TK1" <?php echo ($current_ptkp=='TK1')?'selected':''; ?>>TK/1 (Rp 58.500.000)</option>
                                                        <option value="TK2" <?php echo ($current_ptkp=='TK2')?'selected':''; ?>>TK/2 (Rp 63.000.000)</option>
                                                        <option value="TK3" <?php echo ($current_ptkp=='TK3')?'selected':''; ?>>TK/3 (Rp 67.500.000)</option>
                                                        <option value="K0"  <?php echo ($current_ptkp=='K0')?'selected':''; ?>>K/0 (Rp 58.500.000)</option>
                                                        <option value="K1"  <?php echo ($current_ptkp=='K1')?'selected':''; ?>>K/1 (Rp 63.000.000)</option>
                                                        <option value="K2"  <?php echo ($current_ptkp=='K2')?'selected':''; ?>>K/2 (Rp 67.500.000)</option>
                                                        <option value="K3"  <?php echo ($current_ptkp=='K3')?'selected':''; ?>>K/3 (Rp 72.000.000)</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label>&nbsp;</label>
                                                    <button type="button" id="btn_recalc_pph" class="btn btn-warning text-white waves-effect waves-light btn-block"><i class="fa fa-refresh m-r-5"></i>Hitung Ulang</button>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Summary Cards PPh -->
                                        <div class="row m-t-15">
                                            <div class="col-md-4 m-b-10">
                                                <div class="p-3 text-center rounded" style="background-color: #fff9db; border: 1px solid #ffe066;">
                                                    <h6 class="text-warning font-weight-bold m-b-5"><i class="fa fa-calendar m-r-5"></i>POTONGAN PPh 21 SEBULAN</h6>
                                                    <h3 class="text-warning font-weight-bold m-0" id="card_pph_sebulan">Rp 0</h3>
                                                    <small class="text-muted">Potongan Pajak per Bulan</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 m-b-10">
                                                <div class="p-3 text-center rounded" style="background-color: #fff5f5; border: 1px solid #feb2b2;">
                                                    <h6 class="text-danger font-weight-bold m-b-5"><i class="fa fa-balance-scale m-r-5"></i>PPh 21 TERUTANG SETAHUN</h6>
                                                    <h3 class="text-danger font-weight-bold m-0" id="card_pph_setahun">Rp 0</h3>
                                                    <small class="text-muted">Total Pajak per Tahun</small>
                                                </div>
                                            </div>
                                            <div class="col-md-4 m-b-10">
                                                <div class="p-3 text-center rounded" style="background-color: #ebf8ff; border: 1px solid #90cdf4;">
                                                    <h6 class="text-info font-weight-bold m-b-5"><i class="fa fa-money m-r-5"></i>PKP SETAHUN</h6>
                                                    <h3 class="text-info font-weight-bold m-0" id="card_pkp_setahun">Rp 0</h3>
                                                    <small class="text-muted">Penghasilan Kena Pajak</small>
                                                </div>
                                            </div>
                                        </div>

                                        <!-- Detailed Calculation Table -->
                                        <div class="table-responsive m-t-20">
                                            <table class="table table-bordered table-striped">
                                                <thead class="bg-warning text-white text-center">
                                                    <tr>
                                                        <th class="text-left">Komponen Perhitungan PPh 21</th>
                                                        <th>Per Bulan (Rp)</th>
                                                        <th>Per Tahun (Rp)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="font-weight-bold">Penghasilan Bruto</td>
                                                        <td class="text-right" id="pph_bruto_bln">Rp 0</td>
                                                        <td class="text-right font-weight-bold" id="pph_bruto_thn">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pengurang: Biaya Jabatan (5%, Maks Rp 500.000/bln)</td>
                                                        <td class="text-right text-danger" id="pph_bj_bln">- Rp 0</td>
                                                        <td class="text-right text-danger" id="pph_bj_thn">- Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pengurang: BPJS TK Karyawan (JHT 2% + JP 1%)</td>
                                                        <td class="text-right text-danger" id="pph_bpjs_bln">- Rp 0</td>
                                                        <td class="text-right text-danger" id="pph_bpjs_thn">- Rp 0</td>
                                                    </tr>
                                                    <tr class="bg-light font-weight-bold">
                                                        <td>Penghasilan Neto</td>
                                                        <td class="text-right text-primary" id="pph_neto_bln">Rp 0</td>
                                                        <td class="text-right text-primary" id="pph_neto_thn">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Penghasilan Tidak Kena Pajak (PTKP)</td>
                                                        <td class="text-right text-muted">-</td>
                                                        <td class="text-right text-danger font-weight-bold" id="pph_ptkp_val">- Rp 0</td>
                                                    </tr>
                                                    <tr class="bg-warning text-white font-weight-bold">
                                                        <td>Penghasilan Kena Pajak (PKP)</td>
                                                        <td class="text-right">-</td>
                                                        <td class="text-right" id="pph_pkp_val">Rp 0</td>
                                                    </tr>
                                                </tbody>
                                            </table>
                                        </div>

                                        <h4 class="card-title m-t-20"><i class="fa fa-list-ol text-danger m-r-10"></i>Breakdown Lapisan Tarif Progresif (Pasal 17 UU HPP)</h4>
                                        <div class="table-responsive">
                                            <table class="table table-bordered text-center">
                                                <thead class="bg-dark text-white">
                                                    <tr>
                                                        <th class="text-left">Lapisan Tarif Kena Pajak</th>
                                                        <th>Tarif (%)</th>
                                                        <th>Dasar Kena Pajak (PKP di Lapisan ini)</th>
                                                        <th>Pajak Terutang (Rp)</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <tr>
                                                        <td class="text-left">Lapisan I (s/d Rp 60.000.000)</td>
                                                        <td>5 %</td>
                                                        <td id="lap1_pkp">Rp 0</td>
                                                        <td class="text-danger font-weight-bold" id="lap1_tax">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Lapisan II (> Rp 60.000.000 - Rp 250.000.000)</td>
                                                        <td>15 %</td>
                                                        <td id="lap2_pkp">Rp 0</td>
                                                        <td class="text-danger font-weight-bold" id="lap2_tax">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Lapisan III (> Rp 250.000.000 - Rp 500.000.000)</td>
                                                        <td>25 %</td>
                                                        <td id="lap3_pkp">Rp 0</td>
                                                        <td class="text-danger font-weight-bold" id="lap3_tax">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Lapisan IV (> Rp 500.000.000 - Rp 5.000.000.000)</td>
                                                        <td>30 %</td>
                                                        <td id="lap4_pkp">Rp 0</td>
                                                        <td class="text-danger font-weight-bold" id="lap4_tax">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="text-left">Lapisan V (> Rp 5.000.000.000)</td>
                                                        <td>35 %</td>
                                                        <td id="lap5_pkp">Rp 0</td>
                                                        <td class="text-danger font-weight-bold" id="lap5_tax">Rp 0</td>
                                                    </tr>
                                                </tbody>
                                                <tfoot class="bg-light font-weight-bold">
                                                    <tr>
                                                        <td colspan="3" class="text-right">TOTAL PPh 21 TERUTANG (SETAHUN):</td>
                                                        <td class="text-danger" style="font-size: 16px;" id="lap_total_tax">Rp 0</td>
                                                    </tr>
                                                    <tr>
                                                        <td colspan="3" class="text-right text-warning">TOTAL POTONGAN PPh 21 PER BULAN:</td>
                                                        <td class="text-warning" style="font-size: 16px;" id="lap_monthly_tax">Rp 0</td>
                                                    </tr>
                                                </tfoot>
                                            </table>
                                        </div>
                                    </div>
                                </div>

                                <script type="text/javascript">
                                $(document).ready(function() {
                                    var ptkp_values = {
                                        'TK0': 54000000,
                                        'TK1': 58500000,
                                        'TK2': 63000000,
                                        'TK3': 67500000,
                                        'K0':  58500000,
                                        'K1':  63000000,
                                        'K2':  67500000,
                                        'K3':  72000000
                                    };

                                    var ter_db = <?php echo json_encode($this->db->get('ter_pph21')->result_array()); ?>;

                                    function formatRupiahPph(num) {
                                        return 'Rp ' + Math.round(num).toString().replace(/\B(?=(\d{3})+(?!\d))/g, ".");
                                    }

                                    window.calculatePPh21 = function calculatePPh21() {
                                        var bruto_bln = parseFloat($('#pph_calc_salary').val()) || 0;
                                        var ptkp_key = $('#pph_calc_ptkp').val() || 'TK0';
                                        var ptkp_val = ptkp_values[ptkp_key] || 54000000;

                                        // TER Category (PP 58/2023)
                                        var ter_cat = 'A';
                                        if (['TK2', 'TK3', 'K1', 'K2'].indexOf(ptkp_key) !== -1) {
                                            ter_cat = 'B';
                                        } else if (ptkp_key === 'K3') {
                                            ter_cat = 'C';
                                        }

                                        // Lookup TER Rate from Database
                                        var ter_rate = 0;
                                        if (ter_db && ter_db.length > 0) {
                                            for (var i = 0; i < ter_db.length; i++) {
                                                if (ter_db[i].category === ter_cat) {
                                                    var min_g = parseFloat(ter_db[i].min_gross);
                                                    var max_g = parseFloat(ter_db[i].max_gross);
                                                    if (bruto_bln >= min_g && bruto_bln <= max_g) {
                                                        ter_rate = parseFloat(ter_db[i].rate);
                                                        break;
                                                    }
                                                }
                                            }
                                        }
                                        var ter_tax_bln = Math.round(bruto_bln * ter_rate);

                                        // Biaya Jabatan (5% max 500rb/bulan)
                                        var bj_bln = Math.min(Math.round(bruto_bln * 0.05), 500000);
                                        var bj_thn = bj_bln * 12;

                                        // BPJS TK Karyawan Pengurang (JHT 2% + JP 1% max 10.042.300)
                                        var jht_bln = Math.round(bruto_bln * 0.02);
                                        var base_jp = Math.min(bruto_bln, 10042300);
                                        var jp_bln  = Math.round(base_jp * 0.01);
                                        var bpjs_ded_bln = jht_bln + jp_bln;
                                        var bpjs_ded_thn = bpjs_ded_bln * 12;

                                        // Neto
                                        var bruto_thn = bruto_bln * 12;
                                        var neto_bln  = Math.max(0, bruto_bln - bj_bln - bpjs_ded_bln);
                                        var neto_thn  = neto_bln * 12;

                                        // PKP
                                        var pkp_thn = Math.max(0, neto_thn - ptkp_val);

                                        // Progressive tax layer calculation
                                        var temp_pkp = pkp_thn;
                                        var tax_thn = 0;

                                        // Lap 1: 0 - 60jt (5%)
                                        var lap1_pkp = Math.min(temp_pkp, 60000000);
                                        var lap1_tax = Math.round(lap1_pkp * 0.05);
                                        temp_pkp = Math.max(0, temp_pkp - lap1_pkp);

                                        // Lap 2: 60jt - 250jt (15%)
                                        var lap2_pkp = Math.min(temp_pkp, 190000000);
                                        var lap2_tax = Math.round(lap2_pkp * 0.15);
                                        temp_pkp = Math.max(0, temp_pkp - lap2_pkp);

                                        // Lap 3: 250jt - 500jt (25%)
                                        var lap3_pkp = Math.min(temp_pkp, 250000000);
                                        var lap3_tax = Math.round(lap3_pkp * 0.25);
                                        temp_pkp = Math.max(0, temp_pkp - lap3_pkp);

                                        // Lap 4: 500jt - 5 M (30%)
                                        var lap4_pkp = Math.min(temp_pkp, 4500000000);
                                        var lap4_tax = Math.round(lap4_pkp * 0.30);
                                        temp_pkp = Math.max(0, temp_pkp - lap4_pkp);

                                        // Lap 5: > 5 M (35%)
                                        var lap5_pkp = temp_pkp;
                                        var lap5_tax = Math.round(lap5_pkp * 0.35);

                                        tax_thn = lap1_tax + lap2_tax + lap3_tax + lap4_tax + lap5_tax;
                                        var tax_bln = Math.round(tax_thn / 12);

                                        // Update UI elements
                                        var rate_pct = (ter_rate * 100).toFixed(2) + '%';
                                        $('#card_pph_sebulan').html(formatRupiahPph(ter_tax_bln) + '<br><small style="font-size:12px; font-weight:normal;" class="text-dark">TER ' + ter_cat + ' (' + rate_pct + ')</small>');
                                        $('#card_pph_setahun').text(formatRupiahPph(tax_thn));
                                        $('#card_pkp_setahun').text(formatRupiahPph(pkp_thn));

                                        $('#pph_bruto_bln').text(formatRupiahPph(bruto_bln));
                                        $('#pph_bruto_thn').text(formatRupiahPph(bruto_thn));

                                        $('#pph_bj_bln').text('- ' + formatRupiahPph(bj_bln));
                                        $('#pph_bj_thn').text('- ' + formatRupiahPph(bj_thn));

                                        $('#pph_bpjs_bln').text('- ' + formatRupiahPph(bpjs_ded_bln));
                                        $('#pph_bpjs_thn').text('- ' + formatRupiahPph(bpjs_ded_thn));

                                        $('#pph_neto_bln').text(formatRupiahPph(neto_bln));
                                        $('#pph_neto_thn').text(formatRupiahPph(neto_thn));

                                        $('#pph_ptkp_val').text('- ' + formatRupiahPph(ptkp_val));
                                        $('#pph_pkp_val').text(formatRupiahPph(pkp_thn));

                                        // Update Layer Table
                                        $('#lap1_pkp').text(formatRupiahPph(lap1_pkp));
                                        $('#lap1_tax').text(formatRupiahPph(lap1_tax));

                                        $('#lap2_pkp').text(formatRupiahPph(lap2_pkp));
                                        $('#lap2_tax').text(formatRupiahPph(lap2_tax));

                                        $('#lap3_pkp').text(formatRupiahPph(lap3_pkp));
                                        $('#lap3_tax').text(formatRupiahPph(lap3_tax));

                                        $('#lap4_pkp').text(formatRupiahPph(lap4_pkp));
                                        $('#lap4_tax').text(formatRupiahPph(lap4_tax));

                                        $('#lap5_pkp').text(formatRupiahPph(lap5_pkp));
                                        $('#lap5_tax').text(formatRupiahPph(lap5_tax));

                                        $('#lap_total_tax').text(formatRupiahPph(tax_thn));
                                        $('#lap_monthly_tax').text(formatRupiahPph(tax_bln));
                                    }

                                    calculatePPh21();

                                    $('#pph_calc_salary, #pph_calc_ptkp').on('input keyup change', function() {
                                        calculatePPh21();
                                    });

                                    $('#btn_recalc_pph').click(function() {
                                        calculatePPh21();
                                    });

                                    $('a[href="#pph"]').on('shown.bs.tab', function (e) {
                                        calculatePPh21();
                                    });
                                });
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Column -->
        </div>

        <script type="text/javascript">
        $(document).ready(function() {
            var ptkp_labels = {
                'TK0': 'TK/0 - Tidak Kawin, 0 Tanggungan (Rp 54.000.000/thn)',
                'TK1': 'TK/1 - Tidak Kawin, 1 Tanggungan (Rp 58.500.000/thn)',
                'TK2': 'TK/2 - Tidak Kawin, 2 Tanggungan (Rp 63.000.000/thn)',
                'TK3': 'TK/3 - Tidak Kawin, 3 Tanggungan (Rp 67.500.000/thn)',
                'K0' : 'K/0 - Kawin, 0 Tanggungan (Rp 58.500.000/thn)',
                'K1' : 'K/1 - Kawin, 1 Tanggungan (Rp 63.000.000/thn)',
                'K2' : 'K/2 - Kawin, 2 Tanggungan (Rp 67.500.000/thn)',
                'K3' : 'K/3 - Kawin, 3 Tanggungan (Rp 72.000.000/thn)'
            };

            function updatePTKPClassification() {
                var marital = $('#marital_status_select').val();
                var deps = $('#dependents_select').val();
                
                var prefix = (marital === 'Married' || marital === 'Kawin' || marital === 'K') ? 'K' : 'TK';
                var code = prefix + deps;
                
                if (ptkp_labels[code]) {
                    $('#ptkp_auto_display').val(ptkp_labels[code]);
                    if ($('#em_ptkp_select').length) {
                        $('#em_ptkp_select').val(code);
                    }
                    if ($('#pph_calc_ptkp').length) {
                        $('#pph_calc_ptkp').val(code);
                        if (typeof window.calculatePPh21 === 'function') {
                            window.calculatePPh21();
                        }
                    }
                }
            }

            $('#marital_status_select, #dependents_select').on('change', function() {
                updatePTKPClassification();
            });

            updatePTKPClassification();

            $('.profile-tab a').on('click', function(e) {
                e.preventDefault();
                var target = $(this).attr('href');

                // 1. Remove active class from all tabs
                $('.profile-tab a').removeClass('active');
                $(this).addClass('active');

                // 2. Hide all tab-panes inside tab-content
                $('.tab-content .tab-pane').removeClass('active show').hide();

                // 3. Show the selected target tab pane
                $(target).addClass('active show').show();

                // 4. Trigger calculations if target is BPJS or PPh
                if(target === '#bpjs' && typeof window.calculateBPJS === 'function') {
                    window.calculateBPJS();
                }
                if(target === '#pph' && typeof window.calculatePPh21 === 'function') {
                    window.calculatePPh21();
                }
            });
        });
        </script>
          <script type="text/javascript">
          $('.total').on('input',function() {
            var amount = parseInt($('.total').val());
            $('.basic').val((amount * .50 ? amount * .50 : 0).toFixed(2));
            $('.houserent').val((amount * .40 ? amount * .40 : 0).toFixed(2));
            $('.medical').val((amount * .05 ? amount * .05 : 0).toFixed(2));
            $('.conveyance').val((amount * .05 ? amount * .05 : 0).toFixed(2));
          });
          </script>
<?php $this->load->view('backend/em_modal'); ?>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".education").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#educationmodal').trigger("reset");
                                                $('#EduModal').modal('show');
                                                $.ajax({
                                                    url: 'educationbyib?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#educationmodal').find('[name="id"]').val(response.educationvalue.id).end();
                                                    $('#educationmodal').find('[name="name"]').val(response.educationvalue.edu_type).end();
                                                    $('#educationmodal').find('[name="institute"]').val(response.educationvalue.institute).end();
                                                    $('#educationmodal').find('[name="result"]').val(response.educationvalue.result).end();
                                                    $('#educationmodal').find('[name="year"]').val(response.educationvalue.year).end();
                                                    $('#educationmodal').find('[name="emid"]').val(response.educationvalue.emp_id).end();
												});
                                            });
                                        });
</script>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".experience").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $('#experiencemodal').trigger("reset");
                                                $('#ExpModal').modal('show');
                                                $.ajax({
                                                    url: 'experiencebyib?id=' + iid,
                                                    method: 'GET',
                                                    data: '',
                                                    dataType: 'json',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    // Populate the form fields with the data returned from server
													$('#experiencemodal').find('[name="id"]').val(response.expvalue.id).end();
                                                    $('#experiencemodal').find('[name="company_name"]').val(response.expvalue.exp_company).end();
                                                    $('#experiencemodal').find('[name="position_name"]').val(response.expvalue.exp_com_position).end();
                                                    $('#experiencemodal').find('[name="address"]').val(response.expvalue.exp_com_address).end();
                                                    $('#experiencemodal').find('[name="work_duration"]').val(response.expvalue.exp_workduration).end();
                                                    $('#experiencemodal').find('[name="emid"]').val(response.expvalue.emp_id).end();
												});
                                            });
                                        });
</script>                
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".deletexp").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $.ajax({
                                                    url: 'EXPvalueDelet?id=' + iid,
                                                    method: 'GET',
                                                    data: 'data',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    $(".message").fadeIn('fast').delay(3000).fadeOut('fast').html(response);
                                                    window.setTimeout(function(){location.reload()},2000)
                                                    // Populate the form fields with the data returned from server
												});
                                            });
                                        });
</script>                 
<script type="text/javascript">
                                        $(document).ready(function () {
                                            $(".edudelet").click(function (e) {
                                                e.preventDefault(e);
                                                // Get the record's ID via attribute  
                                                var iid = $(this).attr('data-id');
                                                $.ajax({
                                                    url: 'EduvalueDelet?id=' + iid,
                                                    method: 'GET',
                                                    data: 'data',
                                                }).done(function (response) {
                                                    console.log(response);
                                                    $(".message").fadeIn('fast').delay(3000).fadeOut('fast').html(response);
                                                    window.setTimeout(function(){location.reload()},2000)
                                                    // Populate the form fields with the data returned from server
												});
                                            });
                                        });
</script>                

<?php $this->load->view('backend/footer'); ?>