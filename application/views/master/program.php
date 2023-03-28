<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<div class="content-wrapper" style="min-height: 946px;">  
    <section class="content-header">
        <h1>
            <i class="fa fa-sitemap"></i> <?php echo $this->lang->line('master_data'); ?>
        </h1>
    </section>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <?php if (($this->rbac->hasPrivilege('master_program', 'can_add')) || ($this->rbac->hasPrivilege('master_program', 'can_edit'))) {
    
                ?>     
                <div class="col-md-4">           
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div> 
                        <form action="<?php echo site_url('master/program') ?>"  id="programform" name="programform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>        
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="kode"><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="kode"  name="kode" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["kode"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('kode'); ?></span>

                                    <label for="nama"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="nama"  name="nama" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["deskripsi"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('nama'); ?></span>

                                </div>
                                
                            </div>
                            <div class="box-footer">
                                <input id="act"  name="act" type="hidden" class="form-control"  value="<?php echo $act; ?>" />
                                <button type="submit" class="btn btn-info pull-right"> Simpan</button>
                            </div>
                        </form>
                    </div>   
                </div>  
            <?php } ?>
            
            <div class="col-md-<?php
            if (($this->rbac->hasPrivilege('master_program', 'can_add')) || ($this->rbac->hasPrivilege('master_program', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>  ">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('program'); ?> <?php echo $this->lang->line('list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('program'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($data_program as $value) {
                                       
                                        ?>
                                        <tr>

                                            <td class="mailbox-name"> <?php echo $value['kode'] ?></td>
                                            <td class="mailbox-name"> <?php echo $value['deskripsi'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                            <?php if ($this->rbac->hasPrivilege('master_program', 'can_edit')) { ?>
                                                <a href="<?php echo base_url(); ?>master/program/edit/<?php echo $value['kode'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } if ($this->rbac->hasPrivilege('master_program', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>master/program/delete/<?php echo $value['kode'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
                                                    <i class="fa fa-remove"></i>
                                                </a>
                                            <?php } ?>
                                            </td>
                                        </tr>
                                        <?php
                                    }
                                    $count++;
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="">
                        <div class="mailbox-controls">
                        </div>
                    </div>
                </div>
            </div> 
        </div>
    </section>
</div>


<script>
$(document).ready(function () {
    var act = $('#act').val();
    if(act == 'edit'){
        $('#kode').attr('readonly', true);
        $('#nama').focus();
    }
});
</script>