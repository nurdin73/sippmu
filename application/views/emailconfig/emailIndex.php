

<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10"><?php echo $title; ?></h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Settings</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>emailconfig"><?php echo $title; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- table start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <div class="row">
                    <div class="col-md-12">             
                        <div class="box box-primary">
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-envelope"></i> <?php echo $this->lang->line('email_setting'); ?></h3>
                            </div>   
                            <form action="<?php echo base_url() ?>emailconfig/index"   name="employeeform" class="form-horizontal form-label-left" method="post" accept-charset="utf-8">

                                <div class="box-body">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>   
                                    <?php echo $this->customlib->getCSRF(); ?>
                                    <div class="form-group">
                                        <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                            <?php echo $this->lang->line('email_engine'); ?>
                                        </label>
                                        <div class="col-md-6 col-sm-6 col-xs-12">
                                            <select autofocus="" id="email_type" name="email_type" class="form-control">

                                                <?php
                                                foreach ($mailMethods as $method_key => $method_value) {
                                                    ?>
                                                    <option value="<?php echo $method_key ?>"
                                                            <?php if (set_value('email_type', $emaillist->email_type) == $method_key) echo "selected=selected" ?>>
                                                        <?php echo $method_value ?></option>
                                                    <?php
                                                }
                                                ?>
                                            </select>

                                            <span class="text-danger"><?php echo form_error('email_type'); ?></span>
                                        </div>
                                    </div>   
                                    <?php $display = (set_value('email_type', $emaillist->email_type) != "smtp") ? 'ss-none' : '' ?>
                                    <div class="is_disabled <?php echo $display; ?>" >


                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('smtp_username'); ?>
                                            </label>
                                            <div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" name="smtp_username" placeholder="" type="text" class="form-control col-md-7 col-xs-12" value="<?php echo set_value('smtp_username', $emaillist->smtp_username); ?>" />
                                                <span class="text-danger"><?php echo form_error('smtp_username'); ?></span>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('smtp_password'); ?>
                                            </label><div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" name="smtp_password" placeholder="" type="password" class="form-control col-md-7 col-xs-12"  value="<?php echo set_value('smtp_password', $emaillist->smtp_password); ?>" />
                                                <span class="text-danger"><?php echo form_error('smtp_password'); ?></span>
                                            </div></div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('smtp_server'); ?>
                                            </label><div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" name="smtp_server" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo set_value('smtp_server', $emaillist->smtp_server); ?>"  />
                                                <span class="text-danger"><?php echo form_error('smtp_server'); ?></span>
                                            </div>  </div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('smtp_port'); ?>
                                            </label><div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" name="smtp_port" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo set_value('smtp_port', $emaillist->smtp_port); ?>"  />
                                                <span class="text-danger"><?php echo form_error('smtp_port'); ?></span>
                                            </div></div>
                                        <div class="form-group">
                                            <label class="control-label col-md-3 col-sm-3 col-xs-12" for="exampleInputEmail1">
                                                <?php echo $this->lang->line('smtp_security'); ?>
                                            </label><div class="col-md-6 col-sm-6 col-xs-12">
                                                <input id="name" name="smtp_security" placeholder="" type="text" class="form-control col-md-7 col-xs-12"  value="<?php echo set_value('smtp_security', $emaillist->ssl_tls); ?>"  />
                                                <span class="text-danger"><?php echo form_error('smtp_security'); ?></span>
                                            </div></div>  
                                    </div>                          
                                </div>
                                <div class="box-footer">
                                    <div class="col-md-6 col-sm-6 col-xs-12 col-md-offset-3">
                                        <button type="submit" class="btn btn-info pull-left"> Simpan</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>           
                </div>
            </div>

        </div>
    </div>
</div>


<script type="text/javascript">
    $(document).ready(function () {


        $(document).on('change', '#email_type', function () {
            var selected = $(this).val();
            is_disabled(selected);
        });

    });
    function is_disabled(selected) {
        if (selected != "smtp") {
            $('.is_disabled').slideUp();
        } else {
            $('.is_disabled').slideDown();
        }
    }
</script>