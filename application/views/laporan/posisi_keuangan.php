
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
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/posisi_keuangan"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/posisi_keuangan') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                                        <option value=""> - Pilih Unit Kerja -</option>
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
                            
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_bulan">Bulan </label>
                                    <select  id="src_bulan" name="src_bulan" class="form-control" >
                                        <option value="">- Semua -</option>
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_bulan">Tahun </label>
                                    <select  id="src_tahun" name="src_tahun" class="form-control" >
                                        <?php
                                        if($cbx_tahun) {
                                            foreach ($cbx_tahun as $thn) {
                                                //$src_tahun = isset($_POST['src_tahun']) ? $_POST['src_tahun'] : date('Y');
                                                ?>
                                                <option value="<?php echo $thn['tahun'] ?>" <?php if (isset($src_tahun) && $src_tahun == $thn['tahun']) { echo 'selected'; } ?>> <?php echo $thn['tahun'] ?></option>
                                            <?php }
                                        }else{
                                            $year_now = date('Y');
                                            ?>
                                            <option value="<?php echo $year_now; ?>" <?php if (isset($src_tahun) && $src_tahun == $year_now) { echo 'selected'; } ?>> <?php echo $year_now; ?></option>
                                            <?php
                                        }
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
                
                <div class="table-responsive box-report col-md-12">
                    <section id="print_area" class="print_area">
                        <div class="report-title">
                            <img src="<?php echo base_url();?>assets/images/logo-report.jpg" style="float:left; margin: 0 15px 0 0; height:100px;" >
                            <div class="report-title1"><?php echo $this->config->item('report_header1');?></div>
                            <div class="report-title2"><?php echo $laporan_name; ?></div>
                            <div class="report-title3"><?php echo strtoupper($data_cabang['nama']); ?></div>
                            <div class="report-title4"><?php echo 'PERIODE '; ?> <?php echo $src_tahun; ?></div>
                            <div style="clear:both;">
                        </div>

                        <div class="double-underline"></div>

                        <?php
                        $jumlah_aset = 0;
                        $jumlah_leabilitas = 0;
                        
                        $subtotal_1_1x = 0;
                        $subtotal_1_2x = 0;
                        $subtotal_1_3x = 0;
                        $subtotal_2_1x = 0;
                        $subtotal_2_2x = 0;
                        $subtotal_2_3x = 0;
                        ?>

                        <table style="width:100%;">
                            <tr>
                                <td valign="top" style="width:47%">

                                    <table class="table-report">
                                        <tbody>
                                        <?php
                                        if($data_report){
                                            foreach($data_report as $row){
                                                if($row['is_posisi'] == 'L'){
                                                    if($row['level'] == 1){
                                                        $xbold = 'xbold';
                                                        $xspace = '';
                                                        $saldox = '';
                                                    }else if($row['level'] == 2){
                                                        $xbold = 'xbold xsize12';
                                                        $xspace = '';
                                                        $saldox = '';
                                                        if($row['is_sub_total'] == '1'){
                                                            //rumus
                                                            $sub_totalx = 0;
                                                            if($row['is_sub_total'] == 1){
                                                    
                                                                if($row['kode'] == '1.1x'){
                                                                    $sub_totalx = $subtotal_1_1x;
                                                                }
                                                                if($row['kode'] == '1.2x'){
                                                                    $sub_totalx = $subtotal_1_2x;
                                                                }
                                                                if($row['kode'] == '1.3x'){
                                                                    $sub_totalx = $subtotal_1_3x;
                                                                }

                                                                if($row['kode'] == '2.1x'){
                                                                    $sub_totalx = $subtotal_2_1x;
                                                                }
                                                                if($row['kode'] == '2.2x'){
                                                                    $sub_totalx = $subtotal_2_2x;
                                                                }
                                                                if($row['kode'] == '2.3x'){
                                                                    $sub_totalx = $subtotal_2_3x;
                                                                }

                                                            }
                                                            
                                                            $saldox = $this->customlib->numberFormatId($sub_totalx);
                                                            $jumlah_aset += $sub_totalx;
                                                        }
                                                    }else if($row['level'] == 3){
                                                        $xbold = 'xsize11';
                                                        $xspace = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                        $saldox = $this->customlib->numberFormatId($row['saldo']);

                                                        if(substr($row['kode'],0,3) == '1.1'){
                                                            $subtotal_1_1x += $row['saldo'];
                                                        }
                                                        if(substr($row['kode'],0,3) == '1.2'){
                                                            $subtotal_1_2x += $row['saldo'];
                                                        }
                                                        if(substr($row['kode'],0,3) == '1.3'){
                                                            $subtotal_1_3x += $row['saldo'];
                                                        }

                                                    }

                                                    
                                                    ?>
                                                    <tr>
                                                        <td class="<?php echo $xbold;?>"><?php echo $xspace;?> <?php echo $row['nama'];?></td>
                                                        <td class="xright"><?php echo $saldox; ?></td>
                                                    </tr>
                                                    <?php
                                                    if($row['is_space_after'] == '1'){ 
                                                        ?>
                                                        <tr>
                                                            <td class=""> &nbsp;</td>
                                                            <td class=""> &nbsp;</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }
                                            }  
                                            
                                            $jumlah_aset = $subtotal_1_1x + $subtotal_1_2x + $subtotal_1_3x;

                                        }
                                        ?>
                                            
                                        </tbody>
                                    </table>
                                    
                                </td>
                                <td valign="top" style="width:5%">
                                    &nbsp;
                                </td>
                                <td valign="top" style="width:47%">
                                    <table class="table-report">
                                        <tbody>
                                        <?php
                                        if($data_report){
                                            foreach($data_report as $row){
                                                if($row['is_posisi'] == 'R'){
                                                    if($row['level'] == 1){
                                                        $xbold = 'xbold';
                                                        $xspace = '';
                                                        $saldox = '';
                                                    }else if($row['level'] == 2){
                                                        $xbold = 'xbold xsize12';
                                                        $xspace = '';
                                                        $saldox = '';
                                                        if($row['is_sub_total'] == '1'){
                                                            //rumus
                                                            $sub_totaly = 0;
                                                            if($row['is_sub_total'] == 1){
                                                    
                                                                if($row['kode'] == '1.1x'){
                                                                    $sub_totaly = $subtotal_1_1x;
                                                                }
                                                                if($row['kode'] == '1.2x'){
                                                                    $sub_totaly = $subtotal_1_2x;
                                                                }
                                                                if($row['kode'] == '1.3x'){
                                                                    $sub_totaly = $subtotal_1_3x;
                                                                }

                                                                if($row['kode'] == '2.1x'){
                                                                    $sub_totaly = $subtotal_2_1x;
                                                                }
                                                                if($row['kode'] == '2.2x'){
                                                                    $sub_totaly = $subtotal_2_2x;
                                                                }
                                                                if($row['kode'] == '2.3x'){
                                                                    $sub_totaly = $subtotal_2_3x;
                                                                }

                                                            }
                                                            
                                                            $saldox = $this->customlib->numberFormatId($sub_totaly);
                                                            $jumlah_leabilitas += $sub_totaly;
                                                        }
                                                    }else if($row['level'] == 3){
                                                        $xbold = 'xsize11';
                                                        $xspace = '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;';
                                                        $saldox = $this->customlib->numberFormatId($row['saldo']);

                                                        if(substr($row['kode'],0,3) == '2.1'){
                                                            $subtotal_2_1x += $row['saldo'];
                                                        }
                                                        if(substr($row['kode'],0,3) == '2.2'){
                                                            $subtotal_2_3x += $row['saldo'];
                                                        }
                                                        if(substr($row['kode'],0,3) == '2.3'){
                                                            $subtotal_2_3x += $row['saldo'];
                                                        }

                                                        $jumlah_leabilitas += $row['saldo'];
                                                    }
                                                    
                                                    ?>
                                                    <tr>
                                                        <td class="<?php echo $xbold;?>"><?php echo $xspace;?> <?php echo $row['nama'];?></td>
                                                        <td class="xright"><?php echo $saldox; ?></td>
                                                    </tr>
                                                    <?php
                                                    if($row['is_space_after'] == '1'){ 
                                                        ?>
                                                        <tr>
                                                            <td class=""> &nbsp;</td>
                                                            <td class=""> &nbsp;</td>
                                                        </tr>
                                                        <?php
                                                    }
                                                }

                                                $jumlah_leabilitas = $subtotal_2_1x + $subtotal_2_2x + $subtotal_2_3x;

                                            }  
                                        }
                                        ?>
                                            
                                        </tbody>
                                    </table>
                                    
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" colspan="3">
                                    <hr>
                                </td>
                            </tr>
                            <tr>
                                <td valign="top" style="width:47%">
                                    <table class="table-report">
                                        <tr>
                                            <td class="xbold">JUMLAH ASET</td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($jumlah_aset); ?></td>
                                        </tr>
                                    </table>
                                </td>
                                <td valign="top" style="width:5%">
                                    &nbsp;
                                </td>
                                <td valign="top" style="width:47%">
                                    <table class="table-report">
                                        <tr>
                                            <td class="xbold">JUMLAH LEABILITAS DAN ASET NETO</td>
                                            <td class="xright"><?php echo $this->customlib->numberFormatId($jumlah_leabilitas); ?></td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                            
                        </table>
                        
                    </section>
                </div>
                
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function () {
    
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


