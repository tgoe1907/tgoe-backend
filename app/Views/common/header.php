<?php
use App\Libraries\CIHelper;
?>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/img/favicon.png">
    <title>TGÖ Backend Home</title>
    
    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="/adminlte/plugins/fontawesome-free/css/all.min.css">
    <!-- overlayScrollbars -->
    <link rel="stylesheet" href="/adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="/adminlte/dist/css/adminlte.min.css">
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<!-- Site wrapper -->
<div class="wrapper">
  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>

    <!-- Right navbar links -->
    <ul class="navbar-nav ml-auto">

    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="/" class="brand-link">
      <img src="/img/logo.png" alt="TGÖ Logo" style="width: 50%; height: auto;">
      <span class="brand-text font-weight-light">TGÖ Services</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <?php 
      if( session()->get('userdata') !== null ) {
          ?>
          <!-- Sidebar user (optional) -->
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <img src="/img/loggedin-avatar.png" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
              <a href="/logout"><?= esc(session()->get('userdata')->getFullName())?> <i class="nav-icon fas fa-sign-out-alt"></i></a>
            </div>
          </div>
          <?php 
      }
      ?>

      <!-- Sidebar Menu -->
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php 
        foreach( $ci->getMenuitems() as $key => $menuItemInfo) {
            if( $menuItemInfo[0] === null ) {
                ?>
                <li class="nav-header"><?= esc($menuItemInfo[1]) ?></li>
                <?php 
            }
            else
            {
                $isActive = $key == $ci->getActiveMenuItem();
                ?>
                    <li class="nav-item">
                    <a href="<?= esc($menuItemInfo[2]) ?>" class="nav-link <?= $isActive?"active":"" ?>">
                      <i class="nav-icon <?= esc($menuItemInfo[0]) ?>"></i>
                      <p>
                        <?= esc($menuItemInfo[1]) ?>
                      </p>
                    </a>
                    </li>
        		<?php 
            }
        }
        ?>
        </ul>
      </nav>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1><?= esc($ci->getHeadline()) ?></h1>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <?php
        if( $ci->hasMessages() ) {
        	foreach( $ci->getMessages() as $msg ) {
        	    switch( $msg[2] ) {
        	        case CIHelper::MSG_ERROR:
        	              $style = "alert-danger";
        	              $icon  = "fa-ban";
        	              break;
        	        case CIHelper::MSG_WARNING:
        	            $style = "alert-warning";
        	            $icon  = "fa-exclamation-triangle";
        	            break;
        	        case CIHelper::MSG_INFO:
        	            $style = "alert-info";
        	            $icon  = "fa-info";
        	            break;
        	        case CIHelper::MSG_SUCCESS:
        	            $style = "alert-success";
        	            $icon  = "fa-check";
        	            break;
        	    }
        	?>
            <div class="alert <?= $style ?> alert-dismissible">
              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
              <h5><i class="icon fas <?= $icon ?>"></i> <?= esc($msg[0]) ?></h5>
              <?= esc($msg[1]) ?>
            </div>
			<?php 
        	}
        }
        ?>

    <!-- HEADER ENDS HERE -->