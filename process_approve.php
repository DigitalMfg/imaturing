<?php

require 'function.php';

$id = $_POST['id'];

$username = $_SESSION['username'];
$nik      = $_SESSION['nik'];

mysqli_query($conn,"
UPDATE tbl_request_detail
SET
    status='Approved',
    approve_by='$username',
    approve_nik='$nik',
    approve_date=NOW()
WHERE id='$id'
");

echo 1;