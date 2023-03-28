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
                    <li class="breadcrumb-item"><a href="#!">Master Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/tipe_jurnal"><?php echo $title; ?></a></li>
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
                    <?php if (($this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_edit'))) {
                        
                        ?>     
                        <div class="col-md-8">           
                            <div class="box box-primary">
                                <div class="box-header with-border">
                                    <h3 class="box-title"><?php echo $title; ?></h3>
                                </div> 
                                <form action=""  id="setup_periode_laporanform" name="setup_periode_laporanform" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                                    <div class="box-body">
                                        <?php if ($this->session->flashdata('msg')) { ?>
                                            <?php echo $this->session->flashdata('msg') ?>
                                        <?php } ?>        
                                        <?php echo $this->customlib->getCSRF(); ?>
                                        
                                        <div class="form-group row">
                                            <label for="cabangx" class="col-sm-3 col-form-label">Unit Kerja<small class="req"> *</small></label>
                                            <div class="col-sm-8">
                                                <input id="cabang" name="cabang" type="hidden" class="form-control"  value="<?php echo $cabangx; ?>" />
                                                <select <?php echo $is_disabled;?> id="cabangx" name="cabangx" class="form-control" >
                                                    <option value=""> - Pilih -</option>
                                                    <?php
                                                    foreach ($cbx_cabang as $key => $row) {
                                                        ?>
                                                        <option value="<?php echo $row['id'] ?>" <?php if (isset($cabangx) && $cabangx == $row['id']) { echo 'selected'; } ?> > <?php echo $row["nama"] ?></option>
                                                    <?php }
                                                    ?>
                                                </select>
                                                <span class="text-danger"><?php echo form_error('cabangx'); ?></span>
                                            </div>
                                        </div>

                                        <div id="setup_periode_data"></div>
                                        
                                    </div>
                                    <div class="box-footer">
                                        <button type="button" id="btnClosingPeriode" class="btn btn-info pull-right"> Simpan</button>
                                    </div>
                                </form>
                            </div>   
                        </div>  
                    <?php } ?>
                    
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
    
    //load default cabang
    $('#setup_periode_data').load('<?php echo base_url(); ?>master/setup_periode_laporan/cab/');
    
    $(document).on('change', '#cabangx', function () {
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            
            $('#setup_periode_data').load('<?php echo base_url(); ?>master/setup_periode_laporan/cab/' + src_cabangx);
            
            if(cabang !== src_cabangx){
                $('#btnClosingPeriode').attr('disabled','disabled');
            }else{
                $('#btnClosingPeriode').removeAttr('disabled');
            }
        }
    });
    
    
    $('#btnClosingPeriode').click(function (e) {
        e.preventDefault();
        $('#cabangx').removeAttr('disabled');
        $('#bln_periode_saldo_awalx').removeAttr('disabled');
        $('#thn_periode_saldo_awalx').removeAttr('disabled');
        $('#tutup_periode_saldoawal').removeAttr('disabled');
        
        let is_error = 0;
        var alertMsg = new Array();
    
        if($('#currency').val() == ''){
            alertMsg[0] = 'Mata uang harus diisi!';
            is_error = 1;
        }
        if($('#bln_aktif_sebelumnyax') == ''){
            alertMsg[1] = 'Bulan aktif sebelumnya harus diisi!';
            is_error = 1;
        }
        if($('#thn_aktif_sebelumnyax').val() == ''){
            alertMsg[2] = 'Tahun aktif sebelumnya harus diisi!';
            is_error = 1;
        }
        if($('#bln_aktif_saatinix').val() == ''){
            alertMsg[3] = 'Bulan aktif saat ini harus diisi!';
            is_error = 1;
        }
        if($('#thn_aktif_saatinix').val() == ''){
            alertMsg[4] = 'Tahun aktif saat ini harus diisi!';
            is_error = 1;
        }
        if($('#bln_aktif_akandatangx').val() == ''){
            alertMsg[5] = 'Bulan aktif akan datang harus diisi!';
            is_error = 1;
        }
        if($('#thn_aktif_akandatangx').val() == ''){
            alertMsg[6] = 'Tahun aktif akan datang harus diisi!';
            is_error = 1;
        }
        if($('#bln_periode_saldo_awalx').val() == ''){
            alertMsg[7] = 'Bulan periode saldo awal harus diisi!';
            is_error = 1;
        }
        if($('#thn_periode_saldo_awalx').val() == ''){
            alertMsg[8] = 'Tahun periode saldo awal harus diisi!';
            is_error = 1;
        }
        if($('#bln_bulan_berjalanx').val() == ''){
            alertMsg[9] = 'Bulan berjalan harus diisi!';
            is_error = 1;
        }
        if($('#thn_bulan_berjalanx').val() == ''){
            alertMsg[10] = 'Tahun berjalan harus diisi!';
            is_error = 1;
        }
        
        if(is_error == 0){
            if($('#cabang').val() != $('#cabangx').val()){
                if (!confirm('Anda yakin akan setup periode laporan cabang lain ?')) {
                    return;
                } else {
                     $('#setup_periode_laporanform').attr('action', '<?php echo site_url('master/setup_periode_laporan') ?>');
                     $('#setup_periode_laporanform').trigger("submit");
                }
            }else{
                $('#setup_periode_laporanform').attr('action', '<?php echo site_url('master/setup_periode_laporan') ?>');
                $('#setup_periode_laporanform').trigger("submit");
            }
        }else{
            
            var alertMsgNew = new Array();
            var x = 0;
            for(var i=0; i<alertMsg.length; i++){
                var msg = alertMsg[i];
                //alert(msg);
                if (typeof msg !== "undefined"){
                    alertMsgNew[x] = alertMsg[i];
                    x++;    
                }
            }
            alert(alertMsgNew.join("\n"));
            return false;
        }
    });
});
</script>
