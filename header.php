<?php
// session_start(); 

if (!isset($_SESSION['login'])) {

    header("Location: login.php");
    exit;

}

$authorize = $_SESSION['authorize'];
$scan_type = $_SESSION['scan_type'];
?>

<!-- NAVBAR -->
<nav class="main-header navbar navbar-expand navbar-white navbar-light">
  <!-- LEFT NAVBAR -->
  <ul class="navbar-nav">
    <li class="nav-item">
      <a class="nav-link"
      data-widget="pushmenu"
      href="#"
      role="button">

        <i class="fas fa-bars"></i>
      </a>
    </li>
  </ul>

  <!-- RIGHT NAVBAR -->
  <ul class="navbar-nav ml-auto">
    <li class="nav-item">
      <a class="nav-link">
        <i class="fas fa-user-circle"></i>
        <?= $_SESSION['username']; ?>
      </a>
    </li>

    <li class="nav-item">
      <a class="nav-link"
      data-widget="fullscreen"
      href="#"
      role="button">

        <i class="fas fa-expand-arrows-alt"></i>
      </a>
    </li>
  </ul>
</nav>

<!-- SIDEBAR -->
<aside class="main-sidebar sidebar-dark-primary elevation-4">

  <!-- LOGO -->
  <a href="index.php"
  class="brand-link">

    <img src="dist/img/logo.png"
    alt="Logo"
    class="brand-image img-circle elevation-3">
    <span class="brand-text font-weight-light">
      iMaturing
    </span>
  </a>

  <!-- SIDEBAR -->
  <div class="sidebar">

    <!-- MENU -->
    <nav class="mt-2">

      <ul class="nav nav-pills nav-sidebar flex-column"
      data-widget="treeview"
      role="menu"
      data-accordion="false">

        <!-- DASHBOARD -->
         <li class="nav-header">
          HOME
        </li>
        <li class="nav-item">
          <a href="#"
          class="nav-link">
            <i class="nav-icon fas fa-home"></i>
            <p>
              Dashboard
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="dashboard.php"
              class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Maturing
                </p>
              </a>
            </li>
          </ul>
        </li>

       <li class="nav-header">
            TRANSACTION
          </li>

          <li class="nav-item">
            <a href="#"
            class="nav-link">

              <i class="nav-icon fas fa-book"></i>
              <p>
                Input Data
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="spk_planning.php"
                class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    SPK Planning
                  </p>
                </a>
              </li>  
            </ul>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="qa_process.php"
                class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    QA Process
                  </p>
                </a>
              </li>  
            </ul>

            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="Inventory.php"
                class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p>
                    Inventory
                  </p>
                </a>
              </li>  
            </ul>
          </li>
        

        <!-- MASTER -->
        <li class="nav-header">
          SETTING
        </li>

        <li class="nav-item">
          <a href="#"
          class="nav-link">
            <i class="nav-icon fas fa-cogs"></i>
            <p>
              Master
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="master_time.php"
              class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Plan Time
                </p>
              </a>
            </li>

            <li class="nav-item">
              <a href="master_user.php"
              class="nav-link">

                <i class="far fa-circle nav-icon"></i>
                <p>
                  User
                </p>
              </a>
            </li>
          </ul>
        </li>

        

        <!-- ===================================== -->
        <!-- USER OUT PACKING -->
        <!-- ===================================== -->
        <?php if($authorize == "User" && $scan_type == "OUT_PACKING") : ?>

        <li class="nav-header">
          TRANSACTIONS
        </li>

        <li class="nav-item">
          <a href="#"
          class="nav-link">
            <i class="nav-icon fas fa-box"></i>
            <p>
              Packing Production
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="out_packing.php"
              class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Scan Out
                </p>
              </a>
            </li>
          </ul>
        </li>
        <?php endif; ?>

        <!-- ===================================== -->
        <!-- USER IN SM -->
        <!-- ===================================== -->
        <?php if($authorize == "User" && $scan_type == "IN_SM") : ?>

        <li class="nav-header">
          TRANSACTIONS
        </li>

        <li class="nav-item">
          <a href="#"
          class="nav-link">
            <i class="nav-icon fas fa-warehouse"></i>
            <p>
              Supermarket
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="in_sm.php"
              class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Scan In
                </p>
              </a>
            </li>
          </ul>
        </li>

        <?php endif; ?>

        <!-- ===================================== -->
        <!-- USER OUT SM -->
        <!-- ===================================== -->
        <?php if($authorize == "User" && $scan_type == "OUT_SM") : ?>

        <li class="nav-header">
          TRANSACTIONS
        </li>

        <li class="nav-item">
          <a href="#"
          class="nav-link">

            <i class="nav-icon fas fa-warehouse"></i>
            <p>
              Supermarket
              <i class="fas fa-angle-left right"></i>
            </p>
          </a>

          <ul class="nav nav-treeview">
            <li class="nav-item">
              <a href="out_sm.php"
              class="nav-link">
                <i class="far fa-circle nav-icon"></i>
                <p>
                  Scan Out
                </p>
              </a>
            </li>
          </ul>
        </li>

        <?php endif; ?>

        <!-- LOGOUT -->
        <li class="nav-header">
          LOGOUT
        </li>
        <li class="nav-item">
          <a href="logout.php"
          class="nav-link">
            <i class="nav-icon fas fa-sign-out-alt"></i>
            <p>
              Sign Out
            </p>
          </a>
        </li>
      </ul>
    </nav>
  </div>
</aside>