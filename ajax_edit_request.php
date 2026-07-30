<?php
require 'function.php';

$id = (int)$_GET['id'];

$q = mysqli_query($conn,"
SELECT *
FROM tbl_request_detail
WHERE id='$id'
");

$data = mysqli_fetch_assoc($q);

$size = [];

$getSize = mysqli_query($conn,"
SELECT size,qty
FROM tbl_request_size
WHERE detail_id='$id'
");

while($r=mysqli_fetch_assoc($getSize)){
    $size[$r['size']] = $r['qty'];
}

$sizes = [
'1','1T','2','2T','3','3T','4','4T',
'5','5T','6','6T','7','7T','8','8T',
'9','9T','10','10T','11','11T',
'12','12T','13','13T','14','14T','15'
];
?>

<input type="hidden" name="id" value="<?= $data['id']; ?>">

<div class="row">

    <div class="col-md-3">
        <label>Model</label>
        <input
            type="text"
            name="model"
            class="form-control"
            value="<?= htmlspecialchars($data['model']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>Style</label>
        <input
            type="text"
            name="style"
            class="form-control"
            value="<?= htmlspecialchars($data['style']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>Bucket</label>
        <input
            type="text"
            name="bucket"
            class="form-control"
            value="<?= htmlspecialchars($data['bucket']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>Crafted</label>
        <input
            type="text"
            name="crafted"
            class="form-control"
            value="<?= htmlspecialchars($data['crafted']) ?>"
            required>
    </div>

</div>

<br>

<div class="row">

    <div class="col-md-3">
        <label>Shift</label>
        <input
            type="text"
            name="shift"
            class="form-control"
            value="<?= htmlspecialchars($data['shift']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>Colour</label>
        <input
            type="text"
            name="colour"
            class="form-control"
            value="<?= htmlspecialchars($data['colour']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>MCS</label>
        <input
            type="text"
            name="mcs"
            class="form-control"
            value="<?= htmlspecialchars($data['mcs']) ?>"
            required>
    </div>

    <div class="col-md-3">
        <label>Category</label>

        <select
            name="category"
            class="form-control"
            required>

            <?php

            $cat = [
                'BON STOCKFIT',
                'BON ASEMBLING',
                'EXPORT',
                'RETURN',
                'PROMO',
                'MINUS BUCKET'
            ];

            foreach($cat as $c){

                $selected = ($data['category']==$c) ? 'selected' : '';

                echo "<option value='$c' $selected>$c</option>";

            }

            ?>

        </select>

    </div>

</div>

<hr>

<div class="table-responsive">

<table class="table table-bordered table-sm text-center">

    <thead>

        <tr>

            <?php foreach($sizes as $s){ ?>

                <th><?= $s ?></th>

            <?php } ?>

            <th>Kg</th>

        </tr>

    </thead>

    <tbody>

        <tr>

            <?php foreach($sizes as $s){ ?>

                <td>

                    <input
                        type="number"
                        class="form-control form-control-sm text-center"
                        name="size_run[<?= $s ?>]"
                        value="<?= isset($size[$s]) ? $size[$s] : '' ?>">

                </td>

            <?php } ?>

            <td>

                <input
                    type="number"
                    step="0.01"
                    class="form-control form-control-sm text-center"
                    name="kg"
                    value="<?= $data['kg'] ?>">

            </td>

        </tr>

    </tbody>

</table>

</div>