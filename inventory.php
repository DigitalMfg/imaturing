<?php
// session_start();
require 'function.php';

$status  = '';
$message = '';

if(isset($_SESSION['status'])){

    $status  = $_SESSION['status'];
    $message = $_SESSION['message'];

    unset($_SESSION['status']);
    unset($_SESSION['message']);
}



if(isset($_POST['submit'])){

    $model  = mysqli_real_escape_string($conn,$_POST['model']);
    $style  = mysqli_real_escape_string($conn,$_POST['style']);
    $colour = mysqli_real_escape_string($conn,$_POST['colour']);
    $mcs    = mysqli_real_escape_string($conn,$_POST['mcs']);
    $shift  = mysqli_real_escape_string($conn,$_POST['shift']);
    $kg     = (float)$_POST['kg'];

    if($kg <= 0){

        $_SESSION['status']  = "warning";
        $_SESSION['message'] = "Kg wajib diisi.";

        header("Location: inventory.php");
        exit;
    }

    $created_by = $_SESSION['username'];

    mysqli_query($conn,"
        INSERT INTO tbl_inventory
        (
            inventory_date,
            model,
            style,
            colour,
            mcs,
            shift,
            kg,
            created_by
        )
        VALUES
        (
            CURDATE(),
            '$model',
            '$style',
            '$colour',
            '$mcs',
            '$shift',
            '$kg',
            '$created_by'
        )
    ");

    $_SESSION['status']="success";
    $_SESSION['message']="Inventory berhasil disimpan.";

    header("Location: inventory.php");
    exit;
}

$getData = mysqli_query($conn,"
SELECT *
FROM tbl_inventory
ORDER BY id DESC
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

.dataTables_wrapper{
    overflow: visible !important;
}

.card-body{
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

<title>iMaturing | Inventory</title>

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
                Inventory
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                    Inventory
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

<form method="POST">

<div class="row">

    <div class="col-md-4">
        <label>Model <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="model"
            placeholder="Input model"
            required>
    </div>

    <div class="col-md-4">
        <label>Style <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="style"
            placeholder="Input Style"
            required>
    </div>

    <div class="col-md-4">
        <label>Colour <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="colour"
            placeholder="Input Colour"
            required>
    </div>

</div>
<br>
<div class="row">

    <div class="col-md-4">
        <label>MCS <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="mcs"
            placeholder="Input MCS"
            required>
    </div>

    <div class="col-md-4">
        <label>Shift <span class="text-danger">*</span></label>
        <select
            class="form-control"
            name="shift"
            required>
            <option value="">-- Select Shift --</option>
            <option value="1">1</option>
            <option value="2">2</option>
            <option value="3">3</option>
        </select>
    </div>

    <div class="col-md-4">
        <label>Kg <span class="text-danger">*</span></label>
        <input
            type="number"
            step="0.01"
            class="form-control"
            name="kg"
            placeholder="Input Kg"
            required>
    </div>

</div>

<br>

<button type="submit"
        name="submit"
        class="btn btn-primary"
        style="width:100px;">
    <i class="fas fa-save"></i> 
    Save
</button>

<button type="button"
        class="btn btn-secondary"
        style="width:100px;"
        onclick="window.location='inventory.php'">
    <i class="fas fa-sync-alt"></i>
    Reset
</button>

</form>
</div>
</div>

<div class="card card-outline card-success">

    <div class="card-header">

        <h3 class="card-title">
            List Inventory
        </h3>

    </div>

    <div class="card-body">
    <div class="report-table">
        <table id="reportMinus"
               class="table table-bordered table-striped">

                <thead>

                    <tr class='text-center'>
                        <th>No</th>
                        <th>Date</th>
                        <th>Model</th>
                        <th>Style</th>
                        <th>Colour</th>
                        <th>MCS</th>
                        <th>Shift</th>
                        <th>Kg</th>
                        <th>Created By</th>
                    </tr>

                </thead>
                <tbody>
                    <?php
                    $no=1;
                    while($row=mysqli_fetch_assoc($getData)){
                    ?>
                    <tr class='text-center'>
                        <td><?= $no++; ?></td>
                        <td><?= $row['inventory_date']; ?></td>
                        <td><?= htmlspecialchars($row['model']); ?></td>
                        <td><?= htmlspecialchars($row['style']); ?></td>
                        <td><?= htmlspecialchars($row['colour']); ?></td>
                        <td><?= htmlspecialchars($row['mcs']); ?></td>
                        <td><?= $row['shift']; ?></td>
                        <td><?= number_format($row['kg'],2); ?></td>
                        <td><?= htmlspecialchars($row['created_by']); ?></td>
                    </tr>
                    <?php } ?>
                </tbody>
        </table>
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

        buttons: [{
            extend: 'excelHtml5',
            text: 'Export Excel',
            className: 'btn btn-success btn-sm',
            title: 'Inventory'
        }]
    });

});

$(document).on('click','.btn-detail',function(){

    let id = $(this).data('id');

    $('#modalDetail').modal('show');

    $('#detailBody').load('ajax_detail_request.php?id=' + id);

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


