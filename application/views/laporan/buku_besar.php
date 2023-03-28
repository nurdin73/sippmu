

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
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/buku_besar"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/buku_besar') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                                        <option value=""> - Pilih -</option>
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
                        <?php
                        $src_cabangx = $this->input->post('src_cabangx') ? $this->input->post('src_cabangx') : $cabangx;
                        $src_rekening = $this->input->post('src_rekening') ? $this->input->post('src_rekening') : '';
                        $src_bulan = $this->input->post('src_bulan') ? $this->input->post('src_bulan') : date('m');
                        $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
                        
                        if(!empty($src_rekening)){
                            $data_rekening = $this->report_model->getKodeRekening($src_rekening);
                        }else{
                            $data_rekening = $this->report_model->getKodeRekening();
                        }
                        
                        foreach($data_rekening as $rek){
                            $total_debet = 0;
                            $total_kredit = 0;
                            $saldo_berjalan = 0;
                            $saldo_awal = 0;
                            $saldo_akhir = 0;
                            $saldo = $this->report_model->getSaldo($src_cabangx, $rek['id'], $src_bulan, $src_tahun);
                            if($saldo){
                                $saldo_awal = str_replace('.00','',$saldo['saldo_bulan_lalu']);
                                $saldo_akhir = str_replace('.00','',$saldo['saldo']);
                            }
                            if(empty($saldo_awal)){
                                $saldo_awal = 0;
                            }
                            if(empty($saldo_akhir)){
                                $saldo_akhir = 0;
                            }
                            ?>
                        
                            <table class="table-report-title">
                                <tbody>
                                    <tr>
                                        <td class="kodex" >Kode Rekening</td>
                                        <td style="width:1%;">:</td>
                                        <td class="kodex2"><?php echo $rek['kode'];?></td>
                                        <td style="width:5%;">Bulan</td>
                                        <td style="width:1%;">:</td>
                                        <td style="width:12%;"><?php echo $this->customlib->getMonthByCode($src_bulan); ?></td>
                                    </tr>
                                    <tr>
                                        <td>Nama Rekening</td>
                                        <td>:</td>
                                        <td><?php echo $rek['nama']; ?></td>
                                        <td>Tahun</td>
                                        <td>:</td>
                                        <td><?php echo $src_tahun; ?></td>
                                    </tr>
                                    <tr>
                                        <td class="saldo_awal">Saldo Awal</td>
                                        <td class="saldo_awal">:</td>
                                        <td class="saldo_awal" ><?php 
                                            if($saldo_awal < 0){
                                                $saldo_awal = $saldo_awal * -1;
                                                $is_dk = '(K)';
                                            }else{
                                                $is_dk = '(D)';
                                            }
                                            echo $this->customlib->numberFormatId($saldo_awal).' '.$is_dk;?>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>

                            <table class="table-report">
                                <thead>
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>No Jurnal</th>
                                        <th>Keterangan</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $data_bukubesar = $this->report_model->getDataBukuBesar($src_cabangx, $rek['id'], $src_bulan, $src_tahun);
                            
                                    if($data_bukubesar){
                                        $total_debet = 0;
                                        $total_kredit = 0;
                                        foreach($data_bukubesar as $row){
                                            ?>
                                            <tr>
                                                <td><?php echo date('d-m-Y', strtotime($row['tanggal']));?></td>
                                                <td><?php echo $row['no_jurnal'];?></td>
                                                <td><?php echo $row['keterangan'];?></td>
                                                <td class="xright"><?php echo $this->customlib->numberFormatId($row['debet']);?></td>
                                                <td class="xright"><?php echo $this->customlib->numberFormatId($row['kredit']);?></td>
                                            </tr>
                                            <?php
                                            $total_debet += $row['debet'];
                                            $total_kredit += $row['kredit'];
                                        }
                                        ?>
                                        <tr>
                                            <td colspan="3" class="xbold xright">Total</td>
                                            <td class="xbold xright"><?php echo $this->customlib->numberFormatId($total_debet);?></td>
                                            <td class="xbold xright"><?php echo $this->customlib->numberFormatId($total_kredit);?></td>
                                        </tr>
                                    <?php
                                    }

                                    ?>
                                </tbody>
                            </table>
                        
                            <table class="table-report-akhir">
                                <tbody>
                                    <tr>
                                        <td class="xbold" style="width:11%;">Saldo Akhir</td>
                                        <td class="xbold" style="width:1%;">:</td>
                                        <td class="xbold" ><?php 
                                            if($saldo_akhir < 0){
                                                $saldo_akhir = $saldo_akhir * -1;
                                                $is_dk = '(K)';
                                            }else{
                                                $is_dk = '(D)';
                                            }
                                            echo $this->customlib->numberFormatId($saldo_akhir).' '.$is_dk;?>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td class="xbold" style="width:11%;">Balance</td>
                                        <td class="xbold" style="width:1%;">:</td>
                                        <td class="xbold" >
                                            <?php 
                                            //Jika (Saldo Awal + sum(Debet) - sum(Kredit) ) = Saldo Akhir -> Balance
                                            //Jika (Saldo Awal + sum(Debet) - sum(Kredit) ) <> Saldo Akhir -> Not Balance || Nominal Selisih
                                            $saldo_berjalan = $saldo_awal + $total_debet - $total_kredit;
                                            if($saldo_berjalan < 0){
                                                $saldo_berjalan = $saldo_berjalan * -1;
                                            }
                            
                                            if($saldo_berjalan == $saldo_akhir){
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
                        <?php
                        }
                        ?>
                    
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


