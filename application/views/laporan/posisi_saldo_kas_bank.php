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
                    <li class="breadcrumb-item"><a href="#!">Laporan Kas Bank</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/posisi_saldo_kas_bank"><?php echo $title; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- table start -->
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-header">
                <form action="<?php echo site_url('laporan/posisi_saldo_kas_bank') ?>"  name="search_form" method="post" accept-charset="utf-8">
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>        
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="row">
                            <div class="col-md-5">
                                <div class="form-group"> 
                                    <label for="src_cabangx">Unit Kerja: </label>
                                    <input id="cabang" name="cabang" type="hidden" class="form-control"  value="<?php echo $cabangx; ?>" />
                                    <select <?php echo $is_disabled;?> id="cabangx" name="src_cabangx" class="form-control" >
                                        <!-- <option value=""> - Pilih -</option>-->
                                        <?php
                                        foreach ($cbx_cabang as $key => $row) {
                                            $cabangxx = isset($_POST['cabangx']) ? $_POST['cabangx'] : $cabangx;
                                            ?>
                                            <option value="<?php echo $row['id'] ?>" <?php if (isset($cabangxx) && $cabangxx == $row['id']) { echo 'selected'; } ?> > <?php echo $row["nama"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>   
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Periode Tanggal:</label>
                                    <div class="input-group date">
                                        <input type="text" id="daterange" name="daterange" class="form-control" placeholder="">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                    </div>
                                    <input type="hidden" id="src_date1" name="src_date1" class="form-control" />
                                    <input type="hidden" id="src_date2" name="src_date2" class="form-control" />
                                </div>
                            </div>
                            
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label> &nbsp; &nbsp; &nbsp; </label>
                                    <button type="submit" id="src_cari" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label> &nbsp; &nbsp; &nbsp; </label>
                                    <a id="btn-print" onClick="printHtml();"  class="btn btn-warning pull-right btn-sm"><i class="fa fa-print"></i> Print</a> 
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                
                <div class="table-responsive box-report">
                    <section id="print_area" class="print_area">

                        <div class="report-title2">LAPORAN POSISI SALDO KAS BANK</div>
                        <div class="report-title2"><?php echo $this->config->item('app_name_lite');?> <?php echo strtoupper($data_cabang['nama']); ?></div>
                        <div class="report-title3"><?php echo 'PERIODE '. $src_date1; ?> s/d <?php echo $src_date2; ?></div>
                        <br>
                        <table class="table-report">
                            <thead>
                                    <tr>
                                    <th>No</th>
                                    <th>Akun Kas</th>
                                    <th>Saldo Awal</th>
                                    <th>Masuk</th>
                                    <th>Keluar</th>
                                    <th>Saldo Akhir</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                if($data_report){
                                    $no = 1;
                                    foreach($data_report as $row){
                                        
                                        ?>
                                        <tr>
                                            <td class="xcenter"><?php echo $no;?></td>
                                            <td><?php echo $row['deskripsi'];?></td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($row['saldo_awal']);?></td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($row['masuk']);?></td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($row['keluar']);?></td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($row['saldo_awal'] + $row['masuk'] - $row['keluar']);?></td>
                                        </tr>
                                        <?php

                                        $no++;
                                    }
                                    
                                    ?>
                                    
                                <?php
                                }

                                ?>
                            </tbody>
                        </table>

                        <br>
                            
                    </section>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
    $('#daterange').daterangepicker({ startDate: '<?php echo date('01/m/Y') ?>', endDate: '<?php echo date('t/m/Y') ?>' });
    $("#src_date1").val('<?php echo date('Y-m-01') ?>');
    $("#src_date2").val('<?php echo date('Y-m-t') ?>');
    
    //$('.select2').val(null).trigger('change');
    $('.select2').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        allowClear: true,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    
    $(document).on('change', '#cabangx', function () {
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            $('#src_cari').trigger('click');
        }
    });
 
});

function printHtml() {
    printJS({
        printable: 'print_area',
        type: 'html',
        css: '<?php echo base_url(); ?>assets/css/custom_print.css',
        scanStyles: false
    })
}

</script>