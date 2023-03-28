<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

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
                    <li class="breadcrumb-item"><a href="#!">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>master/cabang_klasifikasi"><?php echo $title; ?></a></li>
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
                    <?php if (($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_add')) || ($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_edit'))) {
            
                        ?>     
                        <div class="col-md-4">           
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $title_add; ?></h3>
                                </div> 
                                <form action="<?php echo site_url('master/cabang_klasifikasi') ?>"  id="cabang_klasifikasiform" name="cabang_klasifikasiform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
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
                                                echo $result["nama"];
                                            }
                                            ?>" />
                                            <span class="text-danger"><?php echo form_error('nama'); ?></span>

                                            
                                            <input autofocus="" id="id"  name="id" placeholder="" type="hidden" class="form-control"  value="<?php
                                            if (isset($result)) {
                                                echo $result["id"];
                                            }
                                            ?>" />
                                        </div>
                                        
                                        <div class="form-group">
                                            <label for="exampleInputEmail1"><?php echo $this->lang->line('active'); ?> <?php echo $this->lang->line('status'); ?></label>
                                            <br/>
                                            <label class="radio-inline">
                                                <input type="radio" checked value="1" <?php
                                                if ((isset($result)) && ($result["is_active"] == "t")) {
                                                    echo "checked";
                                                }
                                                ?> name="is_active"><?php echo $this->lang->line('yes'); ?>
                                            </label>
                                                
                                            <label class="radio-inline">
                                                <input type="radio" value="0" <?php
                                                if ((isset($result)) && ($result["is_active"] == "f")) {
                                                    echo "checked";
                                                }
                                                ?> name="is_active"><?php echo $this->lang->line('no'); ?>
                                            </label>
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
                    if (($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_add')) || ($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_edit'))) {
                        echo "8";
                    } else {
                        echo "12";
                    }
                    ?>  ">              
                        <div class="box box-primary" id="tachelist">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix">Group Unit Kerja <?php echo $this->lang->line('list'); ?></h3>
                            </div>
                            <div class="box-body">
                                <div class="mailbox-controls">
                                </div>
                                <div class="table-responsive mailbox-messages">
                                    <div class="download_label"><?php echo $this->lang->line('cabang_klasifikasi'); ?></div>
                                    <table class="table table-striped table-bordered table-hover" id="dt_table_det">
                                        <thead>
                                            <tr>
                                                <th class="xcenter"><?php echo $this->lang->line('code'); ?></th>
                                                <th><?php echo $this->lang->line('name'); ?></th>
                                                <th class="xcenter"><?php echo $this->lang->line('status'); ?></th>
                                                <th class="xcenter no-print"><?php echo $this->lang->line('action'); ?>
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php
                                            $count = 1;
                                            foreach ($data_cabang as $value) {
                                                $status = "";

                                                if ($value["is_active"] == "t") {

                                                    $status = "Active";
                                                } else {
                                                    $status = "Inactive";
                                                }
                                                ?>
                                                <tr>

                                                    <td class="xcenter"> <?php echo $value['kode'] ?></td>
                                                    <td class=""> <?php echo $value['nama'] ?></td>
                                                    <td class="xcenter"> <?php echo $status ?></td>
                                                    <td class="xcenter no-print">
                                                    <?php if ($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_edit')) { ?>
                                                        <a href="<?php echo base_url(); ?>master/cabang_klasifikasi/edit/<?php echo $value['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('edit'); ?>">
                                                            <i class="fa fa-pencil"></i>
                                                        </a>
                                                    <?php } if ($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_delete')) { ?>
                                                        <a href="<?php echo base_url(); ?>master/cabang_klasifikasi/delete/<?php echo $value['id'] ?>" class="btn btn-default btn-xs"  data-toggle="tooltip" title="<?php echo $this->lang->line('delete'); ?>" onclick="return confirm('<?php echo $this->lang->line('delete_confirm') ?>')";>
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
            
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    var act = $('#act').val();
    if(act == 'edit'){
        $('#kode').attr('readonly', true);
        $('#nama').focus();
    }

    var oTableDet = $("#dt_table_det").dataTable({
        initComplete: function () {
            var api = this.api();
            $('#dt_table_det_filter input')
                    .off('.DT')
                    .on('input.DT', function () {
                        api.search(this.value).draw();
                    });
        },
        iDisplayLength: 15,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'frtip',
        responsive: 'true',
        processing: false,
        serverSide: false,

    });
});
</script>
