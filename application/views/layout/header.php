<!DOCTYPE html>
<html lang="en">

<head>
    <!-- HTML5 Shim and Respond.js IE11 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 11]>
    	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
    	<![endif]-->
    <!-- Meta -->
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title><?php echo $this->customlib->getAppName(); ?></title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <meta http-equiv="Cache-control" content="no-cache">
    <meta name="theme-color" content="#005331" />

    <link href="<?php echo base_url(); ?>assets/images/favicon.ico" rel="shortcut icon" type="image/x-icon">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/daterangepicker.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/font-awesome.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/plugins/select2.min.css">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/print.min.css">

    <!--print table-->
    <link href="<?php echo base_url(); ?>assets/datatables/css/jquery.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatables/css/buttons.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatables/css/dataTables.bootstrap.min.css" rel="stylesheet">
    <!--print table mobile support-->
    <link href="<?php echo base_url(); ?>assets/datatables/css/responsive.dataTables.min.css" rel="stylesheet">
    <link href="<?php echo base_url(); ?>assets/datatables/css/rowReorder.dataTables.min.css" rel="stylesheet">

    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/customs.css">

    <script src="<?php echo base_url(); ?>assets/custom/jquery.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/print.min.js"></script>

</head>

<body class="background-blue">
    <!-- [ Pre-loader ] start -->
    <div class="loader-bg">
        <div class="loader-track">
            <div class="loader-fill"></div>
        </div>
    </div>
    <!-- [ Pre-loader ] End -->
    <!-- [ navigation menu ] start -->
    <nav class="pcoded-navbar menu-light menupos-fixed">
        <div class="navbar-wrapper  ">
            <div class="navbar-content scroll-div ">
                <?php
                $file   = "";
                $result = $this->customlib->getUserData();

                $image = $result["image"];
                $role  = $result["user_type"];
                $id    = $result["id"];
                if (!empty($image)) {

                    $file = "uploads/user_images/" . $image;
                } else {

                    $file = "uploads/user_images/avatar.jpg";
                }
                ?>
                <div class="">
                    <div class="main-menu-header">
                        <img class="img-radius" src="<?php echo base_url() . $file; ?>" alt="User-Profile-Image">
                        <div class="user-details">
                            <div id="more-details"><?php echo $this->config->item('app_name_lite'); ?>
                                <?php echo strtoupper($this->customlib->getSessionCabangName()); ?> <i
                                    class="fa fa-caret-down"></i></div>
                        </div>
                    </div>
                    <div class="collapse" id="nav-user-link">
                        <ul class="list-inline">
                            <li class="list-inline-item"><a href="<?php echo base_url(); ?>myprofile"
                                    data-toggle="tooltip" title="View Profile"><i class="feather icon-user"></i></a>
                            </li>
                            <!-- <li class="list-inline-item"><a href="email_inbox.html"><i class="feather icon-mail" data-toggle="tooltip" title="Messages"></i><small class="badge badge-pill badge-primary">5</small></a></li> -->
                            <li class="list-inline-item"><a href="<?php echo base_url(); ?>auth2/logout"
                                    data-toggle="tooltip" title="Logout" class="text-danger"><i
                                        class="feather icon-power"></i></a></li>
                        </ul>
                    </div>
                </div>
                <br>

                <?php $this->load->view('layout/sidebar'); ?>

            </div>
        </div>
    </nav>

    <header class="navbar pcoded-header navbar-expand-lg navbar-light header-blue">

        <div class="m-header">
            <a class="mobile-menu" id="mobile-collapse" href="#!"><span></span></a>
            <a href="#!" class="b-brand">
                <!-- ========   change your logo hear   ============ -->
                <img src="<?php echo base_url(); ?>assets/images/simkatmuh-logo.jpg" height="28px" alt="" class="logo">
                <h3 style="margin-top:10px;color:#fff;">SIPPMU</h3>
            </a>
            <a href="#!" class="mob-toggler">
                <i class="feather icon-more-vertical"></i>
            </a>

        </div>
        <div class="collapse navbar-collapse">

            <ul class="navbar-nav ml-auto">

                <li class="nav-item top-org" style="">
                    <img src="<?php echo base_url(); ?>assets/images/muhammadiyah-logo.jpg" height="28px" alt=""
                        class="logo">
                    <?php echo strtoupper($this->customlib->getSessionCabangName()); ?>
                </li>

                <li>
                    <div class="dropdown drp-user">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="feather icon-user" style="font-size: 20px;"></i>
                        </a>
                        <div class="dropdown-menu dropdown-menu-right profile-notification">
                            <div class="pro-head">
                                <img src="<?php echo base_url() . $file; ?>" class="img-radius"
                                    alt="User-Profile-Image">
                                <span><?php echo $this->customlib->getSessionName(); ?></span>

                            </div>
                            <ul class="pro-body">
                                <li><a href="<?php echo base_url(); ?>myprofile" class="dropdown-item"><i
                                            class="feather icon-user"></i> Profile</a></li>
                                <!-- <li><a href="email_inbox.html" class="dropdown-item"><i class="feather icon-mail"></i> My Messages</a></li> -->
                                <li><a href="<?php echo base_url(); ?>auth2/logout" class="dropdown-item"><i
                                            class="feather icon-lock"></i> Logout</a></li>
                            </ul>
                        </div>
                    </div>
                </li>
            </ul>
        </div>


    </header>
    <!-- [ Header ] end -->

    <!-- [ Main Content ] start -->
    <div class="pcoded-main-container">
        <div class="pcoded-content">