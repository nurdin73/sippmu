
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

                        
                            <form action="<?php echo site_url('admin/user/edit_profile/' . $id) ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8" enctype="multipart/form-data">
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
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('username'); ?></label><br>
                                                        <?php echo $user["username"] ?>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('full_name'); ?></label><small class="req"> *</small>
                                                        <input id="firstname" name="name" placeholder="" type="text" class="form-control"  value="<?php echo $user["name"] ?>" />
                                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                                    </div>
                                                </div>

                                            </div>
                                            <div class="row">

                                                <div class="col-md-6">
                                                    <div class="form-group">
                                                        <label for="cabang">Unit Kerja</label>
                                                        <select disabled id="cabang" name="cabang" placeholder="" type="text" class="form-control" >
                                                            <option value=""><?php echo $this->lang->line('select') ?></option>
                                                            <?php foreach ($cabangs as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $value["id"] ?>" <?php
                                                                    if ($user["cabang"] == $value["id"]) {
                                                                        echo "selected";
                                                                    }
                                                                    ?>><?php echo $value["nama"] ?></option>
                                                            <?php }
                                                            ?>
                                                        </select> 
                                                        <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('role'); ?></label>
                                                        <select disabled id="role" name="role" class="form-control" >
                                                            <option value=""   ><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($getStaffRole as $key => $role) {
                                                                ?>
                                                                <option value="<?php echo $role["id"] ?>" <?php
                                                                if ($user["user_type"] == $role["type"]) {
                                                                    echo "selected";
                                                                }
                                                                ?>><?php echo $role["type"] ?></option>
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
                                                        <input id="mobileno" name="phone" placeholder="" type="text" class="form-control"  value="<?php echo $user["phone"] ?>" />
                                                        <input id="editid" name="editid" placeholder="" type="hidden" class="form-control"  value="<?php echo $user["id"]; ?>" />

                                                        <span class="text-danger"><?php echo form_error('phone'); ?></span>
                                                    </div>
                                                </div> 
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('email'); ?></label><small class="req"> *</small>
                                                        <input id="email" name="email" placeholder="" type="text" class="form-control"  value="<?php echo $user["email"] ?>" />
                                                        <span class="text-danger"><?php echo form_error('email'); ?></span>
                                                    </div>
                                                </div>
                                                
                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"> <?php echo $this->lang->line('gender'); ?></label>
                                                        <select class="form-control" name="gender">
                                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                                            <?php
                                                            foreach ($genderList as $key => $value) {
                                                                ?>
                                                                <option value="<?php echo $key; ?>" <?php if ($user['gender'] == $key) echo "selected"; ?>><?php echo $value; ?></option>
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
                                                        <div><textarea name="address" class="form-control"><?php echo $user["address"] ?></textarea>
                                                        </div>
                                                        <span class="text-danger"></span>
                                                    </div>
                                                </div>

                                                <div class="col-md-9">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile">Keterangan</label>
                                                        <div><textarea name="note" class="form-control"><?php echo $user["note"] ?></textarea>
                                                        </div>
                                                        <span class="text-danger"></span></div>
                                                </div>                          

                                                <div class="col-md-3">
                                                    <div class="form-group">
                                                        <label for="exampleInputFile"><?php echo $this->lang->line('photo'); ?></label>
                                                        <div><input class="filestyle form-control" type='file' name='file' id="file" size='20' />
                                                        </div>
                                                        <span class="text-danger"><?php echo form_error('file'); ?></span></div>
                                                </div>  

                                            </div>

                                        </div>
                                    </div>

                                    
                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"><?php echo $this->lang->line('save'); ?></button>
                                </div>
                            </form>
                        </div>               
                    </div>
                </div> 
                
            </div>               
        </div>
    </div> 
</div>

