<?php
require 'function.php';

$id = (int)$_POST['id'];

$model    = mysqli_real_escape_string($conn,$_POST['model']);
$style    = mysqli_real_escape_string($conn,$_POST['style']);
$bucket   = mysqli_real_escape_string($conn,$_POST['bucket']);
$crafted  = mysqli_real_escape_string($conn,$_POST['crafted']);
$shift    = mysqli_real_escape_string($conn,$_POST['shift']);
$colour   = mysqli_real_escape_string($conn,$_POST['colour']);
$mcs      = mysqli_real_escape_string($conn,$_POST['mcs']);
$category = mysqli_real_escape_string($conn,$_POST['category']);
$kg       = (float)$_POST['kg'];

mysqli_begin_transaction($conn);

try{

    // Update header/detail
    mysqli_query($conn,"
    UPDATE tbl_request_detail
    SET
        model='$model',
        style='$style',
        bucket='$bucket',
        crafted='$crafted',
        shift='$shift',
        colour='$colour',
        mcs='$mcs',
        category='$category',
        kg='$kg'
    WHERE id='$id'
    ");

    // Hapus size lama
    mysqli_query($conn,"
    DELETE FROM tbl_request_size
    WHERE detail_id='$id'
    ");

    // Simpan size baru
    if(isset($_POST['size_run'])){

        foreach($_POST['size_run'] as $size=>$qty){

            if($qty=='' || $qty<=0){
                continue;
            }

            $size = mysqli_real_escape_string($conn,$size);
            $qty  = (int)$qty;

            mysqli_query($conn,"
            INSERT INTO tbl_request_size
            (
                detail_id,
                size,
                qty
            )
            VALUES
            (
                '$id',
                '$size',
                '$qty'
            )
            ");
        }

    }

    mysqli_commit($conn);

    echo "success";

}catch(Exception $e){

    mysqli_rollback($conn);

    echo "error";

}