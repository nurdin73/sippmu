
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
                        <div class="box box-primary">

                            <form action="<?php echo site_url('admin/user/create') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
                                <div class="box-body">
                                    
                                    <div class="tshadow mb25 bozero">    

                                        <h4 class="pagetitleh2"><?php echo $this->lang->line('basic_information'); ?> </h4>

                                        <div class="around10">
                                            <?php if ($this->session->flashdata('msg')) { ?>
                                                <?php echo $this->session->flashdata('msg') ?>
                                            <?php } ?>  
                                            <?php echo $this->customlib->getCSRF(); ?>

                                            <div class="row">
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('username'); ?></label><small class="req"> *</small>
                                                        <input autofocus="" id="username" name="username"  placeholder="" type="text" class="form-control"  value="<?php echo set_value('username') ?>" />
                                                        <span class="text-danger"><?php echo form_error('username'); ?></span>
                                                    </div>
                                                </div>
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('password'); ?></label><small class="req"> *</small>
                                                        <input autofocus="" id="password" name="password"  placeholder="" type="password" class="form-control"  value="" />
                                                        <span class="text-danger"><?php echo form_error('password'); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('full_name'); ?></label><small class="req"> *</small>
                                                        <input id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name') ?>" />
                                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1">Unit Kerja</label><small class="req"> *</small>
                                                        <select id="cabang" name="cabang" placeholder="" type="text" class="form-control" >
                                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                                            <?php foreach ($cabangs as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $value["id"] ?>" <?php echo set_select('cabang', $value['id'], set_value('cabang')); ?>> <?php echo $value["kode"] ?> - <?php echo $value["nama"] ?></option>
                                                            <?php }
                                                            ?>
                                                        </select> 
                                                        <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label><small class="req"> *</small>
                                                        <select  id="role" name="role" class="form-control" >
                                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($roles as $key => $role) {
                                                                ?>
                                                                <option value="<?php echo $role['id'] ?>" <?php echo set_select('role', $role['id'], set_value('role')); ?>><?php echo $role["name"] ?></option>
                                                            <?php }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('role'); ?></span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            <div class="row">

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('phone'); ?></label>
                                                        <input id="phone" name="phone" placeholder="" type="text" class="form-control"  value="<?php echo set_value('phone') ?>" />
                                                        <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                                    </div>
                                                </div> 
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label>
                                                        <input id="email" name="email" placeholder="" type="text" class="form-control"  value="<?php echo set_value('email') ?>" />
                                                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                    </div>
                                                </div>
                                            
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label>
                                                        <select class="form-control" name="gender">
                                                            <option value=""> </option>
                                                            <?php
                                                            foreach ($genderList as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $key; ?>" <?php echo set_select('gender', $key, set_value('gender')); ?>><?php echo $value; ?></option>
                                                                <?php
                                                            }
                                                            ?>
                                                        </select>
                                                        <span class="text-danger"><?php echo form_error('gender'); ?></span>
                                                    </div>
                                                </div>
                                                
                                            </div>
                                            
                                            <div class="row">

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Alamat</label>
                                                        <div><textarea name="address" class="form-control"><?php echo set_value('address'); ?></textarea>
                                                        </div>
                                                        <span class="text-danger"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('note'); ?></label>
                                                        <div><textarea name="note" class="form-control"><?php echo set_value('note'); ?></textarea>
                                                        </div>
                                                        <span class="text-danger"></span>
                                                    </div>
                                                </div>                          

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('file'); ?></span>
                                                    </div>
                                                </div>  
                                                
                                            </div>

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
    

<script type="text/javascript">


    $(document).ready(function () {
        var date_format = '<?php echo $result = strtr($this->customlib->getAppDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';
        $('#dob').datepicker({
            format: date_format,
            autoclose: true
        });
    });



</script>