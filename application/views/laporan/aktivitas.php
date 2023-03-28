
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
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/aktivitas"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/aktivitas') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                                    <a id="btn-print" onClick="printHtml();" class="btn btn-warning pull-right btn-sm"><i class="fa fa-print"></i> Print</a> 
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
                            <div class="report-title2">LAPORAN AKTIVITAS</div>
                            <div class="report-title3"><?php echo strtoupper($data_cabang['nama']); ?></div>
                            <div class="report-title4"><?php echo 'PERIODE '. strtoupper($this->customlib->getMonthByCode($src_bulan)); ?> <?php echo $src_tahun; ?></div>
                            <div style="clear:both;">
                        </div>

                        <!-- <div class="double-underline"></div> -->

                        <table class="table-report">
                            <thead>
                                <tr>
                                    <th>NAMA PERKIRAAN</th>
                                    <th>TOTAL</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                //echo '<pre>';print_r($data_report);die();
                                
                                if($data_report){

                                    $array_data = [];
                                    $subtotal = array();
                                    $subtotal_1x = 0;
                                    $subtotal_2x = 0;
                                    $subtotal_3x = 0;
                                    $subtotal_4x = 0;
                                    $subtotal_7x = 0;
                                    $subtotal_8x = 0;
                                    foreach($data_report as $row){
                                        
                                        //mutlakan Kredit
                                        //if($row['posisi'] == 'K'){
                                            if($row['saldo'] < 0){
                                                $row['saldo'] = $row['saldo'] * -1;
                                            }
                                        //}

                                        $array_data[$row['kode']] = $row['saldo'];

                                        if($row['is_sub_total'] == 1){
                                            
                                            if($row['kode'] == '1.1x'){
                                                $saldo = $subtotal_1x;
                                            }
                                            if($row['kode'] == '1.2x'){
                                                $saldo = $subtotal_2x;
                                            }
                                            if($row['kode'] == '1.3x'){
                                                $saldo = $subtotal_3x;
                                            }
                                            if($row['kode'] == '1.4x'){
                                                $saldo = $subtotal_4x;
                                            }
                                            if($row['kode'] == '7x'){
                                                $saldo = $subtotal_7x;
                                            }
                                            if($row['kode'] == '8x'){
                                                $saldo = $subtotal_8x;
                                            }
                                            if($row['kode'] == '1.1x + 1.2x'){
                                                $saldo = $array_data['1.1x'] + $array_data['1.2x'];
                                            }
                                            if($row['kode'] == '1.3x + 1.4x'){
                                                $saldo = $array_data['1.3x'] + $array_data['1.4x'];
                                            }
                                            if($row['kode'] == 'SUB1'){
                                                //(1.1x+1.2x) - (1.3x+1.4x)
                                                $saldo = $array_data['1.1x + 1.2x'] - $array_data['1.3x + 1.4x'];
                                            }
                                            if($row['kode'] == 'SUB2'){
                                                //2.1.1 - 2.1.2
                                                $saldo = $array_data['2.1.1'] - $array_data['2.1.2'];
                                            }
                                            if($row['kode'] == 'SUB3'){
                                                //3.1.1 - 3.1.2
                                                $saldo = $array_data['3.1.1'] - $array_data['3.1.2'];
                                            }
                                            if($row['kode'] == 'SUB1+SUB2+SUB3'){
                                                //SUB1+SUB2+SUB3
                                                $saldo = $array_data['SUB1'] + $array_data['SUB2'] + $array_data['SUB3'];
                                            }

                                            // $formula = explode('+', $row['keterangan']);
                                            // //echo '<pre>';print_r($formula);
                                            // if($formula){
                                            //     foreach($formula as $kod){

                                            //     }
                                            // }

                                            $array_data[$row['kode']] = $saldo;

                                        }else{
                                            $saldo = $row['saldo'];
                                            if($row['level'] == 3){
                                                if(substr($row['kode'],0,3) == '1.1'){
                                                    $subtotal_1x += $row['saldo'];
                                                }
                                                if(substr($row['kode'],0,3) == '1.2'){
                                                    $subtotal_2x += $row['saldo'];
                                                }
                                                if(substr($row['kode'],0,3) == '1.3'){
                                                    $subtotal_3x += $row['saldo'];
                                                }
                                                if(substr($row['kode'],0,3) == '1.4'){
                                                    $subtotal_4x += $row['saldo'];
                                                }
                                                if(substr($row['kode'],0,1) == '7'){
                                                    $subtotal_7x += $row['saldo'];
                                                }
                                                if(substr($row['kode'],0,1) == '8'){
                                                    $subtotal_8x += $row['saldo'];
                                                }
                                                
                                            }

                                        }
                                        
                                        $space = '';
                                        $id_disable = '';
                                        if($row['level'] == 1){
                                            $space = '';
                                        }
                                        if($row['level'] == 2){
                                            $space = '&nbsp; &nbsp; &nbsp; ';
                                        }
                                        if($row['level'] == 3){
                                            $space = '&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; ';
                                        }

                                        ?>
                                            <tr>
                                                <td class="pad4"><?php echo $space . $row['nama'];?></td>
                                                
                                                <td class="pad4 xright"><?php echo $this->customlib->numberFormatId($saldo);?></td>
                                            </tr>
                                        <?php
                                        if($row['is_space_after'] == '1'){ 
                                            ?>
                                            <tr>
                                                <td class="xbold"> &nbsp;</td>
                                                <td class="xright"> &nbsp;</td>
                                            </tr>
                                            <?php
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
                                    <td colspan="2">&nbsp;</td>
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

<script type='text/javascript'>
$(document).ready(function () {
    
    $('.select2').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        allowClear: true,
        dropdownCssClass: "bigdrop",
        escapeMarkup: function(m) { 
           return m; 
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


