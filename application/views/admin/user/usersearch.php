<?php
$currency_symbol = $this->customlib->getCurrencyFormat();
?>

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
                            <div class="box-header with-border">
                                <h3 class="box-title"><i class="fa fa-search"></i> <?php echo $this->lang->line('select_criteria'); ?></h3>
                            </div>
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>  <?php echo $this->session->flashdata('msg') ?> <?php } ?>
                                <form role="form" action="<?php echo site_url('admin/user') ?>" method="post" class="">
                                    <div class="row">
                                 
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        <div class="col-md-4">
                                            <div class="form-group"> 
                                                <input id="cabang" name="cabang" type="hidden" class="form-control"  value="<?php echo $cabangx; ?>" />
                                                <select <?php echo $is_disabled;?> id="cabangx" name="cabangx" class="form-control" >
                                                    <?php
                                                    if($is_pusat){
                                                        echo '<option value="all"> - Semua Unit Kerja -</option>';
                                                    }
                                                    foreach ($cbx_cabang as $key => $row) {
                                                        $cabangxx = isset($_POST['cabangx']) ? $_POST['cabangx'] : $cabangx;
                                                        ?>
                                                        <option value="<?php echo $row['id'] ?>" <?php if (isset($cabangxx) && $cabangxx == $row['id']) { echo 'selected'; } ?> > <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>   
                                        </div>
                                        <div class="col-md-2">
                                            <div class="form-group"> 
                                                <select name="role" class="form-control">
                                                    <option value=""> - Semua Roles -</option>
                                                    <?php foreach ($role as $key => $role_value) {
                                                        $role_idx = $_POST['role'] ? $_POST['role'] : $role_id;
                                                        ?>
                                                        <option <?php
                                                        if (isset($role_idx) && $role_idx == $role_value["type"]) {
                                                            echo "selected";
                                                        }
                                                        ?> value="<?php echo $role_value['type'] ?>"><?php echo $role_value['type'] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                            </div>   
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <input type="text" name="search_text" class="form-control"  value="<?php if(isset($_POST['search_text'])) echo $_POST['search_text'];?>"  placeholder="<?php echo $this->lang->line('search_by_user'); ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <div class="form-group">
                                                <button type="submit" name="search" value="search_full" class="btn btn-primary pull-right btn-sm checkbox-toggle"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                            </div>
                                        </div>
                                        <div class="col-md-1">
                                            <?php if ($this->rbac->hasPrivilege('user', 'can_add')) { ?>
                                                <small class="xright">
                                                    <a href="<?php echo base_url(); ?>admin/user/create" class="btn btn-success btn-sm"   >
                                                        <i class="fa fa-plus-square-o"></i> <?php echo $this->lang->line('add_user'); ?>
                                                    </a>
                                                </small>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </form>
                                
                            </div>
                        </div>
                        <?php
                        if (isset($resultlist)) {
                            ?>
                            <div class="nav-tabs-custom">
                                <ul class="nav nav-tabs">

                                    <li class="active"><a href="#tab_1" data-toggle="tab" aria-expanded="false"><i class="fa fa-newspaper-o"></i> <?php echo $this->lang->line('card'); ?> <?php echo $this->lang->line('view'); ?></a></li>
                                    <li class=""><a href="#tab_2" data-toggle="tab" aria-expanded="true"><i class="fa fa-list"></i> <?php echo $this->lang->line('list'); ?>  <?php echo $this->lang->line('view'); ?></a></li>
                                </ul>
                                <div class="tab-content">
                                    <div class="download_label"><?php echo $title; ?></div>
                                    <div class="tab-pane table-responsive no-padding" id="tab_2">
                                        <table class="table table-striped table-bordered table-hover example" cellspacing="0" width="100%">
                                            <thead>
                                                <tr>
                                                    <th><?php echo $this->lang->line('user_id'); ?></th>
                                                    <th><?php echo $this->lang->line('name'); ?></th>
                                                    <th><?php echo $this->lang->line('role'); ?></th>
                                                    <th>Unit Kerja</th>
                                                    <th><?php echo $this->lang->line('mobile_no'); ?></th>

                                                    <th class="text-right"><?php echo $this->lang->line('action'); ?></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <?php
                                                if (empty($resultlist)) {
                                                    ?>
                                                    <tr>
                                                        <td colspan="12" class="text-danger text-center"><?php echo $this->lang->line('no_record_found'); ?></td>
                                                    </tr>                                           <?php
                                                } else {
                                                    $count = 1;
                                                    foreach ($resultlist as $user) {
                                                        ?>
                                                        <tr>
                                                            <td><?php echo $user['username']; ?></td>
                                                            <td>
                                                                <a <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?> href="<?php echo base_url(); ?>admin/user/profile/<?php echo encrypt_url($user['id']); ?>"
                                                                    <?php } ?>><?php echo $user['name']; ?>
                                                                </a>
                                                            </td>

                                                            <td><?php echo $user['user_type']; ?></td>
                                                            <td><?php echo $user['cabang']; ?></td>
                                                            <td><?php echo $user['phone']; ?></td>

                                                            <td class="pull-right">
                                                                <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?>
                                                                    <a href="<?php echo base_url(); ?>admin/user/profile/<?php echo encrypt_url($user['id']) ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('show'); ?>" >
                                                                        <i class="fa fa-reorder"></i>
                                                                    </a>
                                                                    <?php } if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) {

                                                                        $a = 0 ;
                                                                $sessionData = $this->session->userdata('admin');
                                                                    $userdata = $this->customlib->getUserData();

                                                                $user["user_type"];
                                                                if($user["user_type"] == "Super Admin"){
                                                                        if($userdata["email"] == $user["email"]){
                                                                            $a = 1;    
                                                                        }
                                                                    }else{
                                                                        $a = 1 ;
                                                                    }
                                                                    if($a == 1){
                                                                    ?>
                                                                    <a href="<?php echo base_url(); ?>admin/user/edit/<?php echo encrypt_url($user['id']) ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                        <i class="fa fa-pencil"></i>
                                                                    </a>
                                                        <?php } } ?>
                                                            </td>
                                                        </tr>
                                                        <?php
                                                        $count++;
                                                    }
                                                }
                                                ?>
                                            </tbody>
                                        </table>
                                    </div>                           
                                    <div class="tab-pane active" id="tab_1">
                                        <div class="mediarow">   
                                            <div class="row">   
                                                <?php if (empty($resultlist)) {
                                                    ?>
                                                    <div class="alert alert-info"><?php echo $this->lang->line('no_record_found'); ?></div>
                                                    <?php
                                                } else {
                                                    $count = 1;
                                                    foreach ($resultlist as $user) {
                                                        ?>
                                                        <div class="col-lg-4 col-md-4 col-sm-6 img_div_modal">
                                                            <div class="staffinfo-box">
                                                                <div class="staffleft-box">
                                                                    <?php
                                                                    if (!empty($user["image"])) {
                                                                        $image = $user["image"];
                                                                    } else {
                                                                        $image = "avatar.jpg";
                                                                    }
                                                                    ?>
                                                                    <img  src="<?php echo base_url() . "uploads/user_images/" . $image ?>" />
                                                                </div>
                                                                <div class="staffleft-content">
                                                                    <h5><span data-toggle="tooltip" title="<?php echo $this->lang->line('name'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $user["name"]; ?></span></h5>
                                                                    <p><font data-toggle="tooltip" title="<?php echo "Employee Id"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $user["username"] ?></font></p>
                                                                    <p><font data-toggle="tooltip" title="<?php echo "Contact Number"; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $user["phone"] ?></font></p>
                                                                    <p><font data-toggle="tooltip" title="<?php echo 'Cabang'; ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"> <?php echo $user["cabang"]; ?></font></p>
                                                                    <p class="staffsub" ><span data-toggle="tooltip" title="<?php echo $this->lang->line('role'); ?>" data-loading-text="<i class='fa fa-circle-o-notch fa-spin'></i> Processing"><?php echo $user["user_type"] ?></span> </p>
                                                                </div>
                                                                <div class="overlay3">
                                                                    <div class="stafficons">
                                                    }
                                                                        
                                                    <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) { ?>
                                                        <a title="Show"  href="<?php echo base_url() . "admin/user/profile/" . encrypt_url($user["id"]); ?>"><i class="fa fa-navicon"></i></a>
                                                    <?php } ?>
                                                        <?php if ($this->rbac->hasPrivilege('can_see_other_users_profile', 'can_view')) {

                                                                    $a = 0 ;
                                                                    $sessionData = $this->session->userdata('admin');
                                                                    $userdata = $this->customlib->getUserData();

                                                                    $user["user_type"];
                                                                    if($user["user_type"] == "Super Admin"){
                                                                        if($userdata["email"] == $user["email"]){
                                                                            $a = 1;    
                                                                        }
                                                                    }else{
                                                                        $a = 1 ;
                                                                    }
                                                                    if($a == 1){
                                                                    ?>
                                                                        <a title="Edit"  href="<?php echo base_url() . "admin/user/edit/" . encrypt_url($user["id"]); ?>"><i class=" fa fa-pencil"></i></a>
                                                                    <?php }
                                                            }
                                                        ?>
                                                </div>
                                            </div>
                                        </div>
                                    </div><!--./col-md-3-->
                                    <?php }
                                }
                                ?>


                                </div><!--./col-md-3-->
                            </div><!--./row-->  
                        </div><!--./mediarow-->  


                    </div>                                                          
                </div>
                
            </div>
        </div>
    </div>
</div>
    
<?php
}
?>
        
</div> 
</section>
</div>
<script type="text/javascript">
    function getSectionByClass(class_id, section_id) {
        if (class_id != "" && section_id != "") {
            $('#section_id').html("");
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        var sel = "";
                        if (section_id == obj.section_id) {
                            sel = "selected";
                        }
                        div_data += "<option value=" + obj.section_id + " " + sel + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        }
    }
    $(document).ready(function () {
        var class_id = $('#class_id').val();
        var section_id = '<?php echo set_value('section_id') ?>';
        getSectionByClass(class_id, section_id);
        $(document).on('change', '#class_id', function (e) {
            $('#section_id').html("");
            var class_id = $(this).val();
            var base_url = '<?php echo base_url() ?>';
            var div_data = '<option value=""><?php echo $this->lang->line('select'); ?></option>';
            $.ajax({
                type: "GET",
                url: base_url + "sections/getByClass",
                data: {'class_id': class_id},
                dataType: "json",
                success: function (data) {
                    $.each(data, function (i, obj)
                    {
                        div_data += "<option value=" + obj.section_id + ">" + obj.section + "</option>";
                    });
                    $('#section_id').append(div_data);
                }
            });
        });
    });
</script>