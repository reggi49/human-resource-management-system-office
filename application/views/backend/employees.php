<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>
         <div class="page-wrapper">
            <!-- ============================================================== -->
            <!-- Bread crumb and right sidebar toggle -->
            <!-- ============================================================== -->
            <div class="row page-titles">
                <div class="col-md-5 align-self-center">
                    <h3 class="text-themecolor"><i class="fa fa-user-secret" aria-hidden="true"></i> Employee</h3>
                </div>
                <div class="col-md-7 align-self-center">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                        <li class="breadcrumb-item active">Employee</li>
                    </ol>
                </div>
            </div>
            <div class="message"></div>
            <div class="container-fluid">
                <div class="row m-b-10"> 
                    <div class="col-12">
                        <button type="button" class="btn btn-info"><i class="fa fa-plus"></i><a href="<?php echo base_url(); ?>employee/Add_employee" class="text-white"><i class="" aria-hidden="true"></i> Add Employee</a></button>
                        <button type="button" class="btn btn-primary"><i class="fa fa-bars"></i><a href="<?php echo base_url(); ?>employee/Disciplinary" class="text-white"><i class="" aria-hidden="true"></i>  Disciplinary List</a></button>
                    </div>
                </div>
                <div class="row">
                    <div class="col-12">
                        <div class="card card-outline-info">
                            <div class="card-header">
                                <h4 class="m-b-0 text-white"><i class="fa fa-user-o" aria-hidden="true"></i> Employee List</h4>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive ">
                                    <table id="employees123" class="display nowrap table table-hover table-striped table-bordered" cellspacing="0" width="100%">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Employee Name</th>
                                                <th>TTL</th>
                                                <th>Division</th>
                                                <th>Position</th>
                                                <th>Contact (HP)</th>
                                                <th>User Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tfoot>
                                            <tr>
                                                <th>No</th>
                                                <th>NIK</th>
                                                <th>Employee Name</th>
                                                <th>TTL</th>
                                                <th>Division</th>
                                                <th>Position</th>
                                                <th>Contact (HP)</th>
                                                <th>User Type</th>
                                                <th>Action</th>
                                            </tr>
                                        </tfoot>
                                        <tbody>
                                           <?php foreach($employee as $value): ?>
                                            <tr>
                                                <td><strong><?php echo !empty($value->emp_no) ? $value->emp_no : '-'; ?></strong></td>
                                                <td><span class="badge badge-info"><?php echo $value->em_code; ?></span></td>
                                                <td title="<?php echo $value->first_name .' '.$value->last_name; ?>">
                                                    <strong><?php echo $value->first_name .' '.$value->last_name; ?></strong>
                                                    <br><small class="text-muted"><?php echo $value->em_email; ?></small>
                                                </td>
                                                <td><small><?php echo !empty($value->ttl) ? $value->ttl : (!empty($value->birth_place) ? $value->birth_place . ', ' : '') . $value->em_birthday; ?></small></td>
                                                <td><span class="badge badge-primary"><?php echo !empty($value->div_name) ? $value->div_name : '-'; ?></span></td>
                                                <td><?php echo !empty($value->des_name) ? $value->des_name : '-'; ?></td>
                                                <td><?php echo $value->em_phone; ?></td>
                                                <td><span class="badge <?php echo $value->em_role == 'ADMIN' || $value->em_role == 'SUPER ADMIN' ? 'badge-danger' : 'badge-success'; ?>"><?php echo $value->em_role; ?></span></td>
                                                <td class="jsgrid-align-center">
                                                    <a href="<?php echo base_url(); ?>employee/view?I=<?php echo base64_encode($value->em_id); ?>" title="Edit" class="btn btn-sm btn-info waves-effect waves-light"><i class="fa fa-pencil-square-o"></i></a>
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
<?php $this->load->view('backend/footer'); ?>
<script>
    $('#employees123').DataTable({
        "aaSorting": [[0,'asc']],
        dom: 'Bfrtip',
        buttons: [
            'copy', 'csv', 'excel', 'pdf', 'print'
        ]
    });
</script>