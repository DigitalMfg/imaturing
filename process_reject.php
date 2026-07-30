<?php

require 'function.php';

$id = $_POST['id'];

$username = $_SESSION['username'];
$nik      = $_SESSION['nik'];

$reason = mysqli_real_escape_string($conn,$_POST['reason']);

mysqli_query($conn,"
UPDATE tbl_request_detail
SET
    status='Rejected',
    reject_reason='$reason',
    reject_by='$username',
    reject_nik='$nik',
    reject_date=NOW()
WHERE id='$id'
");

echo 1;