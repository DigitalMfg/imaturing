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

    $model    = mysqli_real_escape_string($conn,$_POST['model']);
    $style    = mysqli_real_escape_string($conn,$_POST['style']);
    $bucket   = mysqli_real_escape_string($conn,$_POST['bucket']);
    $crafted  = mysqli_real_escape_string($conn,$_POST['crafted']);
    $shift    = mysqli_real_escape_string($conn,$_POST['shift']);
    $colour   = mysqli_real_escape_string($conn,$_POST['colour']);
    $mcs      = mysqli_real_escape_string($conn,$_POST['mcs']);
    $category = mysqli_real_escape_string($conn,$_POST['category']);
    $kg = isset($_POST['size_run']['Kg']) ? (float)$_POST['size_run']['Kg'] : 0;

    if($kg <= 0){

        $_SESSION['status']  = "warning";
        $_SESSION['message'] = "Kg wajib diisi.";

        header("Location: spk_planning.php");
        exit;
    }

    $planning_by = mysqli_real_escape_string($conn, $_SESSION['username']);

        mysqli_query($conn,"
            INSERT INTO tbl_request_header
            (
                request_date,
                planning_by,
                status
            )
            VALUES
            (
                CURDATE(),
                '$planning_by',
                'OPEN'
            )
        ");

    $request_id = mysqli_insert_id($conn);
        if(!mysqli_query($conn,"
            INSERT INTO tbl_request_detail
            (
                request_id,
                bucket,
                crafted,
                shift,
                style,
                model,
                colour,
                mcs,
                category,
                kg
            )
            VALUES
            (
                '$request_id',
                '$bucket',
                '$crafted',
                '$shift',
                '$style',
                '$model',
                '$colour',
                '$mcs',
                '$category',
                '$kg'
            )
            ")){
                die(mysqli_error($conn));
            }

        $detail_id = mysqli_insert_id($conn);

        foreach($_POST['size_run'] as $size => $qty){

        if($size == 'Kg'){
            continue;
        }

        if($qty == '' || $qty <= 0){
            continue;
        }

        mysqli_query($conn,"
            INSERT INTO tbl_request_size
            (
                detail_id,
                size,
                qty
            )
            VALUES
            (
                '$detail_id',
                '$size',
                '$qty'
            )
        ");

    }

    $_SESSION['status']  = "success";
    $_SESSION['message'] = "Data SPK Planning berhasil disimpan.";
    header("Location: spk_planning.php");
    exit;

}

    $getData = mysqli_query($conn,"
        SELECT
            h.request_date,

            d.id,
            d.bucket,
            d.crafted,
            d.shift,
            d.colour,
            d.style,
            d.model,
            d.mcs,
            d.category,
            d.kg

        FROM tbl_request_detail d

        INNER JOIN tbl_request_header h
            ON h.id = d.request_id

        ORDER BY d.id DESC
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

<title>iMaturing | Spk Planning</title>

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
                SPK Planning
                </h1>
            </div>

            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                    </li>
                    <li class="breadcrumb-item active">
                    SPK Planning
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

    <div class="col-md-3">
        <label>Model <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="model"
            placeholder="Input model"
            required>
    </div>

    <div class="col-md-3">
        <label>Style <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="style"
            placeholder="Input Style"
            required>
    </div>

    <div class="col-md-3">
        <label>Bucket <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="bucket"
            placeholder="Input Bucket"
            required>
    </div>

    <div class="col-md-3">
        <label>Crafted <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="crafted"
            placeholder="Input Crafted"
            required>
    </div>
</div>
<br>
<div class="row">
    <div class="col-md-3">
        <label>Shift <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="shift"
            placeholder="Input Shift"
            required>
    </div>

        <div class="col-md-3">
        <label>Colour <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="colour"
            placeholder="Input Colour"
            required>
    </div>

    <div class="col-md-3">
        <label>MCS <span class="text-danger">*</span></label>
        <input type="text"
            class="form-control"
            name="mcs"
            placeholder="Input MCS"
            required>
    </div>

    <div class="col-md-3">
        <label>Category <span class="text-danger">*</span></label>
        <select
            class="form-control"
            name="category"
            required>
            <option value="">-- Select Category --</option>
            <option value="BON STOCKFIT">BON STOCKFIT</option>
            <option value="BON ASEMBLING">BON ASEMBLING</option>
            <option value="EXPORT">EXPORT</option>
            <option value="RETURN">RETURN</option>
            <option value="PROMO">PROMO</option>
            <option value="MINUS BUCKET">MINUS BUCKET</option>

        </select>
    </div>
</div>

<hr>

<div class="row">
    <div class="col-md-12">

        <label>Size Run</label>
        <div class="table-responsive">
            <table class="table table-bordered table-sm text-center mb-0">
                <thead class="bg-light">

                    <tr>

                        <th>1</th>
                        <th>1T</th>
                        <th>2</th>
                        <th>2T</th>
                        <th>3</th>
                        <th>3T</th>
                        <th>4</th>
                        <th>4T</th>
                        <th>5</th>
                        <th>5T</th>
                        <th>6</th>
                        <th>6T</th>
                        <th>7</th>
                        <th>7T</th>
                        <th>8</th>
                        <th>8T</th>
                        <th>9</th>
                        <th>9T</th>
                        <th>10</th>
                        <th>10T</th>
                        <th>11</th>
                        <th>11T</th>
                        <th>12</th>
                        <th>12T</th>
                        <th>13</th>
                        <th>13T</th>
                        <th>14</th>
                        <th>14T</th>
                        <th>15</th>
                        <th>Kg <span class="text-danger">*</span></th>

                    </tr>

                </thead>

                <tbody>
                    <tr>

                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[1]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[1T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[2]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[2T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[3]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[3T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[4]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[4T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[5]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[5T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[6]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[6T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[7]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[7T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[8]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[8T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[9]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[9T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[10]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[10T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[11]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[11T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[12]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[12T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[13]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[13T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[14]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[14T]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[15]" min="0"></td>
                        <td><input type="number" class="form-control form-control-sm text-center" name="size_run[Kg]" placeholder="Kg" step="0.01" min="0.01" required> </td>

                    </tr>
                </tbody>

            </table>

        </div>

    </div>
</div>

<br>

<button type="submit"
        name="submit"
        class="btn btn-primary"
        style="width:100px;">
    <i class="fas fa-paper-plane"></i>
    Submit
</button>

<button type="button"
        class="btn btn-secondary"
        style="width:100px;"
        onclick="window.location='spk_planning.php'">
    <i class="fas fa-sync-alt"></i>
    Reset
</button>

</form>
</div>
</div>

<div class="card card-outline card-success">

    <div class="card-header">

        <h3 class="card-title">
            SPK Planning
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
                        <th>Model</th>
                        <th>Style</th>
                        <th>Bucket</th>
                        <th>Crafted</th>
                        <th>Shift</th>
                        <th>Colour</th>
                        <th>MCS</th>
                        <th>Category</th>
                        <th class='text-center'>Action</th>
                    </tr>

                </thead>

                <tbody>
                    <?php
                        $no = 1;
                        while($row = mysqli_fetch_assoc($getData)):
                        ?>
                        <tr>
                        <td class="text-center"><?= $no++; ?></td>
                        <td><?= date('d-m-Y', strtotime($row['request_date'])) ?></td>
                        <td><?= htmlspecialchars($row['model']) ?></td>
                        <td><?= htmlspecialchars($row['style']) ?></td>
                        <td><?= htmlspecialchars($row['bucket']) ?></td>
                        <td><?= htmlspecialchars($row['crafted']) ?></td>
                        <td><?= htmlspecialchars($row['shift']) ?></td>
                        <td><?= htmlspecialchars($row['colour']) ?></td>
                        <td><?= htmlspecialchars($row['mcs']) ?></td>
                        <td><?= htmlspecialchars($row['category']) ?></td>
                        <td class="text-center">
                            <button
                                class="btn btn-info btn-sm btn-detail"
                                style="width:85px;"
                                data-id="<?= $row['id']; ?>">
                                <i class="fas fa-eye"></i>
                                Detail
                            </button>

                            <button
                                class="btn btn-warning btn-sm btn-edit"
                                style="width:85px;"
                                data-id="<?= $row['id']; ?>">
                                <i class="fas fa-edit"></i>
                                Edit
                            </button>

                        </td>
                    </tr>
                    <?php endwhile; ?>
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

    <div class="modal fade" id="modalEdit">

    <div class="modal-dialog modal-xl">
        <div class="modal-content">
            <div class="modal-header bg-warning">
                <h4 class="modal-title">
                    <i class="fas fa-edit"></i>
                    Edit SPK Planning
                    </h4>

                <button
                    type="button"
                    class="close"
                    data-dismiss="modal">
                    &times;
                </button>
                </div>
                
                <form id="formEdit">

                    <div class="modal-body" id="editBody">
                        Loading...
                    </div>

                    <div class="modal-footer">
                        <button
                            type="button"
                            class="btn btn-secondary"
                            data-dismiss="modal">
                            Close
                        </button>

                        <button
                            type="submit"
                            class="btn btn-warning">
                            <i class="fas fa-save"></i>
                            Update
                        </button>
                    </div>
                </form>
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
            title: 'SPK Planning'
        }]
    });

});

   $(document).on('click','.btn-detail',function(){

    let id = $(this).data('id');

    $('#modalDetail').modal('show');

    $('#detailBody').load('ajax_detail_request.php?id=' + id);

});


    $(document).on('click','.btn-edit',function(){

        let id = $(this).data('id');

        $('#modalEdit').modal('show');

        $('#editBody').load('ajax_edit_request.php?id=' + id);

});

    $(document).on('submit','#formEdit',function(e){

        e.preventDefault();

        $.ajax({

            url:'update_request.php',
            type:'POST',
            data:$(this).serialize(),

            success:function(res){

        if($.trim(res) == 'success'){

            $('#modalEdit').modal('hide');

            new Audio('assets/sound/success.mp3').play();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Data SPK Planning berhasil diupdate.',
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

            setTimeout(function(){
                location.reload();
            },2000);

        }else{

            new Audio('assets/sound/error.mp3').play();

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'error',
                title: res,
                showConfirmButton: false,
                timer: 2000,
                timerProgressBar: true
            });

        }

    }

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


