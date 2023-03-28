<style type="text/css">
    @media print
    {
        .no-print, .no-print *
        {
            display: none !important;
        }
    }
</style>

<!-- [ breadcrumb ] -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tutup Periode</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Transaksi Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>transaction/tutup_periode">Tutup Periode</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-8">
        
        <div class="card">
            <div class="card-body">
                <?php 
                if (($this->rbac->hasPrivilege('trx_tutup_periode', 'can_edit'))) {
                        
                    if($data_setup['bulan_berjalan']){
                        $bln_tutup_periode = explode('/', $data_setup['bulan_berjalan']);
                        $result["bln_tutup_periode"] = $bln_tutup_periode[0];
                        $result["thn_tutup_periode"] = $bln_tutup_periode[1];
                    }else{
                        $result["bln_tutup_periode"] = '';
                        $result["thn_tutup_periode"] = '';
                    }
                    ?>   
                    <form action="<?php echo site_url('transaction/tutup_periode') ?>"  id="tutup_periode_form" name="tutup_periode_form" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
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
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="bln_tutup_periode" class="col-sm-3 col-form-label">Tutup Periode <small class="req"> *</small></label>
                                <div class="col-sm-3">
                                    <select  id="bln_tutup_periode" name="bln_tutup_periode" class="form-control" >
                                        <option value="">- Pilih -</option>
                                        <?php
                                        foreach ($monthList as $key => $month) {
                                            ?>
                                            <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_tutup_periode"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                     <span class="text-danger"><?php echo form_error('bln_tutup_periode'); ?></span>
                                </div>
                                <div class="col-sm-2">
                                    <input id="thn_tutup_periode" name="thn_tutup_periode" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                    if (isset($result)) {
                                        echo $result["thn_tutup_periode"];
                                    }
                                    ?>" />
                                     <span class="text-danger"><?php echo form_error('thn_tutup_periode'); ?></span>
                                </div>
                            </div>


                        </div>
                        <div class="box-footer">
                            <input name="id" type="hidden" class="form-control"  value="<?php echo $data_setup["id"]; ?>" />
                            <input id="cabang" name="cabang" type="hidden" class="form-control"  value="<?php echo $cabangx; ?>" />
                            <!--<button type="submit" class="btn btn-info pull-right">Simpan</button>-->
                            <a onclick="closingTutupPeriode()" id="btnClosingTutupPeriode" class="btn btn-danger pull-right btn-sm checkbox-toggle"><i class="fa fa-close"></i> Tutup Periode</a>
                        </div>
                    </form>
                <?php } ?>
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
    
    $(document).on('change', '#cabangx', function () {
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            
            if(cabang !== src_cabangx){
                $('#btnClosingTutupPeriode').attr('disabled','disabled');
                $('#btnClosingTutupPeriode').removeAttr('onclick');
            }else{
                $('#btnClosingTutupPeriode').removeAttr('disabled');
                $('#btnClosingTutupPeriode').attr('onclick','closingTutupPeriode()');
            }

        }
    });
    
//    $('#btnClosingTutupPeriode').click(function () {
//        if($('#cabang').val() != $('#cabangx').val()){
//            if (!confirm('Anda yakin akan tutup periode cabang lain ?')) {
//                return;
//            } else {
//                 $('#tutup_periode_form').trigger("submit");
//            }
//        }else{
//            $('#tutup_periode_form').trigger("submit");
//        }
//    });
});
    
    
function closingTutupPeriode() {
    let cabangx = $('#cabangx').val();
    let bln_tutup_periode = $('#bln_tutup_periode').val();
    let thn_tutup_periode = $('#thn_tutup_periode').val();
    
    Swal.fire({
        title: 'Peringatan!',
        text: 'Silahkan dicek ulang kesesuian data transaksi...\nApakah anda yakin data sudah sesuai dan akan melakukan Tutup Periode  '+ bln_tutup_periode + '/' + thn_tutup_periode + ' ?',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Tutup Periode !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.value) {
            $.ajax({
                dataType: 'json',
                url: '<?php echo base_url(); ?>transaction/tutup_periode/closing_tutup_periode/' + cabangx + '/' + bln_tutup_periode + '/' + thn_tutup_periode,
                success: function (result) {

                    if(result.success == 'true'){
                        successMsg(result.message);
                        //window.location.reload(true);

                    }else{
                        errorMsg(result.message);
                    }

                }

            });
        }
    });

 
}
  
</script>
