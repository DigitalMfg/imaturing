<?php 
if (!function_exists('login_validate')) {
    session_start(); // ready to go!

    function login_validate() {
        $timeout = 28800;
        $_SESSION["expires_by"] = time() + $timeout;
    }

    function login_check() {
        $exp_time = $_SESSION["expires_by"];
        if (time() < $exp_time) {
            login_validate();
            return true;
        } else {
            unset($_SESSION["expires_by"]);
            return false;
        }
    }
}

  // Set Timezone
  date_default_timezone_set('Asia/Jakarta'); // Zona Waktu indonesia
  $hr_scan = date('H:i');
  $time_scan = "<p id='timePerHour'> </p>";
  $ref = date('H:i');
  $siftHour = date('H:i');
  // $manipul = mktime(23,31);
  // $hr_scan = date('H:i', $manipul);
  
  $tanggal      = date('Y-m-d');

  $tanggals      = date('l, Y-m-d');
  $tgl_sekarang = strtotime($tanggals);
  $tgl_jumat    = date('l, Y-m-d', strtotime("Friday", $tgl_sekarang));

  //Koneksi ke DBMS
$conn = mysqli_connect("localhost:3306","root","","db_imaturing");

  //mengambil data
  function query ($query){
  global $conn;
  $result = mysqli_query($conn, $query);
  $rows = [];
  while ($row = mysqli_fetch_assoc($result)) {
    $rows [] = $row;
  }
  return $rows;
  }


  // Input data Master Time
  if (isset($_POST['SumbitMasterTime'])){
     
    $date = htmlspecialchars ($_POST ["date"]); 
    $hour = htmlspecialchars ($_POST ["hour"]);  
    $shift = htmlspecialchars ($_POST ["shift"]);
    $time_start = htmlspecialchars ($_POST ["time_start"]);
    $time_end = htmlspecialchars ($_POST ["time_end"]);

  //query insert data Master Time
    $MasterTime = "INSERT INTO tbl_master_time (date, hour, shift, time_start, time_end) 
    VALUES ('$date','$hour','$shift','$time_start','$time_end')";

    $query_MasterTime = mysqli_query($conn, $MasterTime);
    
    if($query_MasterTime)
    {
        $_SESSION['Sumbited'] = "Data berhasil di input Successfully";
        header('location: masterTime.php');
        exit;
    } else {
        echo "error data";
    }
  }


?>