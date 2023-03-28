
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
                    <li class="breadcrumb-item"><a href="#!">Manajemen User</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>admin/user"><?php echo $title; ?></a></li>
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
                        <div class="box box-primary" >
                            <div class="box-header with-border">

                                <h3 class="box-title"><?php echo $this->lang->line('assign_permission'); ?> (<?php echo $user['name'] ?>) </h3>
                            </div>
                            <form action="<?php echo site_url('admin/user/permission/' . $user['id']) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
                                <div class="box-body">
                                    <?php if ($this->session->flashdata('msg')) { ?>
                                        <?php echo $this->session->flashdata('msg') ?>
                                    <?php } ?>
                                    <?php
                                    if (isset($error_message)) {
                                        echo "<div class='alert alert-danger'>" . $error_message . "</div>";
                                    }
                                    ?>      
                                    <?php echo $this->customlib->getCSRF(); ?>  
                                    <input type="hidden" name="user_id" value="<?php echo $user['id'] ?>"/>
                                    <div class="form-group">
                                        <label class="col-lg-2 control-label"><?php echo $this->lang->line('permisssion'); ?></label>
                                        <div class="col-lg-10">
                                            <?php
                                            foreach ($userpermission as $userpermission_key => $userpermission_value) {

                                                if ($userpermission_value->user_permissions_id == 1) {
                                                    ?>
                                                    <input type="hidden" name="prev_array[]" value="<?php echo $userpermission_value->id ?>">
                                                    <?php
                                                }
                                                ?>
                                                <label class="checkbox-inline">
                                                    <input type="checkbox" name="module_perm[]" value="<?php echo $userpermission_value->id ?>" <?php echo set_checkbox('module_perm[]', $userpermission_value->id, ($userpermission_value->user_permissions_id == 1) ? TRUE : FALSE) ?>> <?php echo $userpermission_value->name; ?>
                                                </label>
                                                <?php
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>         

                </div>
  
            </div>               
        </div>
    </div> 
</div>
