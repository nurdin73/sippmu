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
            <?php if (($this->rbac->hasPrivilege('master_setting_parameter', 'can_add')) || ($this->rbac->hasPrivilege('master_setting_parameter', 'can_edit'))) {
    
                ?>     
                <div class="col-md-4">           
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><?php echo $title; ?></h3>
                        </div> 
                        <form action="<?php echo site_url('master/setting_parameter') ?>"  id="setting_parameterform" name="setting_parameterform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                            <div class="box-body">
                                <?php if ($this->session->flashdata('msg')) { ?>
                                    <?php echo $this->session->flashdata('msg') ?>
                                <?php } ?>        
                                <?php echo $this->customlib->getCSRF(); ?>
                                <div class="form-group">
                                    <label for="param_code"><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="param_code"  name="param_code" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["param_code"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('param_code'); ?></span>

                                    <label for="param_name"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="param_name"  name="param_name" placeholder="" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["param_name"];
                                    }
                                    ?>" />
                                    <span class="text-danger"><?php echo form_error('param_name'); ?></span>

                                    
                                    <label for="param_type">Tipe</label><small class="req"> *</small>
                                    <select id="param_type" name="param_type" class="form-control">
                                        <option value="" <?php if (isset($result) && $result["param_type"] == '') echo 'selected'; ?> >- Pilih - </option>
                                        <option value="persen" <?php if (isset($result) && $result["param_type"] == 'persen') echo 'selected'; ?> >Persen</option>
                                        <option value="rekening" <?php if (isset($result) && $result["param_type"] == 'rekening') echo 'selected'; ?> >Rekening</option>
                                    </select>
                                    <span class="text-danger"><?php echo form_error('param_type'); ?></span>

                                    <div id="param_value_persen">
                                        <label for="param_value">Nilai</label>
                                        <input autofocus="" id="param_value"  name="param_value" placeholder="" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["param_value"];
                                        }
                                        ?>" />
                                    </div>
                                    
                                    <div id="param_value_rekening">
                                        <label for="param_value2">Nilai</label>
                                        <select id="param_value2" name="param_value2" class="form-control select2">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($cbx_kode_rekening as $key => $row) {
                                                $selected = '';
                                                if (isset($result) && $result["param_value"] == $row['id']) {
                                                    $selected = "selected";
                                                }
                                                ?>
                                                <option value="<?php echo $row['id'] ?>" <?php echo $selected; ?>> <?php echo $row['kode'] ?> - <?php echo $row["nama"] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                    </div>
                                    
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
            if (($this->rbac->hasPrivilege('master_setting_parameter', 'can_add')) || ($this->rbac->hasPrivilege('master_setting_parameter', 'can_edit'))) {
                echo "8";
            } else {
                echo "12";
            }
            ?>  ">              
                <div class="box box-primary" id="tachelist">
                    <div class="box-header ptbnull">
                        <h3 class="box-title titlefix"><?php echo $this->lang->line('setting_parameter'); ?> <?php echo $this->lang->line('list'); ?></h3>
                    </div>
                    <div class="box-body">
                        <div class="mailbox-controls">
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <div class="download_label"><?php echo $this->lang->line('setting_parameter'); ?></div>
                            <table class="table table-striped table-bordered table-hover example">
                                <thead>
                                    <tr>
                                        <th><?php echo $this->lang->line('code'); ?></th>
                                        <th><?php echo $this->lang->line('name'); ?></th>
                                        <th>Nilai</th>
                                        <th>Tipe</th>
                                        <th class="text-right no-print"><?php echo $this->lang->line('action'); ?></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $count = 1;
                                    foreach ($data_parameter as $value) {
                                        
                                        ?>
                                        <tr>
                                            <td class="mailbox-name"> <?php echo $value['param_code'] ?></td>
                                            <td class="mailbox-name"> <?php echo $value['param_name'] ?></td>
                                            <td class="mailbox-name"> <?php echo $value['param_value'] ?></td>
                                            <td class="mailbox-name"> <?php echo $value['param_type'] ?></td>
                                            <td class="mailbox-date pull-right no-print">
                                            <?php if ($this->rbac->hasPrivilege('master_setting_parameter', 'can_edit')) { ?>
                                                <a href="<?php echo base_url(); ?>master/setting_parameter/edit/<?php echo $value['param_code'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                    <i class="fa fa-pencil"></i>
                                                </a>
                                            <?php } if ($this->rbac->hasPrivilege('master_setting_parameter', 'can_delete')) { ?>
                                                <a href="<?php echo base_url(); ?>master/setting_parameter/delete/<?php echo $value['param_code'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
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
    $('#param_value_persen').hide();
    $('#param_value_rekening').hide();
    
    
    $('.select2').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    
    $(document).on('change', '#param_type', function () {
        var param_type = $('#param_type').val();
        if(param_type == 'persen'){
            $('#param_value_persen').show();
            $('#param_value_rekening').hide();
        }
        if(param_type == 'rekening'){
            $('#param_value_persen').hide();
            $('#param_value_rekening').show();
        }
    });
    
    if(act == 'edit'){
        $('#param_code').attr('readonly', true);
        var param_type = $('#param_type').val();
        if(param_type == 'persen'){
            $('#param_value_persen').show();
            $('#param_value_rekening').hide();
        }
        if(param_type == 'rekening'){
            $('#param_value_persen').hide();
            $('#param_value_rekening').show();
        }
    }
});
</script>
