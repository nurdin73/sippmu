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
    <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title><?= env('APP_NAME') ?> </title>
    <meta name="description" content="Pencatatan dan laporan keuangan usaha dengan aplikasi keuangan SIMKATMUH" />

    <link href="<?php echo base_url(); ?>assets/images/favicon.ico" rel="shortcut icon" type="image/x-icon">
    <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css">

</head>

<body>
    <div class="auth-wrapper align-items-stretch aut-bg-img">
        <div class="flex-grow-1">
            <div class="h-20 d-md-flex align-items-center auth-side-img">
                <div class="col-sm-3 auth-content w-auto">
                    <img src="<?php echo base_url(); ?>assets/images/muhammadiyah-logo.jpg" alt=""
                        class="img-fluid logo-muh">
                </div>
            </div>
            <div class="auth-side-form">
                <div class=" auth-content">
                    <div class="xcenter"><img src="<?php echo base_url(); ?>assets/images/logo2.png" alt=""
                            class="img-fluid mb-4" style="height:100px"></div>
                    <h4 class="mb-2 f-w-400"><?= env('APP_NAME') ?></h4>
                    <h4 class="mb-4 f-w-400">PDM KABUPATEN CIREBON</h4>
                    <?php
                    if (isset($error_message)) {
                        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                    }
                    ?>
                    <?php
                    if ($this->session->flashdata('message')) {
                        echo "<div class='alert alert-success'>" . $this->session->flashdata('message') . "</div>";
                    }
                    ?>
                    <form action="<?php echo site_url('auth2/login') ?>" method="post">
                        <?php echo $this->customlib->getCSRF(); ?>

                        <div class="form-group mb-3">
                            <label class="floating-label" for="username">Username</label>
                            <input type="text" class="form-control" name="username" placeholder="">
                            <span class="text-danger"><?php echo form_error('username'); ?></span>
                        </div>
                        <div class="form-group mb-4">
                            <label class="floating-label" for="password">Password</label>
                            <input type="password" class="form-control" name="password" placeholder="">
                            <span class="text-danger"><?php echo form_error('password'); ?></span>
                        </div>
                        <button type="submit" class="btn btn-block btn-primary mb-4">Signin</button>
                    </form>

                </div>
            </div>
        </div>
    </div>

    <script src="<?php echo base_url(); ?>assets/js/vendor-all.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/plugins/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/ripple.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/pcoded.min.js"></script>

</body>

</html>