<?php
// session_start();
require 'function.php';

if (!isset($_SESSION['login'])) {
    header("Location: login.php");
    exit;
}

$getPlanning = mysqli_query($conn,"
SELECT

    d.id,
    d.style,
    d.model,
    d.colour,
    d.mcs,
    d.category,
    d.kg,

    d.status,
    d.reject_reason,


    MAX(CASE WHEN s.size='1' THEN s.qty END) AS size_1,
    MAX(CASE WHEN s.size='1T' THEN s.qty END) AS size_1T,
    MAX(CASE WHEN s.size='2' THEN s.qty END) AS size_2,
    MAX(CASE WHEN s.size='2T' THEN s.qty END) AS size_2T,
    MAX(CASE WHEN s.size='3' THEN s.qty END) AS size_3,
    MAX(CASE WHEN s.size='3T' THEN s.qty END) AS size_3T,
    MAX(CASE WHEN s.size='4' THEN s.qty END) AS size_4,
    MAX(CASE WHEN s.size='4T' THEN s.qty END) AS size_4T,
    MAX(CASE WHEN s.size='5' THEN s.qty END) AS size_5,
    MAX(CASE WHEN s.size='5T' THEN s.qty END) AS size_5T,
    MAX(CASE WHEN s.size='6' THEN s.qty END) AS size_6,
    MAX(CASE WHEN s.size='6T' THEN s.qty END) AS size_6T,
    MAX(CASE WHEN s.size='7' THEN s.qty END) AS size_7,
    MAX(CASE WHEN s.size='7T' THEN s.qty END) AS size_7T,
    MAX(CASE WHEN s.size='8' THEN s.qty END) AS size_8,
    MAX(CASE WHEN s.size='8T' THEN s.qty END) AS size_8T,
    MAX(CASE WHEN s.size='9' THEN s.qty END) AS size_9,
    MAX(CASE WHEN s.size='9T' THEN s.qty END) AS size_9T,
    MAX(CASE WHEN s.size='10' THEN s.qty END) AS size_10,
    MAX(CASE WHEN s.size='10T' THEN s.qty END) AS size_10T,
    MAX(CASE WHEN s.size='11' THEN s.qty END) AS size_11,
    MAX(CASE WHEN s.size='11T' THEN s.qty END) AS size_11T,
    MAX(CASE WHEN s.size='12' THEN s.qty END) AS size_12,
    MAX(CASE WHEN s.size='12T' THEN s.qty END) AS size_12T,
    MAX(CASE WHEN s.size='13' THEN s.qty END) AS size_13,
    MAX(CASE WHEN s.size='13T' THEN s.qty END) AS size_13T,
    MAX(CASE WHEN s.size='14' THEN s.qty END) AS size_14,
    MAX(CASE WHEN s.size='14T' THEN s.qty END) AS size_14T,
    MAX(CASE WHEN s.size='15' THEN s.qty END) AS size_15


FROM tbl_request_detail d

LEFT JOIN tbl_request_size s
ON d.id=s.detail_id

GROUP BY d.id

ORDER BY d.id DESC

");

?>

<style>
  .stat-card{
      border:none;
      border-radius:16px;
      transition:.3s;
  }

  .stat-card:hover{
      transform:translateY(-5px);
  }

  .stat-icon{
      width:60px;
      height:60px;
      border-radius:15px;
      color:white;
      display:flex;
      align-items:center;
      justify-content:center;
      font-size:22px;
  }

  .card{
      border-radius:16px;
  }

  .card-header{
      background:white;
      border-bottom:1px solid #f1f1f1;
  }

    table.dataTable thead th,
    table.dataTable tbody td{
        white-space: nowrap;
    }

    .dtfc-fixed-left{
        background:#fff !important;
    }

    table.dataTable{
        width:100% !important;
    }

    .table-danger td{
    background-color:#f8d7da !important;
    }

    .table-success td{
        background-color:#d4edda !important;
    }
</style>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>iMaturing | Dashboard</title>

    <link rel="icon" href="assets/images/i.Phylon.png" type="image/x-icon">
    
    <!-- Google Font -->
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700">

    <!-- Font Awesome -->
    <link rel="stylesheet"
        href="plugins/fontawesome-free/css/all.min.css">

    <!-- AdminLTE -->
    <link rel="stylesheet"
      href="dist/css/adminlte.min.css">

    <!-- DataTables -->
    <link rel="stylesheet" href="plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">

    <link rel="stylesheet" href="plugins/datatables-responsive/css/responsive.bootstrap4.min.css">

    <!-- Fixed Columns -->
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.3.0/css/fixedColumns.bootstrap4.min.css"> 

</head>

<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

    <!-- NAVBAR -->
    <?php include 'header.php'; ?>

    <!-- CONTENT -->
    <div class="content-wrapper">

        <!-- HEADER -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>
                            Dashboard
                        </h1>
                    </div>

                    <div class="col-sm-6 text-right">
                        
                            <h5> <?= date('d F Y'); ?></h5>
                        
                    </div>
                </div>
            </div>
        </section>

        <!-- CONTENT -->
        <section class="content">
            <div class="container-fluid">


                <div class="row">

                    <div class="col-md-12">

                        <div class="card card-info">

                            <div class="card-header">

                                <h3 class="card-title">
                                    Dashboard Status
                                </h3>

                            </div>

                            <div class="card-body">
                                <div>
                                    <table id="tblDashboard"
                                        class="table table-bordered table-striped">
                                        <thead>
                                            <tr>
                                                <th>No</th>
                                                <th>Style</th>
                                                <th>Model</th>
                                                <th>Colour</th>
                                                <th>MCS</th>
                                                <th>Category</th>
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
                                                <th>Kg</th>
                                                <th>Status</th>
                                                <th>Note</th>

                                            </tr>

                                        </thead>

                                        <tbody>
                                            <?php
                                            $no=1;
                                            while($row=mysqli_fetch_assoc($getPlanning))
                                            {
                                            ?>

                                            <tr
                                                <?php
                                                if($row['status']=='Rejected'){
                                                    echo 'class="table-danger"';
                                                }
                                                elseif($row['status']=='Approved'){
                                                    echo 'class="table-success"';
                                                }
                                                ?>
                                                >
                                            <td><?= $no++; ?></td>
                                            <td><?= $row['style']; ?></td>
                                            <td><?= $row['model']; ?></td>
                                            <td><?= $row['colour']; ?></td>
                                            <td><?= $row['mcs']; ?></td>
                                            <td><?= $row['category']; ?></td>

                                            <td><?= $row['size_1'] ?? 0 ?></td>
                                            <td><?= $row['size_1T'] ?? 0 ?></td>
                                            <td><?= $row['size_2'] ?? 0 ?></td>
                                            <td><?= $row['size_2T'] ?? 0 ?></td>
                                            <td><?= $row['size_3'] ?? 0 ?></td>
                                            <td><?= $row['size_3T'] ?? 0 ?></td>
                                            <td><?= $row['size_4'] ?? 0 ?></td>
                                            <td><?= $row['size_4T'] ?? 0 ?></td>
                                            <td><?= $row['size_5'] ?? 0 ?></td>
                                            <td><?= $row['size_5T'] ?? 0 ?></td>
                                            <td><?= $row['size_6'] ?? 0 ?></td>
                                            <td><?= $row['size_6T'] ?? 0 ?></td>
                                            <td><?= $row['size_7'] ?? 0 ?></td>
                                            <td><?= $row['size_7T'] ?? 0 ?></td>
                                            <td><?= $row['size_8'] ?? 0 ?></td>
                                            <td><?= $row['size_8T'] ?? 0 ?></td>
                                            <td><?= $row['size_9'] ?? 0 ?></td>
                                            <td><?= $row['size_9T'] ?? 0 ?></td>
                                            <td><?= $row['size_10'] ?? 0 ?></td>
                                            <td><?= $row['size_10T'] ?? 0 ?></td>
                                            <td><?= $row['size_11'] ?? 0 ?></td>
                                            <td><?= $row['size_11T'] ?? 0 ?></td>
                                            <td><?= $row['size_12'] ?? 0 ?></td>
                                            <td><?= $row['size_12T'] ?? 0 ?></td>
                                            <td><?= $row['size_13'] ?? 0 ?></td>
                                            <td><?= $row['size_13T'] ?? 0 ?></td>
                                            <td><?= $row['size_14'] ?? 0 ?></td>
                                            <td><?= $row['size_14T'] ?? 0 ?></td>
                                            <td><?= $row['size_15'] ?? 0 ?></td>

                                            <td><?= $row['kg']; ?></td>
                                            <td class="text-center">
                                                <?php if($row['status']=='Approved'){ ?>
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check"></i>
                                                    Approved
                                                </span>
                                                <?php }elseif($row['status']=='Rejected'){ ?>
                                                <span class="badge badge-danger">
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

                                            <td>
                                                <?php
                                                if($row['status']=='Rejected')
                                                {
                                                    echo htmlspecialchars($row['reject_reason']);
                                                }
                                                elseif($row['status']=='Approved')
                                                {
                                                    echo "Ready";
                                                }
                                                else
                                                {
                                                    echo "-";
                                                }
                                                ?>
                                                </td>
                                            </tr>

                                            <?php } ?>

                                        </tbody>
                                    </table>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
    </div>

    <!-- FOOTER -->
  <footer class="main-footer">
    <div class="float-right d-none d-sm-block">
      <b>Version</b> 1.0.0
    </div>2024 
    <strong><a href="#">Mfg Project Officer</a>.</strong> All rights reserved.
  </footer>

</div>

<script src="plugins/jquery/jquery.min.js"></script>
<script src="plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="plugins/datatables/jquery.dataTables.min.js"></script>
<script src="plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
<script src="https://cdn.datatables.net/fixedcolumns/4.3.0/js/dataTables.fixedColumns.min.js"></script>
<script src="dist/js/adminlte.min.js"></script>


<script>

</script>

<script>
    $('#tblDashboard').DataTable({

        responsive:false,

        scrollX:true,
        scrollY:"550px",
        scrollCollapse:true,

        paging:true,
        searching:true,
        ordering:true,

        autoWidth:false,

        fixedColumns:{
            leftColumns:6
        }

    });

    window.addEventListener('load', function () {

        if (window.location.search !== '') {
            window.history.replaceState({}, document.title, window.location.pathname);
        }

    });
</script>

</body>
</html>