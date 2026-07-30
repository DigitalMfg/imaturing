<?php
// session_start();
require 'function.php';

$date_filter   = $_GET['date'] ?? '';
$colour_filter = $_GET['colour'] ?? '';
$style_filter  = $_GET['style'] ?? '';
$model_filter  = $_GET['model'] ?? '';
$mcs_filter    = $_GET['mcs'] ?? '';

$status  = '';
$message = '';

if(isset($_SESSION['status'])){

    $status  = $_SESSION['status'];
    $message = $_SESSION['message'];

    unset($_SESSION['status']);
    unset($_SESSION['message']);
}
$where = " WHERE 1=1 ";

if($date_filter != '')
    $where .= " AND DATE(h.request_date)='$date_filter'";

if($colour_filter != '')
    $where .= " AND d.colour='$colour_filter'";

if($style_filter != '')
    $where .= " AND d.style='$style_filter'";

if($model_filter != '')
    $where .= " AND d.model='$model_filter'";

if($mcs_filter != '')
    $where .= " AND d.mcs='$mcs_filter'";

$getData = mysqli_query($conn,"
SELECT

    h.request_date,
    h.planning_by,
    h.status,

    d.id,
    d.request_id,
    d.colour,
    d.style,
    d.model,
    d.mcs,
    d.category,
    d.kg,
    d.status,
    d.reject_reason

    FROM tbl_request_header h

    INNER JOIN tbl_request_detail d
    ON h.id=d.request_id

    $where

    ORDER BY h.id DESC
");

?>
<style>
.dataTables_wrapper {
    width: 100% !important;
}

.dataTables_length {
    float: left !important;
}

.dataTables_filter {
    float: right !important;
}

.dataTables_info {
    float: left !important;
    margin-top: 10px;
}

.dataTables_paginate {
    float: right !important;
    margin-top: 10px;
}

.table-sm input{
    width: 60px;
    height: 30px;
    text-align: center;
    margin: auto;
    padding: 2px;
}

.content-wrapper{
    overflow-x: hidden;
    min-height: 100vh !important;
}

.card-body{
    overflow: visible !important;
}

.dataTables_wrapper{
    overflow: visible !important;
}

.report-table{
    width:100%;
}

#reportMinus{
    width:100% !important;
}
</style>
<!DOCTYPE html>
<html lang="en">
<head>

<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<title>iMaturing | QA Process</title>

<link rel="icon" href="assets/images/i.Phylon.png" type="image/x-icon">

<link rel="stylesheet" href="plugins/fontawesome-free/css/all.min.css">
<link rel="stylesheet" href="dist/css/adminlte.min.css">

<link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
<link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">
<link rel="stylesheet" href="plugins/select2/css/select2.min.css">
<link rel="stylesheet" href="plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css">

</head>

<body class="hold-transition sidebar-mini layout-fixed">

<div class="wrapper">

<?php include 'header.php'; ?>

<div class="content-wrapper">
    <section class="content-header">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                <h1>
                <i class="fas fa-chart-bar"></i>
                QA Process
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                    QA Process
                    </li>
                </ol>
            </div>

        </div>

        </div>
    </section>

    <section class="content">
<div class="container-fluid">

<div class="card card-outline card-primary">

<div class="card-header">
<h3 class="card-title">
Filter Report
</h3>
</div>

<div class="card-body">
    <?php if(isset($_SESSION['success'])): ?>
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button"
                    class="close"
                    data-dismiss="alert">
                &times;
            </button>

            <i class="fas fa-check-circle"></i>
            <?= $_SESSION['success']; ?>
        </div>

    <?php unset($_SESSION['success']); ?>

    <?php endif; ?>

<form method="GET">

<div class="row">

    <div class="col-md-2">
        <label>Date</label>
        <input
            type="date"
            name="date"
            class="form-control"
            value="<?= $date_filter ?>">
    </div>

    <div class="col-md-3">
        <label>Colour</label>
        <select
            id="colour"
            name="colour"
            class="form-control select2bs4">

            <option value="">-- All Colour --</option>

            <?php

            $q=mysqli_query($conn,"
            SELECT DISTINCT colour
            FROM tbl_request_detail
            ORDER BY colour
            ");

            while($r=mysqli_fetch_assoc($q))
            {
            ?>

            <option
            value="<?= $r['colour'] ?>"
            <?= $colour_filter==$r['colour']?'selected':'';?>>

            <?= $r['colour'] ?>

            </option>

            <?php } ?>

            </select>
    </div>

    <div class="col-md-2">
        <label>Style</label>

        <select
            id="style"
            name="style"
            class="form-control select2bs4">

            <option value="">-- All Style --</option>

            <?php
            $q = mysqli_query($conn,"
                SELECT DISTINCT style
                FROM tbl_request_detail
                ORDER BY style
            ");

            while($r = mysqli_fetch_assoc($q)){
            ?>

                <option
                    value="<?= $r['style'] ?>"
                    <?= $style_filter == $r['style'] ? 'selected' : '' ?>>
                    <?= $r['style'] ?>
                </option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-3">
        <label>Model</label>

        <select
            id="model"
            name="model"
            class="form-control select2bs4">

            <option value="">-- All Model --</option>

            <?php
            $q = mysqli_query($conn,"
                SELECT DISTINCT model
                FROM tbl_request_detail
                ORDER BY model
            ");

            while($r = mysqli_fetch_assoc($q)){
            ?>

                <option
                    value="<?= $r['model'] ?>"
                    <?= $model_filter == $r['model'] ? 'selected' : '' ?>>
                    <?= $r['model'] ?>
                </option>

            <?php } ?>

        </select>
    </div>

    <div class="col-md-2">
    <label>MCS</label>

    <select
        id="mcs"
        name="mcs"
        class="form-control select2bs4">

        <option value="">-- All MCS --</option>

        <?php
        $q = mysqli_query($conn,"
            SELECT DISTINCT mcs
            FROM tbl_request_detail
            ORDER BY mcs
        ");

        while($r = mysqli_fetch_assoc($q)){
        ?>

            <option
                value="<?= $r['mcs'] ?>"
                <?= $mcs_filter == $r['mcs'] ? 'selected' : '' ?>>
                <?= $r['mcs'] ?>
            </option>

        <?php } ?>

    </select>
</div>


</div>

<br>

<button type="submit"
        name="submit"
        class="btn btn-primary"
        style="width:100px;">
    <i class="fas fa-search"></i>
    Search
</button>

<button type="button"
        class="btn btn-secondary"
        style="width:100px;"
        onclick="window.location='qa_process.php'">
    <i class="fas fa-sync-alt"></i>
    Reset
</button>

</form>
</div>
</div>

<div class="card card-outline card-success">

    <div class="card-header">

        <h3 class="card-title">
            List QA Approval
        </h3>

    </div>

    <div class="card-body">
        

        <div class="report-table">

            <table id="reportMinus"
                class="table table-bordered table-striped">

                <thead>

                    <tr>
                        <th width="50">No</th>
                        <th>Date</th>
                        <th>Planning By</th>
                        <th>Colour</th>
                        <th>Style</th>
                        <th>Model</th>
                        <th>MCS</th>
                        <th>Category</th>
                        <th class="text-center">Status</th>
                        <th class='text-center'>Detail</th>
                        <th class='text-center'>Action</th>
                    </tr>

                </thead>

                <tbody>
                        <?php
                        $no = 1;

                        while($row=mysqli_fetch_assoc($getData)){
                        ?>
                    <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= $row['request_date']; ?></td>
                        <td><?= htmlspecialchars($row['planning_by']); ?></td>
                        <td><?= htmlspecialchars($row['colour']); ?></td>
                        <td><?= htmlspecialchars($row['style']); ?></td>
                        <td><?= htmlspecialchars($row['model']); ?></td>
                        <td><?= htmlspecialchars($row['mcs']); ?></td>
                        <td><?= htmlspecialchars($row['category']); ?></td>
                        <td class="text-center">
                            <?php if($row['status']=='Approved'){ ?>

                                <span class="badge badge-success">
                                    <i class="fas fa-check"></i>
                                    Approved
                                </span>

                            <?php }elseif($row['status']=='Rejected'){ ?>

                                <span 
                                    class="badge badge-danger"
                                    title="<?= htmlspecialchars($row['reject_reason']); ?>">
                                    <i class="fas fa-times"></i>
                                    Rejected
                                </span>

                            <?php }else{ ?>

                                <span class="badge badge-warning">
                                    <i class="fas fa-clock"></i>
                                    Pending
                                </span>

                            <?php } ?>

                            </td>
                        <td class="text-center">

                        <button
                        class="btn btn-info btn-sm btn-detail"
                        data-id="<?= $row['id']; ?>">
                        <i class="fas fa-eye"></i>
                        Detail
                        </button>
                        </td>

                        <td class="text-center">

                            <?php if($row['status']=='Approved'){ ?>
                                <button
                                    class="btn btn-success btn-sm"
                                    disabled>
                                    <i class="fas fa-check"></i>
                                    Approved
                                </button>

                                <button
                                    class="btn btn-danger btn-sm"
                                    disabled>
                                    <i class="fas fa-times"></i>
                                    Reject
                                </button>

                            <?php }elseif($row['status']=='Rejected'){ ?>

                                <button
                                    class="btn btn-success btn-sm btn-approve"
                                    data-id="<?= $row['id']; ?>">
                                    <i class="fas fa-check"></i>
                                    Approve
                                </button>

                                <button
                                    class="btn btn-danger btn-sm"
                                    disabled>
                                    <i class="fas fa-times"></i>
                                    Rejected
                                </button>

                            <?php }else{ ?>

                                <button
                                    class="btn btn-success btn-sm btn-approve"
                                    data-id="<?= $row['id']; ?>">
                                    <i class="fas fa-check"></i>
                                    Approve
                                </button>

                                <button
                                    class="btn btn-danger btn-sm btn-reject"
                                    data-id="<?= $row['id']; ?>">
                                    <i class="fas fa-times"></i>
                                    Reject
                                </button>
                            <?php } ?>
                        </td>

                    </tr>

                    <?php } ?>

                </tbody>
            </table>
        </div>
    </div>
</div>

</div>
</section>
</div>

    <div class="modal fade" id="modalDetail">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header bg-info">
                    <h4 class="modal-title">
                        <i class="fas fa-eye"></i>
                        Detail SPK Planning
                    </h4>

                    <button
                        type="button"
                        class="close"
                        data-dismiss="modal">
                        &times;
                    </button>
                </div>

                <div class="modal-body" id="detailBody">
                    Loading...
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modalReject">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header bg-danger">
                    <h4 class="modal-title">
                        Reject Material
                    </h4>
                </div>

                <div class="modal-body">

                    <input
                        type="hidden"
                        id="reject_id">
                    <label>Reason</label>

                    <textarea
                        id="reject_reason"
                        class="form-control"
                        rows="5"></textarea>

                </div>

                <div class="modal-footer">
                    <button
                        class="btn btn-secondary"
                        data-dismiss="modal">
                        Cancel
                    </button>

                    <button
                        id="saveReject"
                        class="btn btn-danger">
                        Save
                    </button>
                </div>
            </div>
        </div>
    </div>

<!-- FOOTER -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>2024 
    <strong><a href="#">Mfg Project Officer</a>.</strong> All rights reserved.
  </footer>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/select2/js/select2.full.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>

<script src="dist/js/adminlte.min.js"></script>

<script>

$(function(){

    $('.select2bs4').select2({
        theme: 'bootstrap4',
        placeholder: 'Select Data',
        allowClear: true
    });

        $('#reportMinus').DataTable({

            responsive: false,
            scrollX: true,
            scrollCollapse: true,
            autoWidth: false,

            paging: true,
            searching: true,
            ordering: true,
            lengthMenu: [10,25,50,100,250,500],

            dom:
                "<'row mb-2'<'col-sm-6 d-flex align-items-center'l><'col-sm-6 d-flex justify-content-end gap-2'Bf>>" +
                "<'row'<'col-sm-12'tr>>" +
                "<'row mt-2'<'col-sm-5'i><'col-sm-7'p>>",

            buttons:[
                {
                    extend:'excelHtml5',
                    text:'Export Excel',
                    className:'btn btn-success btn-sm',
                    title:'QA Approval'
                }
            ]
        });

});

$(document).on('click','.btn-detail',function(){

    let id = $(this).data('id');

    $('#modalDetail').modal('show');

    $('#detailBody').load('ajax_detail_request.php?id=' + id);

});

$(document).on('click','.btn-approve',function(){
    let id=$(this).data('id');
    if(confirm('Approve data ini ?')){
        $.post('process_approve.php',{
            id:id
        },function(){
            location.reload();
        });
    }
});

$(document).on('click','.btn-reject',function(){
    $('#reject_id').val($(this).data('id'));
    $('#reject_reason').val('');
    $('#modalReject').modal('show');
});

$('#saveReject').click(function(){
    $.post('process_reject.php',{
        id:$('#reject_id').val(),
        reason:$('#reject_reason').val()
    },function(){
        location.reload();
    });
});



</script>
<?php if($status != ''): ?>

<script>

document.addEventListener('DOMContentLoaded', function(){

    Swal.fire({
        toast: true,
        position: 'top-end',
        icon: '<?= $status ?>',
        title: '<?= $message ?>',
        showConfirmButton: false,
        timer: 1500,
        timerProgressBar: true
    });

    <?php if($status == 'success'): ?>
        new Audio('assets/sound/success.mp3').play();
    <?php endif; ?>

    <?php if($status == 'error' || $status == 'warning'): ?>
        new Audio('assets/sound/error.mp3').play();
    <?php endif; ?>

});

</script>

<?php endif; ?>
</body>
</html>


