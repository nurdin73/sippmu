
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
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/arus_kas_tahun"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/arus_kas_tahun') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                                        <option value=""> - Semua Unit Kerja -</option>
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
                                    <label for="src_tahun">Tahun </label>
                                    <select  id="src_tahun" name="src_tahun" class="form-control" >
                                        <option value=""> - Pilih - </option>
                                        <?php
                                        if($cbx_tahun) {
                                            foreach ($cbx_tahun as $thn) {
                                                $src_tahun = isset($_POST['src_tahun']) ? $_POST['src_tahun'] : date('Y');
                                                ?>
                                                <option value="<?php echo $thn['tahun'] ?>" <?php if (isset($src_tahun) && $src_tahun == $thn['tahun']) { echo 'selected'; } ?>> <?php echo $thn['tahun'] ?></option>
                                            <?php }
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
                
                <div class="table-responsive box-report col-md-10">
                    <section id="print_area" class="print_area"> 
                        <div class="report-title">
                            <img src="<?php echo base_url();?>assets/images/logo-report.jpg" style="float:left; margin: 0 15px 0 0; height:100px;" >
                            <div class="report-title1"><?php echo $this->config->item('report_header1');?></div>
                            <div class="report-title2">LAPORAN ARUS KAS TAHUNAN</div>
                            <div class="report-title3"><?php echo strtoupper($data_cabang['nama']); ?></div>
                            <div class="report-title4">PERIODE <?php echo $src_tahun; ?></div>
                            <div style="clear:both;">
                        </div>

                        <div class="double-underline"></div>

                        <table class="table-report">
                            <tbody>
                                <?php
                                //echo '<pre>';print_r($data_report);die();
                                
                                $sisa_saldo1 = 0;
                                $sisa_saldo2 = 0;
                                $sisa_saldo3 = 0;
                                
                                if($data_arus_kas){
                                    $saldo_awal = 0;
                                    $no = 0;
                                    foreach($data_arus_kas as $row){
                                        $kodex = substr($row['kode'],0,1);
                                        $kodex3 = substr($row['kode'],0,3);
                                        $kodex3x = substr($row['kode'],2,1);
                                        
                                        if($row['level'] == 1){
                                            $no++;
                                            $nomor = $no;
                                            $space_no = 'xbold';
                                            $space = 'xbold';
                                            $xbgcolor = '';
                                        }else if($row['level'] == 2){
                                            $nomor = '';
                                            $space_no = '';
                                            $space = 'xbold';
                                            $xbgcolor = 'xbg-grey';
                                        }else if($row['level'] == 3){
                                            $nomor = '';
                                            $space_no = '';
                                            $space = 'xnext-30';
                                            $xbgcolor = 'xbg-grey';
                                        }else if($row['level'] == 4){
                                            $nomor = '';
                                            $space_no = '';
                                            $space = 'xnext-60';
                                            $xbgcolor = '';
                                        }else if($row['level'] == 5){
                                            $nomor = '';
                                            $space_no = '';
                                            $space = 'xnext-90';
                                            $xbgcolor = '';
                                        }
                                        
                                        if($row['is_sub_total'] == 'f'){
                                        ?>
                                            <tr>
                                                <td class=" <?php echo $space;?>"><?php echo $row['nama'];?></td>
                                                <td class="xright <?php echo $xbgcolor;?>">
                                                    <?php 
                                                    $jumlah = $data_report[$row['id']][$kodex]['saldo'];
                                                    if($jumlah < 0){
                                                        $jumlah = ($jumlah * -1);
                                                    }
                                                    echo isset($jumlah) ? $this->customlib->numberFormatId($jumlah) : 0;?>
                                                </td>
                                            </tr>
                                        
                                        <?php
                                        }else{
                                            
                                            if($row['kode'] == '1.9'){
                                            ?>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="xbold">Saldo Kas dari Aktivitas Operasi</td>
                                                    <td class="xbold xright xbg-grey">
                                                    <?php 
                                                        $tot_debet1 = isset($data_report['sisa_saldo']['1.1']) ? $data_report['sisa_saldo']['1.1'] : 0;
                                                        $tot_kredit1 = isset($data_report['sisa_saldo']['1.2']) ? $data_report['sisa_saldo']['1.2'] : 0;
                                                    
                                                        if($tot_debet1 < 0){
                                                            $tot_debet1 = ($tot_debet1 * -1);
                                                        }
                                                        $sisa_saldo1 = $tot_debet1 - $tot_kredit1;
                                                        
                                                        echo $this->customlib->numberFormatId($sisa_saldo1);
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }else if($row['kode'] == '2.9'){
                                            ?>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="xbold">Saldo Kas dari Aktivitas Investasi</td>
                                                    <td class="xbold xright xbg-grey">
                                                    <?php 
                                                        $tot_debet2 = isset($data_report['sisa_saldo']['2.1']) ? $data_report['sisa_saldo']['2.1'] : 0;
                                                        $tot_kredit2 = isset($data_report['sisa_saldo']['2.2']) ? $data_report['sisa_saldo']['2.2'] : 0;

                                                        if($tot_debet2 < 0){
                                                            $tot_debet2 = ($tot_debet2 * -1);
                                                        }
                                                        $sisa_saldo2 = $tot_debet2 - $tot_kredit2;
                                                        
                                                        echo $this->customlib->numberFormatId($sisa_saldo2);
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }else if($row['kode'] == '3.9'){
                                            ?>
                                                <tr>
                                                    <td>&nbsp;</td>
                                                    <td>&nbsp;</td>
                                                </tr>
                                                <tr>
                                                    <td class="xbold">Saldo Kas dari Aktivitas Pendanaan</td>
                                                    <td class="xbold xright xbg-grey">
                                                    <?php 
                                                        $tot_debet3 = isset($data_report['sisa_saldo']['3.1']) ? $data_report['sisa_saldo']['3.1'] : 0;
                                                        $tot_kredit3 = isset($data_report['sisa_saldo']['3.2']) ? $data_report['sisa_saldo']['3.2'] : 0;

                                                        if($tot_debet3 < 0){
                                                            $tot_debet3 = ($tot_debet3 * -1);
                                                        }
                                                        $sisa_saldo3 = $tot_debet3 - $tot_kredit3;
                                                        
                                                        echo $this->customlib->numberFormatId($sisa_saldo3);
                                                    ?>
                                                    </td>
                                                </tr>
                                            <?php
                                            }
                                            ?>

                                            <tr>
                                                <td style="border-bottom:1px solid #000">&nbsp;</td>
                                                <td style="border-bottom:1px solid #000">&nbsp;</td>
                                            </tr>
                                            <tr>
                                                <td>&nbsp;</td>
                                                <td>&nbsp;</td>
                                            </tr>
                                            <?php
                                        }
                                        
                                        $kas_setara_kas_bersih = $sisa_saldo1 + $sisa_saldo2 + $sisa_saldo3;
                                        $kas_setara_kas_akhir = $data_saldo_awal + $kas_setara_kas_bersih;
                                    }
                                    ?>
                                
                                    <tr>
                                        <td class="xbold">Kenaikan (Penurunan) Bersih Kas dan Setara Kas</td>
                                        <td class="xbold xright xbg-grey"><?php echo $this->customlib->numberFormatId($kas_setara_kas_bersih);?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="xbold">Kas dan Setara Kas Awal Periode</td>
                                        <td class="xbold xright xbg-grey"><?php echo $this->customlib->numberFormatId($data_saldo_awal);?></td>
                                    </tr>
                                    <tr>
                                        <td>&nbsp;</td>
                                        <td>&nbsp;</td>
                                    </tr>
                                    <tr>
                                        <td class="xbold">Kas dan Setara Kas Akhir Periode</td>
                                        <td class="xbold xright xbg-grey"><?php echo $this->customlib->numberFormatId($kas_setara_kas_akhir);?></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">&nbsp;</td>
                                    </tr>
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


