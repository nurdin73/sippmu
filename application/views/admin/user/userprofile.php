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
                    <div class="col-md-3">                
                        <div class="box box-primary" <?php
                                if ($user["is_active"] == 0) {
                                    echo "style='background-color:#f0dddd;'";
                                }
                                ?>>
                            <div class="box-body box-profile">
                                <?php
                                $image = $user['image'];
                                if (!empty($image)) {

                                    $file = $user['image'];
                                } else {

                                    $file = "avatar.jpg";
                                }
                                ?>
                                <img class="profile-user-img img-responsive img-circle" src="<?php echo base_url() . "uploads/user_images/" . $file ?>" alt="User profile picture">
                                <br><h3 class="profile-username text-center"><?php echo $user['name']; ?></h3>
                                <br>
                                <ul class="list-group list-group-unbordered">
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('user_id'); ?></b> <a class="pull-right text-aqua"><?php echo $user['username']; ?></a>
                                    </li>
                                    <li class="list-group-item listnoback">
                                        <b><?php echo $this->lang->line('role'); ?></b> <a class="pull-right text-aqua"><?php echo $user['user_type']; ?></a>
                                    </li>

                                </ul>
                            </div>
                        </div>

                    </div>

                    <div class="col-md-9">
                        <div class="nav-tabs-custom">
                            <ul class="nav nav-tabs">
                                <li class="active"><a href="#activity" data-toggle="tab" aria-expanded="true"><?php echo $this->lang->line('profile'); ?></a></li>
                                <li class="xright"><a href="<?php echo base_url('admin/user/edit_profile/' . $idx); ?>" data-toggle="tooltip" title="<?php echo "Edit"; ?>" title="<?php echo $this->lang->line('edit'); ?>" class="text" ><i class="fa fa-pencil"></i></a></li>
                                <li class="pull-right">
                                    <a href="#" class="change_password text-green" data-toggle="tooltip" title="Ubah Password" ></i> <i class="fa fa-key"></i></a>
                                </li>
                            </ul>
                            <div class="tab-content">
                                <div class="tab-pane active" id="activity">
                                    <div class="tshadow mb25 bozero">
                                        <div class="table-responsive around10 pt0">
                                            <table class="table table-hover table-striped tmb0">
                                                <tbody>  


                                                    <tr>
                                                        <td>Nama</td>
                                                        <td><?php echo $user['name']; ?></td>
                                                    </tr>
                                                     <tr>
                                                        <td>Unit Kerja</td>
                                                        <td><?php echo $user['cabang']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('phone'); ?></td>
                                                        <td><?php echo $user['phone']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('email'); ?></td>
                                                        <td><?php echo $user['email']; ?></td>
                                                    </tr>
                                                    <tr>
                                                        <td><?php echo $this->lang->line('gender'); ?></td>
                                                        <td><?php echo $user['gender']; ?></td>
                                                    </tr>
                                                    
                                                    <tr>
                                                        <td>Alamat</td>
                                                        <td><?php echo $user['address']; ?></td>
                                                    </tr>

                                                    <tr>
                                                        <td><?php echo $this->lang->line('note'); ?></td>
                                                        <td><?php echo $user['note']; ?></td>
                                                    </tr>

                                                </tbody>
                                            </table>
                                        </div> 
                                    </div> 
                                    
                                </div>
                                  
                                            
                            </div>
                        </div>
                    </div>
    
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myTimelineModal" role="dialog">
    <div class="modal-dialog modal-sm400">      
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title title text-center transport_fees_title"></h4>
            </div>
            <div class="">
                <div class="">
                    <div class="">

                        <form  id="timelineform" name="timelineform" method="post" action="<?php echo base_url() . "admin/timeline/add_user_timeline" ?>"  enctype="multipart/form-data">
                            <?php echo $this->customlib->getCSRF(); ?>
                            <div id='timeline_hide_show'>                                                    
                                <input type="hidden" name="user_id" value="<?php echo $user["id"] ?>" id="user_id">
                                <h4></h4>
                                <div class=" col-md-12">
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('title'); ?></label>
                                        <input id="timeline_title" name="timeline_title" placeholder="" type="text" class="form-control"  />
                                        <span class="text-danger"><?php echo form_error('timeline_title'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('date'); ?></label><small class="req"> *</small>
                                        <input id="timeline_date" name="timeline_date" value="<?php echo set_value('timeline_date', date($this->customlib->getAppDateFormat())); ?>" placeholder="" type="text" class="form-control"  />
                                        <span class="text-danger"><?php echo form_error('timeline_date'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('description'); ?></label>
                                        <textarea id="timeline_desc" name="timeline_desc" placeholder=""  class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('description'); ?></span>
                                    </div>

                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('attach_document'); ?></label>
                                        <div class="" style="margin-top:-5px; border:0; outline:none;"><input id="timeline_doc_id" name="timeline_doc" placeholder="" type="file"  class="filestyle form-control" data-height="40"  value="<?php echo set_value('timeline_doc'); ?>" />
                                            <span class="text-danger"><?php echo form_error('timeline_doc'); ?></span></div>
                                    </div>
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('visible'); ?></label>
                                        <input id="visible_check" checked="checked" name="visible_check" value="yes" placeholder="" type="checkbox"   />

                                    </div>


                                </div>
                            </div>
                            <div class="modal-footer" style="clear:both">
                                <button type="button" class="btn btn-default pull-left" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
                                <button type="submit"  class="btn btn-info pull-right"> Simpan</button>

                                <button type="reset" id="reset" style="display: none"  class="btn btn-info pull-right">Reset</button>                            </div>
                        </form>
                    </div>                 
                </div>
            </div>
        </div>
    </div>
</div>
<div id="scheduleModal" class="modal fade bs-example-modal-lg" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title_logindetail"></h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
            </div>
            <div class="modal-body_logindetail">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line('cancel'); ?></button>
            </div>
        </div>
    </div>
</div>

<div id="payslipview"  class="modal fade" role="dialog">

    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title"><?php echo $this->lang->line('details'); ?>   <span id="print"></span></h4>
            </div>
            <div class="modal-body" id="testdata">


            </div>
        </div>
    </div>
</div>


<div id="changepwdmodal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Change Password</h4>
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
            </div>
            <form method="post" id="changepassbtn" action="">
                
            <div class="modal-body">
                <div class="form-group">
                    <label for="email">Old Password</label>
                    <input type="password" class="form-control" name="current_pass" id="current_pass">
                </div>
                <div class="form-group">
                    <label for="email">Password</label>
                    <input type="password" class="form-control" name="new_pass" id="pass">
                </div>
                <div class="form-group">
                    <label for="pwd">Confirm Password</label>
                    <input type="password" class="form-control" name="confirm_pass" id="pwd">
                </div>

 
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="submit"  class="btn btn-primary">Save</button>
            </div>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript">
    
    $(document).ready(function (e) {
        $("#changepassbtn").on('submit', (function (e) {
            var user_id = $("#user_id").val();

            e.preventDefault();
            $.ajax({
                url: "<?php echo site_url('admin/user/change_password/') ?>"+user_id,
                type: "POST",
                data: new FormData(this),
                dataType: 'json',
                contentType: false,
                cache: false,
                processData: false,
                success: function (data) {

                    if (data.status == "fail") {
                        if(data.message){
                            errorMsg(data.message);
                        }else{
                            var message = "";
                            $.each(data.error, function (index, value) {
                                message += value;
                            });
                            errorMsg(message);
                        }
                        
                    } else {

                        successMsg(data.message);

                        window.location.reload(true);
                    }

                },
                error: function (e) {
                    alert("Fail");
                    console.log(e);
                }
            });


        }));
    });

    function delete_timeline(id) {

        var user_id = $("#user_id").val();
        
        Swal.fire({
            title: 'Peringatan!',
            text: 'Anda yakin akan menghapus data ini !',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Hapus data !',
            cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    url: '<?php echo base_url(); ?>admin/timeline/delete_user_timeline/' + id,
                    success: function (res) {
                        $.ajax({
                            url: '<?php echo base_url(); ?>admin/timeline/user_timeline/' + user_id,
                            success: function (res) {
                                $('#timeline_list').html(res);

                            },
                            error: function () {
                                alert("Fail")
                            }
                        });

                    },
                    error: function () {
                        alert("Fail")
                    }
                });
            }
        });

    }

    $(document).ready(function () {
        $(document).on('click','.change_password',function(){

            $('#changepwdmodal').modal('show');
        });

    });



</script>
<script>
    $(document).ready(function () {
        $('.detail_popover').popover({
            placement: 'right',
            title: '',
            trigger: 'hover',
            container: 'body',
            html: true,
            content: function () {
                return $(this).closest('td').find('.fee_detail_popover').html();
            }
        });

        var date_format = '<?php echo $result = strtr($this->customlib->getAppDateFormat(), ['d' => 'dd', 'm' => 'mm', 'Y' => 'yyyy',]) ?>';

        // $("#timeline_date").datepicker({
        //     format: date_format,
        //     autoclose: true

        // });
    });

</script>