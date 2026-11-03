<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-calendar" style="color:#9f0a0b"></i> Evaluation Period List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">Evaluation Period</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addPeriodModal">
                    <i class="fa fa-plus"></i> Add New Evaluation Period
                </button>
            </div>
        </div>

        <!-- Period List Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">Evaluation Periods List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table id="periodTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Period Name</th>
                                        <th>Start Date</th>
                                        <th>End Date</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($periods)): ?>
                                        <?php foreach ($periods as $period): ?>
                                            <tr>
                                                <td><?= html_escape($period->period_id); ?></td>
                                                <td><?= html_escape($period->period_name); ?></td>
                                                <td><?= html_escape($period->start_date); ?></td>
                                                <td><?= html_escape($period->end_date); ?></td>
                                                <td><?= html_escape($period->description); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = ($period->status == 'active') ? 'badge badge-success' : 'badge badge-danger';
                                                    echo '<span class="' . $status_class . '">' . html_escape(ucfirst($period->status)) . '</span>';
                                                    ?>
                                                </td>
                                                <td><?= html_escape(date('jS M Y', strtotime($period->created_at))); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info editPeriodBtn"
                                                        data-id="<?= $period->period_id ?>"
                                                        data-name="<?= html_escape($period->period_name) ?>"
                                                        data-start="<?= $period->start_date ?>"
                                                        data-end="<?= $period->end_date ?>"
                                                        data-description="<?= html_escape($period->description) ?>"
                                                        data-status="<?= $period->status ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger deletePeriodBtn"
                                                        data-id="<?= $period->period_id ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr><td colspan="8" class="text-center">No evaluation periods found.</td></tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add Period Modal -->
    <div class="modal fade" id="addPeriodModal" tabindex="-1" role="dialog" aria-labelledby="addPeriodModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('evaluation/save_period') ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addPeriodModalLabel">Add New Evaluation Period</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Period Name</label>
                            <input type="text" name="period_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add Period</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add Period Modal -->

    <!-- Edit Period Modal -->
    <div class="modal fade" id="editPeriodModal" tabindex="-1" role="dialog" aria-labelledby="editPeriodModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('evaluation/save_period') ?>">
                <input type="hidden" name="period_id" id="editPeriodId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editPeriodModalLabel">Edit Evaluation Period</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>Period Name</label>
                            <input type="text" name="period_name" id="editPeriodName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Start Date</label>
                            <input type="date" name="start_date" id="editPeriodStart" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>End Date</label>
                            <input type="date" name="end_date" id="editPeriodEnd" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" id="editPeriodDescription" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="editPeriodStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update Period</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Edit Period Modal -->

    <!-- Delete Period Modal -->
    <div class="modal fade" id="deletePeriodModal" tabindex="-1" role="dialog" aria-labelledby="deletePeriodModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('evaluation/delete_period') ?>">
                <input type="hidden" name="period_id" id="deletePeriodId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deletePeriodModalLabel">Delete Evaluation Period</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this period?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Delete Period Modal -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $('.editPeriodBtn').on('click', function(){
        $('#editPeriodId').val($(this).data('id'));
        $('#editPeriodName').val($(this).data('name'));
        $('#editPeriodStart').val($(this).data('start'));
        $('#editPeriodEnd').val($(this).data('end'));
        $('#editPeriodDescription').val($(this).data('description'));
        $('#editPeriodStatus').val($(this).data('status'));
        $('#editPeriodModal').modal('show');
    });

    $('.deletePeriodBtn').on('click', function(){
        $('#deletePeriodId').val($(this).data('id'));
        $('#deletePeriodModal').modal('show');
    });
});
</script>
<?php $this->load->view('backend/footer'); ?>
