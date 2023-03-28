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
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>laporan/pindah_buku"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('laporan/pindah_buku') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_akun_kas">Akun Kas Masuk</label>
                                    <select id="src_akun_kas" name="src_akun_kas" class="form-control">
                                        <option value="">- All -</option>
                                        <?php
                                        foreach ($cbx_akun_kas as $key => $row) {
                                            ?>
                                            <option value="<?php echo $row['id'] ?>" <?php if (isset($src_akun_kas) && $src_akun_kas == $row['id']) { echo 'selected'; } ?>> <?php echo $row["deskripsi"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="src_akun_kas2">Akun Kas Keluar</label>
                                    <select id="src_akun_kas2" name="src_akun_kas2" class="form-control">
                                        <option value="">- All -</option>
                                        <?php
                                        foreach ($cbx_akun_kas as $key => $row) {
                                            ?>
                                            <option value="<?php echo $row['id'] ?>" <?php if (isset($src_akun_kas2) && $src_akun_kas2 == $row['id']) { echo 'selected'; } ?>> <?php echo $row["deskripsi"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>
                            </div>
                            
                        </div>
                        <div class="row">

                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Pencarian:</label>
                                    <input type="text" id="src_text"name="src_text" class="form-control" value="<?php echo $src_text; ?>" placeholder="No Transaksi, No Jurnal">
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
                        
                        <div class="report-title2">LAPORAN PINDAH BUKU</div>
                        <div class="report-title2"><?php echo $this->config->item('app_name_lite');?> <?php echo strtoupper($data_cabang['nama']); ?></div>
                        <div class="report-title3"><?php echo 'PERIODE '. $src_date1; ?> s/d <?php echo $src_date2; ?></div>
                        <br>
                        <table class="table-report">
                            <thead>
                                    <tr>
                                    <th>No</th>
                                    <th>No Transaksi</th>
                                    <th>Tanggal</th>
                                    <th>Keterangan</th>
                                    <th>No Jurnal</th>
                                    <th>Petugas</th>
                                    <th>Akun Kas Masuk</th>
                                    <th>Akun Kas Keluar</th>
                                    <th>Jumlah</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php

                                if($data_pindah_buku){
                                    $total_rows = $data_pindah_buku['total'];
                                    $subtotal = array();
                                    $no_transaksi_array = array();
                                    $no_transaksi_before = '';
                                    $total_all = 0;
                                    $no = 1;
                                    foreach($data_pindah_buku['result'] as $row){
                                        if(empty($subtotal[$row['no_transaksi']])){
                                            $subtotal[$row['no_transaksi']] = 0;
                                        }
                                        $subtotal[$row['no_transaksi']] += $row['jumlah'];
                                        
                                        if($no_transaksi_before != '' && $no_transaksi_before != $row['no_transaksi']){
                                            ?>
                                            <tr>
                                                <td class="xbold xright" colspan="8">Sub Total</td>
                                                <td class="xbold xright"><?php echo $this->customlib->numberFormatId($subtotal[$no_transaksi_before]);?></td>

                                            </tr>
                                            <?php
                                        }
                                        
                                        if(!in_array($row['no_transaksi'], $no_transaksi_array)){
                                            $transaksi_push = array_push($no_transaksi_array, $row['no_transaksi']);
                                            
                                            ?>
                                            <tr>
                                                <td class="xcenter"><?php echo $no;?></td>
                                                <td class="xcenter"><?php echo $row['no_transaksi'];?></td>
                                                <td class="xcenter"><?php echo date('d-m-Y H:i:s', strtotime($row['tanggal']));?></td>
                                                <td class=""><?php echo $row['keterangan'];?></td>
                                                <td class="xcenter"><?php echo $row['no_jurnal'];?></td>
                                                <td class=""><?php echo $row['created_by'];?></td>
                                                <td class=""><?php echo $row['akun_mutasi_masuk'];?></td>
                                                <td class=""><?php echo $row['akun_mutasi_keluar'];?></td>
                                                <td class="xright"><?php echo $this->customlib->numberFormatId($row['jumlah']);?></td>

                                            </tr>
                                            <?php
                                            
                                        }else{
                                            
                                            ?>
                                            <tr>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td></td>
                                                <td><?php echo $row['akun_mutasi_masuk'];?></td>
                                                <td><?php echo $row['akun_mutasi_keluar'];?></td>
                                                <td class="xright"><?php echo $this->customlib->numberFormatId($row['jumlah']);?></td>

                                            </tr>
                                            <?php
                                        }
                                        
                                        if($total_rows == $no) {
                                            ?>
                                            <tr>
                                                <td class="xbold xright" colspan="8">Sub Total</td>
                                                <td class="xbold xright"><?php echo $this->customlib->numberFormatId($subtotal[$row['no_transaksi']]);?></td>

                                            </tr>
                                            <?php
                                        }
                                        
                                        
                                        $total_all += $row['jumlah'];
                                        $no_transaksi_before = $row['no_transaksi'];
                                        $no++;
                                    }
                                    ?>
                                    <tr>
                                        <td class="xbold xright" colspan="8"> Total</td>
                                        <td class="xbold xright"><?php echo $this->customlib->numberFormatId($total_all);?></td>

                                    </tr>
                                <?php
                                }

                                ?>
                            </tbody>
                        </table>

        <!--
                        <table class="table-report-akhir ">
                            <tbody>
                                <tr>
                                    <td colspan="6">&nbsp;</td>
                                </tr>
                                <tr>
                                    <td colspan="3" class="xcenter">
                                        <?php echo $data_cabang['nama'];?>, <?php echo date('d M Y');?>
                                        <br>
                                        <br>
                                        <br>
                                        (User)
                                    </td>
                                </tr>
                            </tbody>
                        </table>
        -->
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


