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

  // Get data Bucket
  $bucket = query("SELECT DISTINCT(bucket) FROM tbl_master_barcode ;");

  // Get data style
  $style = query("SELECT DISTINCT(style) FROM tbl_master_barcode ;");
  
  // Get data PO
  $po = query("SELECT DISTINCT(po) FROM tbl_master_barcode ;");

  // Get data PO Item
  $po_item = query("SELECT DISTINCT(po_item) FROM tbl_master_barcode ;");

  // Input data Regis data User
  if (isset($_POST['registerUser'])){
     
      $username = htmlspecialchars (ucwords($_POST ["username"])); 
      $nik = htmlspecialchars (ucwords($_POST ["nik"]));  
      $password = htmlspecialchars($_POST ["password"]);
      $authorize = htmlspecialchars($_POST ["authorize"]);
      $scan_type = htmlspecialchars($_POST ["scan_type"]);
      $cost_center = htmlspecialchars($_POST ["cost_center"]);

    //query insert data Regis
      $cek = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tbl_user WHERE nik='$nik' LIMIT 1"));
        if ($cek > 0){
          $_SESSION['status_nik'] = "Nik sudah terdaftar";
          header('location: registerUser.php');
          exit;
        }else {
            
      
      $regis = "INSERT INTO tbl_user (username, nik, password, authorize, scan_type, cost_center) 
      VALUES ('$username','$nik','$password','$authorize','$scan_type','$cost_center')";
 
       $query_regis = mysqli_query($conn, $regis);
        
       if($query_regis)
       {
           $_SESSION['registered'] = "Data registered Successfully";
           header('location: registerUser.php');
           exit;
       } else {
           echo "Error: " . mysqli_error($conn);
       }
    }
   
  }

   // Regis ID card
    if (isset($_POST['registerIDCard'])){

     $username = htmlspecialchars (ucwords($_POST ["username"]));  
     $nik = htmlspecialchars (ucwords($_POST ["nik"]));  
     $cost_center = htmlspecialchars (ucwords($_POST ["cost_center"]));  
     $no_idCard = htmlspecialchars($_POST ["no_idCard"]); 
    
     //query insert data Regis
     $cek = mysqli_num_rows(mysqli_query($conn,"SELECT * FROM tbl_idcard WHERE no_idCard='$no_idCard'"));
     if ($cek > 0){
       $_SESSION['IDCardDouble'] = "ID card sudah terdaftar";
       header('location: registerIDcard.php');
       exit;
     }else {
         
 //enkripsi password
   // $password = password_hash($password, PASSWORD_DEFAULT);
   
   $regis = "INSERT INTO tbl_idcard (username, nik, cost_center, no_idCard)  
   VALUES ('$username','$nik','$cost_center','$no_idCard')";

    $query_regis = mysqli_query($conn, $regis);
     
    if($query_regis)
    {
        $_SESSION['IDCardSuccess'] = "Data registered Successfully";
        header('location: registerIDcard.php');
        exit;
    } else {
        echo "error data";
    }
 }
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
  
  // Ambil data Master Time
  $MasterTime = query("SELECT * FROM tbl_master_time ORDER BY id_time DESC");

  // Transaction Scan in preform
  if (isset($_POST['inPreform'])) {
    $qr_code     = htmlspecialchars(trim($_POST["qr_code"]));
    $nik         = htmlspecialchars(trim($_POST["nik"])); 
    $username    = htmlspecialchars(trim($_POST["username"])); 
    $type_scan   = htmlspecialchars(trim($_POST["type_scan"]));
    $cost_center = htmlspecialchars(trim($_POST["cost_center"]));
    $hour_scan   = htmlspecialchars(trim($_POST["hour_scan"]));
    $shift       = htmlspecialchars(trim($_POST["shift"]));
    $date_scan   = htmlspecialchars(trim($_POST["date_scan"]));

    // STEP 1: Cek QR & status scan
    $stmt = $conn->prepare("
        SELECT 
            mb.qr_code,
            mb.bucket,
            mb.style,
            COALESCE(ts.type_scan, '') AS type_scan
        FROM tbl_master_barcode mb
        LEFT JOIN tbl_transaction_scan ts
            ON mb.qr_code = ts.qr_code AND ts.type_scan = 'IN_Preform'
        WHERE mb.qr_code = ?
        LIMIT 1
    ");

    if (!$stmt) {
        die("Prepare failed (master check): " . $conn->error);
    }

    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['not_data_qr'] = "QR Code tidak ditemukan di master barcode!";
        header('Location: in preform.php');
        exit;
    }

    $row = $result->fetch_assoc();

    if ($row['type_scan'] === 'IN_Preform') {
        $_SESSION['status_double_scan'] = "QR Code sudah pernah discan IN Preform!";
        header('Location: in preform.php');
        exit;
    }

    $bucket = trim($row['bucket']);
    $style  = trim($row['style']);
    $stmt->close();

    // STEP 2: Validasi di hourly plan + TANGGAL
    $stmt = $conn->prepare("
        SELECT 1
        FROM tbl_hourly_plan
        WHERE TRIM(cost_center) = TRIM(?)
        AND TRIM(bucket) = TRIM(?)
        AND TRIM(style) = TRIM(?)
        AND date_plan = ?
        LIMIT 1
    ");

    if (!$stmt) {
        die("Prepare failed (hourly plan check): " . $conn->error);
    }

    $stmt->bind_param("ssss", $cost_center, $bucket, $style, $date_scan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows === 0) {
        $_SESSION['not_match_plan'] = "Kombinasi Cost Center, Bucket, Style & Tanggal tidak ditemukan di Hourly Plan!";
        header('Location: in preform.php');
        exit;
    }

    $stmt->close();

    // STEP 3: Simpan ke transaction
    $stmt = $conn->prepare("
        INSERT INTO tbl_transaction_scan 
        (qr_code, nik, username, type_scan, cost_center, hour_scan, shift, date_scan)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
    ");

    if (!$stmt) {
        die("Prepare failed (insert): " . $conn->error);
    }

    $stmt->bind_param("ssssssss", $qr_code, $nik, $username, $type_scan, $cost_center, $hour_scan, $shift, $date_scan);

    if ($stmt->execute()) {
        $_SESSION['inPreform'] = "Scan IN Preform berhasil disimpan.";
        header('Location: in preform.php');
        exit;
    } else {
        echo "Gagal menyimpan data scan: " . $stmt->error;
    }

    $stmt->close();
}


// Transaction Scan in press
if (isset($_POST['inPress'])) {
    $qr_code     = htmlspecialchars($_POST["qr_code"]);
    $nik         = htmlspecialchars($_POST["nik"]);
    $username    = htmlspecialchars($_POST["username"]);
    $type_scan   = htmlspecialchars($_POST["type_scan"]);
    $cost_center = htmlspecialchars($_POST["cost_center"]);
    $hour_scan   = htmlspecialchars($_POST["hour_scan"]);
    $shift       = htmlspecialchars($_POST["shift"]);
    $date_scan   = htmlspecialchars($_POST["date_scan"]);

    // Gunakan LEFT JOIN untuk ambil data IN_Press dan IN_Preform sekaligus
    $stmt = $conn->prepare("
        SELECT 
            press.qr_code AS scanned_press,
            preform.qr_code AS valid_preform
        FROM 
            (SELECT ? AS qr_code) AS q
        LEFT JOIN tbl_transaction_scan AS press 
            ON press.qr_code = q.qr_code AND press.type_scan = 'IN_Press'
        LEFT JOIN tbl_transaction_scan AS preform 
            ON preform.qr_code = q.qr_code AND preform.type_scan = 'IN_Preform' AND preform.cost_center = ?
        LIMIT 1
    ");
    $stmt->bind_param("ss", $qr_code, $cost_center);
    $stmt->execute();
    $stmt->bind_result($scanned_press, $valid_preform);
    $stmt->fetch();
    $stmt->close();

    if (!is_null($scanned_press)) {
        $_SESSION['double_scanIN'] = "QR Code has been scan IN Press";
        header('location: in press.php');
        exit;
    }

    if (!is_null($valid_preform)) {
        // Insert data IN_Press
        $stmt = $conn->prepare("INSERT INTO tbl_transaction_scan 
            (qr_code, nik, username, type_scan, cost_center, hour_scan, shift, date_scan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssss", $qr_code, $nik, $username, $type_scan, $cost_center, $hour_scan, $shift, $date_scan);

        if ($stmt->execute()) {
            $_SESSION['status_scan_in'] = "Scan IN Press successfully";
            header('location: in press.php');
            exit;
        } else {
            echo "Error inserting data: " . $stmt->error;
        }
    } else {
        $_SESSION['status_unscan'] = "QR code not scan In Preform / Crafted not match";
        header('location: in press.php');
        exit;
    }
}

// Transaction Scan out press
if (isset($_POST['OutPress'])) {
    $qr_code     = htmlspecialchars($_POST["qr_code"]);
    $nik         = htmlspecialchars($_POST["nik"]);
    $username    = htmlspecialchars($_POST["username"]);
    $type_scan   = htmlspecialchars($_POST["type_scan"]);
    $cost_center = htmlspecialchars($_POST["cost_center"]);
    $hour_scan   = htmlspecialchars($_POST["hour_scan"]);
    $shift       = htmlspecialchars($_POST["shift"]);
    $date_scan   = htmlspecialchars($_POST["date_scan"]);

    // Prepared statement untuk cek apakah QR sudah OUT dan validasi pernah IN
    $stmt = $conn->prepare("
        SELECT 
            outpress.qr_code AS scanned_out,
            inpress.qr_code AS valid_in
        FROM 
            (SELECT ? AS qr_code) AS q
        LEFT JOIN tbl_transaction_scan AS outpress 
            ON outpress.qr_code = q.qr_code AND outpress.type_scan = 'OUT_Press'
        LEFT JOIN tbl_transaction_scan AS inpress 
            ON inpress.qr_code = q.qr_code AND inpress.type_scan = 'IN_Press' AND inpress.cost_center = ?
        LIMIT 1
    ");
    $stmt->bind_param("ss", $qr_code, $cost_center);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['scanned_out']) {
        $_SESSION['double_scanOUT'] = "QR Code has been scan OUT Press";
        header('location: out press.php');
        exit;
    } elseif ($data['valid_in']) {
        $insert = $conn->prepare("
            INSERT INTO tbl_transaction_scan 
                (qr_code, nik, username, type_scan, cost_center, hour_scan, shift, date_scan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param("ssssssss", $qr_code, $nik, $username, $type_scan, $cost_center, $hour_scan, $shift, $date_scan);
        
        if ($insert->execute()) {
            $_SESSION['status_scan_out'] = "Scan Out Press successfully";
            header('location: out press.php');
            exit;
        } else {
            echo "Error: Gagal insert data.";
        }
    } else {
        $_SESSION['status_unscan'] = "QR code not scan In Press / Crafted not match";
        header('location: out press.php');
        exit;
    }
}


// Transaction Scan IN SM-Rubber
if (isset($_POST['inSMRubber'])) {
    $qr_code     = htmlspecialchars($_POST["qr_code"]);
    $nik         = htmlspecialchars($_POST["nik"]);
    $username    = htmlspecialchars($_POST["username"]);
    $type_scan   = htmlspecialchars($_POST["type_scan"]);
    $cost_center = htmlspecialchars($_POST["cost_center"]);
    $hour_scan   = htmlspecialchars($_POST["hour_scan"]);
    $shift       = htmlspecialchars($_POST["shift"]);
    $date_scan   = htmlspecialchars($_POST["date_scan"]);

    // Gabung dua pengecekan: sudah IN_SMRubber dan validasi pernah OUT_Press
    $stmt = $conn->prepare("
        SELECT 
            inrub.qr_code AS scanned_in,
            outpress.qr_code AS valid_out
        FROM 
            (SELECT ? AS qr_code) AS q
        LEFT JOIN tbl_transaction_scan AS inrub 
            ON inrub.qr_code = q.qr_code AND inrub.type_scan = 'IN_SMRubber'
        LEFT JOIN tbl_transaction_scan AS outpress 
            ON outpress.qr_code = q.qr_code AND outpress.type_scan = 'OUT_Press'
        LIMIT 1
    ");
    $stmt->bind_param("s", $qr_code);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['scanned_in']) {
        $_SESSION['double_scanIN'] = "QR Code has been scan IN SM-Rubber";
        header('location: in smrubber.php');
        exit;
    } elseif ($data['valid_out']) {
        $insert = $conn->prepare("
            INSERT INTO tbl_transaction_scan 
                (qr_code, nik, username, type_scan, cost_center, hour_scan, shift, date_scan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param("ssssssss", $qr_code, $nik, $username, $type_scan, $cost_center, $hour_scan, $shift, $date_scan);
        
        if ($insert->execute()) {
            $_SESSION['status_scan_in'] = "Scan IN SM-Rubber successfully";
            header('location: in smrubber.php');
            exit;
        } else {
            echo "Error inserting data.";
        }
    } else {
        $_SESSION['status_unscan'] = "QR Code not Scan OUT Press";
        header('location: in smrubber.php');
        exit;
    }
}


// Transaction Scan OUT SM-Rubber
if (isset($_POST['outSMRubber'])) {
    $qr_code     = htmlspecialchars($_POST["qr_code"]);
    $nik         = htmlspecialchars($_POST["nik"]);
    $username    = htmlspecialchars($_POST["username"]);
    $type_scan   = htmlspecialchars($_POST["type_scan"]);
    $cost_center = htmlspecialchars($_POST["cost_center"]);
    $hour_scan   = htmlspecialchars($_POST["hour_scan"]);
    $shift       = htmlspecialchars($_POST["shift"]);
    $date_scan   = htmlspecialchars($_POST["date_scan"]);

    // Gabung cek apakah sudah scan OUT dan pernah scan IN SMRubber
    $stmt = $conn->prepare("
        SELECT 
            outsm.qr_code AS scanned_out,
            insm.qr_code AS valid_in
        FROM 
            (SELECT ? AS qr_code) AS q
        LEFT JOIN tbl_transaction_scan AS outsm 
            ON outsm.qr_code = q.qr_code AND outsm.type_scan = 'OUT_SMRubber'
        LEFT JOIN tbl_transaction_scan AS insm 
            ON insm.qr_code = q.qr_code AND insm.type_scan = 'IN_SMRubber' AND insm.cost_center = ?
        LIMIT 1
    ");
    $stmt->bind_param("ss", $qr_code, $cost_center);
    $stmt->execute();
    $result = $stmt->get_result();
    $data = $result->fetch_assoc();

    if ($data['scanned_out']) {
        $_SESSION['double_scanOUTSM'] = "QR Code has been scan OUT SM-Rubber";
        header('location: out smrubber.php');
        exit;
    } elseif ($data['valid_in']) {
        $insert = $conn->prepare("
            INSERT INTO tbl_transaction_scan 
                (qr_code, nik, username, type_scan, cost_center, hour_scan, shift, date_scan)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?)
        ");
        $insert->bind_param("ssssssss", $qr_code, $nik, $username, $type_scan, $cost_center, $hour_scan, $shift, $date_scan);
        
        if ($insert->execute()) {
            $_SESSION['status_scan_out_SM'] = "Scan OUT SM-Rubber successfully";
            header('location: out smrubber.php');
            exit;
        } else {
            echo "Error inserting data.";
        }
    } else {
        $_SESSION['status_unscan'] = "QR Code not Scan IN SM-Rubber / Crafted not match";
        header('location: out smrubber.php');
        exit;
    }
}


  
// Format Shift
if ($tanggal == $tgl_jumat && $siftHour >='07:01' && $siftHour <= '16:30'){
  $sift = '1';
} elseif ($tanggal != $tgl_jumat && $siftHour >='07:01' && $siftHour <= '16:00'){
    $sift = '1';
} elseif ($tanggal == $tgl_jumat && $siftHour >='16:01'){
  $sift = '2';
} elseif ($tanggal != $tgl_jumat && $siftHour >='16:01'){
  $sift = '2';
} elseif ($tanggal == $tgl_jumat && $siftHour >='00:01'){
  $sift = '3';
} elseif ($tanggal != $tgl_jumat && $siftHour >='00:01'){
  $sift = '3';
}


?>