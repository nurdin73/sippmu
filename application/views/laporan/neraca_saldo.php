
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
                    <li class="breadcrumb-item"><a href="#!">Laporan Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/neraca_saldo"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/neraca_saldo') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                            <div class="col-md-5">
                                <div class="form-group"> 
                                    <label for="src_rekening">Kode Rekening: </label>
                                    <select id="src_rekening" name="src_rekening" class="form-control select2" >
                                        <option value=""> - Semua Rekening -</option>
                                        <?php
                                        foreach ($cbx_rekening as $key => $row) {
                                            $src_rekening = isset($_POST['src_rekening']) ? $_POST['src_rekening'] : '';
                                            ?>
                                            <option value="<?php echo $row['id'] ?>" <?php if (isset($src_rekening) && $src_rekening == $row['id']) { echo 'selected'; } ?> > <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>   
                            </div>
                         
                        </div>
                        <div class="row">

                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_bulan">Bulan </label>
                                    <select  id="src_bulan" name="src_bulan" class="form-control" >
                                        <!--<option value="">- Pilih -</option>-->
                                        <?php
                                        foreach ($cbx_bulan as $key => $month) {
                                            $src_bulan = isset($_POST['src_bulan']) ? $_POST['src_bulan'] : date('m');
                                            ?>
                                            <option value="<?php echo $key ?>" <?php if (isset($src_bulan) && $src_bulan == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <!-- <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_bulan">Tahun </label>
                                    <select  id="src_tahun" name="src_tahun" class="form-control" >
                                        <?php
                                        if(isset($cbx_tahun)) {
                                            foreach ($cbx_tahun as $thn) {
                                                //$src_tahun = isset($_POST['src_tahun']) ? $_POST['src_tahun'] : date('Y');
                                                ?>
                                                <option value="<?php echo $thn['tahun'] ?>" <?php if (isset($src_tahun) && $src_tahun == $thn['tahun']) { echo 'selected'; } ?>> <?php echo $thn['tahun'] ?></option>
                                            <?php }
                                        }
                                        ?>
                                    </select>
                                </div>
                            </div> -->
                            <div class="col-md-2">
                                <div class="form-group"> 
                                    <label for="src_level">Level: </label>
                                    <select id="src_level" name="src_level" class="form-control" >
                                        <option value=""> - Semua Level -</option>
                                        <?php
                                        foreach ($cbx_level as $key => $row) {
                                            $src_level = isset($_POST['src_level']) ? $_POST['src_level'] : '';
                                            ?>
                                            <option value="<?php echo $row['level'] ?>" <?php if (isset($src_level) && $src_level == $row['level']) { echo 'selected'; } ?> > <?php echo $row["level"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>   
                            </div>

                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="src_cari"> &nbsp; &nbsp; &nbsp; </label>
                                    <button type="submit" id="src_cari" class="btn btn-primary pull-right btn-sm"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></button>
                                </div>
                            </div>
                            <div class="col-md-1">
                                <div class="form-group">
                                    <label for="print"> &nbsp; &nbsp; &nbsp; </label>
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
                        <div class="report-title">
                            <img src="<?php echo base_url();?>assets/images/logo-report.jpg" style="float:left; margin: 0 15px 0 0; height:100px;" >
                            <div class="report-title1"><?php echo $this->config->item('report_header1');?></div>
                            <div class="report-title2">LAPORAN NERACA SALDO</div>
                            <div class="report-title3"><?php echo strtoupper($data_cabang['nama']); ?></div>
                            <div class="report-title4"><?php echo 'PERIODE '. strtoupper($this->customlib->getMonthByCode($src_bulan)); ?> <?php echo $src_tahun; ?></div>
                            <div style="clear:both;">
                        </div>

                        <!-- <div class="double-underline"></div> -->

                        <table class="table-report">
                            <thead>
                                <tr>
                                    <th>Nama Rekening</th>
                                    <th>Level</th>
                                    <th>Saldo Bulan Lalu</th>
                                    <th>Posisi</th>
                                    <th>Mutasi Debet</th>
                                    <th>Mutasi Kredit</th>
                                    <th>Saldo Bulan Ini</th>
                                    <th>Posisi</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if($data_neracasaldo){
                                    $total_debet = 0;
                                    $total_kredit = 0;
                                    $total_saldo_bulan_lalu = 0;
                                    $total_saldo = 0;
                                    
                                    foreach($data_neracasaldo as $row){
                                        $xbold = '';
                                        if($row['level'] == 1 || $row['level'] == 2){
                                            $xbold = 'xbold';
                                        }
                                        ?>
                                        <tr>
                                            <td class="<?php echo $xbold;?>"><?php 
                                                $spaces = '';
                                                if($row['level'] == 1){
                                                    $spaces = '<span class="pdl-1"></span>';
                                                }
                                                if($row['level'] == 2){
                                                    $spaces = '<span class="pdl-2"></span>';
                                                }
                                                if($row['level'] == 3){
                                                    $spaces = '<span class="pdl-3"></span>';
                                                }
                                                if($row['level'] == 4){
                                                    $spaces = '<span class="pdl-4"></span>';
                                                }
                                                if($row['level'] == 5){
                                                    $spaces = '<span class="pdl-5"></span>';
                                                }
                                                if($row['level'] == 6){
                                                    $spaces = '<span class="pdl-6"></span>';
                                                }

                                                echo $spaces.$row['kode'].' '.$row['nama'];?>
                                            </td>
                                            <td class="<?php echo $xbold;?> xcenter"><?php echo $row['level'];?></td>
                                            <td class="<?php echo $xbold;?> xright"><?php 
                                                if($row['saldo_bulan_lalu'] < 0){
                                                    $saldo_bulan_lalu = $row['saldo_bulan_lalu'] * -1;
                                                }else{
                                                    $saldo_bulan_lalu = $row['saldo_bulan_lalu'];
                                                }
                                                echo $this->customlib->numberFormatId($saldo_bulan_lalu);?>
                                            </td>
                                            <td class="<?php echo $xbold;?> xcenter"><?php echo ($row['saldo_bulan_lalu'] > 0) ? 'D' : 'K';?></td>
                                            <td class="<?php echo $xbold;?> xright"><?php 
                                                if($row['mutasi_debet'] < 0){
                                                    $mutasi_debet = $row['mutasi_debet'] * -1;
                                                }else{
                                                    $mutasi_debet = $row['mutasi_debet'];
                                                }
                                                echo $this->customlib->numberFormatId($mutasi_debet);?>
                                            </td>
                                            <td class="<?php echo $xbold;?> xright"><?php 
                                                if($row['mutasi_kredit'] < 0){
                                                    $mutasi_kredit = $row['mutasi_kredit'] * -1;
                                                }else{
                                                    $mutasi_kredit = $row['mutasi_kredit'];
                                                }
                                                echo $this->customlib->numberFormatId($mutasi_kredit);?>
                                            </td>
                                            <td class="<?php echo $xbold;?> xright"><?php 
                                                if($row['saldo'] < 0){
                                                    $saldo = $row['saldo'] * -1;
                                                }else{
                                                    $saldo = $row['saldo'];
                                                }
                                                echo $this->customlib->numberFormatId($saldo);?>
                                            </td>
                                            <td class="<?php echo $xbold;?> xcenter"><?php echo $row['posisi'];?></td>
                                        </tr>
                                        <?php
                                        if(empty($src_level)){
                                            if($row['level'] == 5){
                                                $total_debet += $row['mutasi_debet'];
                                                $total_kredit += $row['mutasi_kredit'];
                                                $total_saldo_bulan_lalu += $row['saldo_bulan_lalu'];
                                                $total_saldo += $row['saldo'];
                                            }
                                        }else{
                                            $total_debet += $row['mutasi_debet'];
                                            $total_kredit += $row['mutasi_kredit'];
                                            $total_saldo_bulan_lalu += $row['saldo_bulan_lalu'];
                                            $total_saldo += $row['saldo'];
                                        }
                                    }
                                    ?>
                                    
                                <?php
                                }

                                ?>
                            </tbody>
                        </table>

                        <table class="table-report-akhir">
                            <tbody>
                                <tr>
                                    <td class="xbold" style="width:16%;">Total Saldo Bulan Lalu</td>
                                    <td class="xbold" style="width:1%;">:</td>
                                    <td class="xbold" >Rp. <?php 
                                        if($total_saldo_bulan_lalu < 0){
                                            $total_saldo_bulan_lalu = $total_saldo_bulan_lalu * -1;
                                            $is_dk = '(D)';
                                        }else{
                                            $is_dk = '(D)';
                                        }
                                        echo $this->customlib->numberFormatId($total_saldo_bulan_lalu).' '.$is_dk;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="xbold">Total Mutasi Debet</td>
                                    <td class="xbold">:</td>
                                    <td class="xbold" >Rp. <?php echo $this->customlib->numberFormatId($total_debet);?></td>
                                </tr>
                                <tr>
                                    <td class="xbold">Total Mutasi Kredit</td>
                                    <td class="xbold">:</td>
                                    <td class="xbold" >Rp. <?php echo $this->customlib->numberFormatId($total_kredit);?></td>
                                </tr>
                                <tr>
                                    <td class="xbold">Total Saldo</td>
                                    <td class="xbold">:</td>
                                    <td class="xbold" >Rp. <?php 
                                        if($total_saldo < 0){
                                            $total_saldo = $total_saldo * -1;
                                            $is_dk = '(D)';
                                        }else{
                                            $is_dk = '(D)';
                                        }
                                        echo $this->customlib->numberFormatId($total_saldo).' '.$is_dk;?>
                                    </td>
                                </tr>
                                <tr>
                                    <td class="xbold">Balance</td>
                                    <td class="xbold">:</td>
                                    <td class="xbold" >
                                        <?php 
                                        if($total_debet == $total_kredit){
                                            echo '<font color="blue">Balance</font>';
                                        }else{
                                            echo '<font color="red">Not Balance</font>  ';
                                        }

                                        ?>
                                    </td>
                                </tr>
                                <tr>
                                    <td colspan="3">&nbsp;</td>
                                </tr>
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
    
//    $(document).on('change', '#cabangx', function () {
//        let src_cabangx = $('#cabangx option:selected').val();
//        if(src_cabangx != ''){
//            
//        }
//    });
 
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


