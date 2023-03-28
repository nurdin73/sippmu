
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
                    <div class="col-md-4">
                        <div class="box box-primary" >
                            <div class="box-header with-border">
                                <h3 class="box-title"><?php echo $this->lang->line('role'); ?></h3>
                            </div>
                            <form action="<?php echo site_url('admin/roles') ?>"  id="employeeform" name="employeeform" method="post" accept-charset="utf-8">
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
                                    <div class="form-group">
                                        <label for="exampleInputEmail1"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                        <input autofocus="" id="name" name="name" placeholder="" type="text" class="form-control"  value="<?php echo set_value('name'); ?>" />
                                        <span class="text-danger"><?php echo form_error('name'); ?></span>
                                    </div>


                                </div>
                                <div class="box-footer">
                                    <button type="submit" class="btn btn-info pull-right"> Simpan</button>
                                </div>
                            </form>
                        </div>
                    </div>         
                    <div class="col-md-8">
                        <div class="box box-primary" id="route">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"><?php echo $this->lang->line('role'); ?> <?php echo $this->lang->line('list'); ?></h3>

                            </div>
                            <div class="box-body">
                                <div class="mailbox-controls">                         
                                    <div class="pull-right">
                                    </div>
                                </div>
                                <div class="table-responsive mailbox-messages">
                                    <div class="download_label"><?php echo $this->lang->line('role'); ?> <?php echo $this->lang->line('list'); ?></div>
                                    <table class="table table-striped table-bordered table-hover example">
                                        <thead>
                                            <tr>
                                                <th><?php echo $this->lang->line('role'); ?></th>
                                                <th><?php echo $this->lang->line('type'); ?></th>


                                                <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php if (empty($listroute)) {
                                                ?>

                                                <?php
                                            } else {
                                                $count = 1;
                                                foreach ($listroute as $data) {
                                                    ?>
                                                    <tr>
                                                        <td class="mailbox-name"> <?php echo $data['name'] ?></td>
                                                        <td class="mailbox-name"> 
                                                            <?php
                                                            if ($data['is_system']) {

                                                                echo $this->lang->line('system');
                                                            } else {
                                                                echo $this->lang->line('custom');
                                                            }
                                                            ?>
                                                        </td>


                                                        <td class="mailbox-date pull-right no-print">
                                                            <?php
                                                            if (!$data['is_superadmin']) {
                                                                ?>
                                                                <a href="<?php echo site_url('admin/roles/permission/' . $data['id']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('assign_permission'); ?>">
                                                                    <i class="fa fa-tag"></i>
                                                                </a>
                                                                <a href="<?php echo site_url('admin/roles/edit/' . $data['id']); ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                                    <i class="fa fa-pencil"></i>
                                                                </a>
                                                                <?php
                                                                if (!$data['is_system']) {
                                                                    ?>
                                                                    <a href="<?php echo site_url('admin/roles/delete/' . $data['id']); ?>"class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>');">
                                                                        <i class="fa fa-remove"></i>
                                                                    </a>

                                                                    <?php
                                                                }
                                                            }
                                                            ?>

                                                        </td>
                                                    </tr>
                                                    <?php
                                                }
                                                $count++;
                                            }
                                            ?>
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

<script type="text/javascript">
    $(document).ready(function () {
        $('#postdate').datepicker({
            format: "dd-mm-yyyy",
            autoclose: true
        });
        $("#btnreset").click(function () {
            $("#form1")[0].reset();
        });
    });
</script>
