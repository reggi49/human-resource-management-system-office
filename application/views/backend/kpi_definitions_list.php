<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<?php $this->load->view('backend/header'); ?>
<?php $this->load->view('backend/sidebar'); ?>

<div class="page-wrapper">
    <div class="row page-titles">
        <div class="col-md-5 align-self-center">
            <h3 class="text-themecolor"><i class="fa fa-bar-chart" style="color:#9f0a0b"></i> KPI List</h3>
        </div>
        <div class="col-md-7 align-self-center">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="javascript:void(0)">Home</a></li>
                <li class="breadcrumb-item active">KPI</li>
            </ol>
        </div>
    </div>

    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-12">
                <button type="button" class="btn btn-info" data-toggle="modal" data-target="#addKpiModal">
                    <i class="fa fa-plus"></i> Add New KPI Definition
                </button>
            </div>
        </div>

        <!-- KPI List Table -->
        <div class="row">
            <div class="col-12">
                <div class="card card-outline-info">
                    <div class="card-header">
                        <h4 class="m-b-0 text-white">KPI Definitions List</h4>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive ">
                            <table id="kpiDefTable" class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>KPI Name</th>
                                        <th>Category</th>
                                        <th>Level</th>
                                        <th>Period</th>
                                        <th>Target</th>
                                        <th>Weight</th>
                                        <th>Description</th>
                                        <th>Status</th>
                                        <th>Created At</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (!empty($kpis)): ?>
                                        <?php foreach ($kpis as $kpi): ?>
                                            <tr>
                                                <td><?= html_escape($kpi->kpi_id); ?></td>
                                                <td><?= html_escape($kpi->kpi_name); ?></td>
                                                <td><?= html_escape($kpi->kpi_category); ?></td>
                                                <td><?= html_escape($kpi->kpi_level); ?></td>
                                                <td><?= html_escape($kpi->period_name ?? ''); ?></td>
                                                <td><?= html_escape($kpi->target_value); ?></td>
                                                <td><?= html_escape($kpi->weight); ?></td>
                                                <td><?= html_escape($kpi->description); ?></td>
                                                <td>
                                                    <?php
                                                    $status_class = ($kpi->status == 'active') ? 'badge badge-success' : 'badge badge-danger';
                                                    echo '<span class="' . $status_class . '">' . html_escape(ucfirst($kpi->status)) . '</span>';
                                                    ?>
                                                </td>
                                                <td><?= html_escape(date('jS M Y', strtotime($kpi->created_at))); ?></td>
                                                <td>
                                                    <button class="btn btn-sm btn-info editKpiBtn"
                                                        data-id="<?= $kpi->kpi_id ?>"
                                                        data-name="<?= html_escape($kpi->kpi_name) ?>"
                                                        data-category="<?= html_escape($kpi->kpi_category) ?>"
                                                        data-level="<?= html_escape($kpi->kpi_level) ?>"
                                                        data-period="<?= $kpi->period_id ?>"
                                                        data-target="<?= html_escape($kpi->target_value) ?>"
                                                        data-weight="<?= html_escape($kpi->weight) ?>"
                                                        data-description="<?= html_escape($kpi->description) ?>"
                                                        data-status="<?= html_escape($kpi->status) ?>">
                                                        <i class="fa fa-pencil"></i>
                                                    </button>
                                                    <button class="btn btn-sm btn-danger deleteKpiBtn"
                                                        data-id="<?= $kpi->kpi_id ?>">
                                                        <i class="fa fa-trash"></i>
                                                    </button>
                                                </td>
                                            </tr>
                                        <?php endforeach ?>
                                    <?php else: ?>
                                        <tr><td colspan="11" class="text-center">No KPI definitions found.</td></tr>
                                    <?php endif ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add KPI Modal -->
    <div class="modal fade" id="addKpiModal" tabindex="-1" role="dialog" aria-labelledby="addKpiModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('kpi/save_definition') ?>">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="addKpiModalLabel">Add New KPI Definition</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>KPI Name</label>
                            <input type="text" name="kpi_name" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="kpi_category" class="form-control" required>
                                <option value="HRD">HRD</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="IT">IT</option>
                                <option value="Operasional">Operasional</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Level</label>
                            <select name="kpi_level" class="form-control" required>
                                <option value="Individu">Individu</option>
                                <option value="Organisasi">Organisasi</option>
                                <option value="Perusahaan">Perusahaan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Period</label>
                            <select name="period_id" class="form-control" required>
                                <option value="">-- Select Period --</option>
                                <?php foreach ($periods as $period): ?>
                                    <option value="<?= $period->period_id ?>">
                                        <?= html_escape($period->period_name) ?> (<?= $period->start_date ?> - <?= $period->end_date ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Target</label>
                            <input type="number" step="any" name="target_value" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Weight</label>
                            <input type="number" step="any" name="weight" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" class="form-control">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Add KPI</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Add KPI Modal -->

    <!-- Edit KPI Modal -->
    <div class="modal fade" id="editKpiModal" tabindex="-1" role="dialog" aria-labelledby="editKpiModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('kpi/edit_definition') ?>">
                <input type="hidden" name="kpi_id" id="editKpiId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="editKpiModalLabel">Edit KPI Definition</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label>KPI Name</label>
                            <input type="text" name="kpi_name" id="editKpiName" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Category</label>
                            <select name="kpi_category" id="editKpiCategory" class="form-control" required>
                                <option value="HRD">HRD</option>
                                <option value="Marketing">Marketing</option>
                                <option value="Finance">Finance</option>
                                <option value="IT">IT</option>
                                <option value="Operasional">Operasional</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Level</label>
                            <select name="kpi_level" id="editKpiLevel" class="form-control" required>
                                <option value="Individu">Individu</option>
                                <option value="Organisasi">Organisasi</option>
                                <option value="Perusahaan">Perusahaan</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Evaluation Period</label>
                            <select name="period_id" id="editKpiPeriod" class="form-control" required>
                                <option value="">-- Select Period --</option>
                                <?php foreach ($periods as $period): ?>
                                    <option value="<?= $period->period_id ?>">
                                        <?= html_escape($period->period_name) ?> (<?= $period->start_date ?> - <?= $period->end_date ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group">
                            <label>Target</label>
                            <input type="number" step="any" name="target_value" id="editKpiTarget" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Weight</label>
                            <input type="number" step="any" name="weight" id="editKpiWeight" class="form-control" required>
                        </div>
                        <div class="form-group">
                            <label>Description</label>
                            <input type="text" name="description" id="editKpiDescription" class="form-control">
                        </div>
                        <div class="form-group">
                            <label>Status</label>
                            <select name="status" id="editKpiStatus" class="form-control" required>
                                <option value="active">Active</option>
                                <option value="inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-success">Update KPI</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Edit KPI Modal -->

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteKpiModal" tabindex="-1" role="dialog" aria-labelledby="deleteKpiModalLabel">
        <div class="modal-dialog" role="document">
            <form method="post" action="<?= base_url('kpi/delete_definition') ?>">
                <input type="hidden" name="kpi_id" id="deleteKpiId">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="deleteKpiModalLabel">Delete KPI Definition</h4>
                        <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                    </div>
                    <div class="modal-body">
                        <p>Are you sure you want to delete this KPI?</p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-danger">Delete</button>
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- /Delete KPI Modal -->
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
$(function(){
    $('.editKpiBtn').on('click', function(){
        $('#editKpiId').val($(this).data('id'));
        $('#editKpiName').val($(this).data('name'));
        $('#editKpiCategory').val($(this).data('category'));
        $('#editKpiLevel').val($(this).data('level'));
        $('#editKpiPeriod').val($(this).data('period'));
        $('#editKpiTarget').val($(this).data('target'));
        $('#editKpiWeight').val($(this).data('weight'));
        $('#editKpiDescription').val($(this).data('description'));
        $('#editKpiStatus').val($(this).data('status'));
        $('#editKpiModal').modal('show');
    });

    $('.deleteKpiBtn').on('click', function(){
        $('#deleteKpiId').val($(this).data('id'));
        $('#deleteKpiModal').modal('show');
    });
});
</script>
<?php $this->load->view('backend/footer'); ?>
