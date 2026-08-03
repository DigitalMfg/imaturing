<?php
require 'function.php';

$id = $_GET['id'] ?? 0;

// ==========================
// AMBIL DATA DETAIL
// ==========================
$q = mysqli_query($conn,"
    SELECT *
    FROM tbl_request_detail
    WHERE id='$id'
");

$data = mysqli_fetch_assoc($q);

if(!$data){
    echo "<div class='alert alert-danger'>Data tidak ditemukan.</div>";
    exit;
}
?>

<table class="table table-bordered">

    <tr>
        <th width="180">Bucket</th>
        <td><?= $data['bucket']; ?></td>
    </tr>

    <tr>
        <th>Style</th>
        <td><?= $data['style']; ?></td>
    </tr>

    <tr>
        <th>Model</th>
        <td><?= $data['model']; ?></td>
    </tr>

    <tr>
        <th>Crafted</th>
        <td><?= $data['crafted']; ?></td>
    </tr>

    <tr>
        <th>Shift</th>
        <td><?= $data['shift']; ?></td>
    </tr>

    <tr>
        <th width="180">Colour</th>
        <td><?= $data['colour']; ?></td>
    </tr>

    <tr>
        <th>MCS</th>
        <td><?= $data['mcs']; ?></td>
    </tr>

    <tr>
        <th>Kg</th>
        <td><?= number_format($data['kg'],2); ?> Kg</td>
    </tr>

</table>

<hr>

<h5>
    <b>Size Run</b>
</h5>

<?php

//=========================
// AMBIL SIZE RUN
//=========================
$qSize = mysqli_query($conn,"
    SELECT
        size,
        qty
    FROM tbl_request_size
    WHERE detail_id = '$id'
");

// Simpan ke array
$sizeRun = [];

while($row = mysqli_fetch_assoc($qSize)){
    $sizeRun[$row['size']] = $row['qty'];
}

// Hitung total qty size
$totalQty = array_sum($sizeRun);

// Size yang tampil sesuai input
$sizes = array_keys($sizeRun);

// Untuk menampilkan semua size dari 1 sampai 15, termasuk T (Toddler)
// $sizes = [
//     '1','1T','2','2T','3','3T','4','4T','5','5T',
//     '6','6T','7','7T','8','8T','9','9T',
//     '10','10T','11','11T','12','12T',
//     '13','13T','14','14T','15'
// ];

?>

<div class="table-responsive">
    <table class="table table-bordered table-sm text-center">
        <thead class="bg-light">
            <tr>
                <?php foreach($sizes as $size){ ?>
                    <th><?= $size ?></th>
                <?php } ?>
                <th>Total</th>
            </tr>
        </thead>


        <tbody>
            <tr>
                <?php foreach($sizes as $size){ ?>
                    <td>
                        <?= $sizeRun[$size]; ?>
                    </td>
                <?php } ?>


                <td class="font-weight-bold">
                    <?= $totalQty; ?>
                </td>

            </tr>
        </tbody>
    </table>
</div>