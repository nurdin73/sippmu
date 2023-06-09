
<style type="text/css">
    @media print {
        .col-sm-1, .col-sm-2, .col-sm-3, .col-sm-4, .col-sm-5, .col-sm-6, .col-sm-7, .col-sm-8, .col-sm-9, .col-sm-10, .col-sm-11, .col-sm-12 {
            float: left;
        }
        .col-sm-12 {
            width: 100%;
        }
        .col-sm-11 {
            width: 91.66666667%;
        }
        .col-sm-10 {
            width: 83.33333333%;
        }
        .col-sm-9 {
            width: 75%;
        }
        .col-sm-8 {
            width: 66.66666667%;
        }
        .col-sm-7 {
            width: 58.33333333%;
        }
        .col-sm-6 {
            width: 50%;
        }
        .col-sm-5 {
            width: 41.66666667%;
        }
        .col-sm-4 {
            width: 33.33333333%;
        }
        .col-sm-3 {
            width: 25%;
        }
        .col-sm-2 {
            width: 16.66666667%;
        }
        .col-sm-1 {
            width: 8.33333333%;
        }
        .col-sm-pull-12 {
            right: 100%;
        }
        .col-sm-pull-11 {
            right: 91.66666667%;
        }
        .col-sm-pull-10 {
            right: 83.33333333%;
        }
        .col-sm-pull-9 {
            right: 75%;
        }
        .col-sm-pull-8 {
            right: 66.66666667%;
        }
        .col-sm-pull-7 {
            right: 58.33333333%;
        }
        .col-sm-pull-6 {
            right: 50%;
        }
        .col-sm-pull-5 {
            right: 41.66666667%;
        }
        .col-sm-pull-4 {
            right: 33.33333333%;
        }
        .col-sm-pull-3 {
            right: 25%;
        }
        .col-sm-pull-2 {
            right: 16.66666667%;
        }
        .col-sm-pull-1 {
            right: 8.33333333%;
        }
        .col-sm-pull-0 {
            right: auto;
        }
        .col-sm-push-12 {
            left: 100%;
        }
        .col-sm-push-11 {
            left: 91.66666667%;
        }
        .col-sm-push-10 {
            left: 83.33333333%;
        }
        .col-sm-push-9 {
            left: 75%;
        }
        .col-sm-push-8 {
            left: 66.66666667%;
        }
        .col-sm-push-7 {
            left: 58.33333333%;
        }
        .col-sm-push-6 {
            left: 50%;
        }
        .col-sm-push-5 {
            left: 41.66666667%;
        }
        .col-sm-push-4 {
            left: 33.33333333%;
        }
        .col-sm-push-3 {
            left: 25%;
        }
        .col-sm-push-2 {
            left: 16.66666667%;
        }
        .col-sm-push-1 {
            left: 8.33333333%;
        }
        .col-sm-push-0 {
            left: auto;
        }
        .col-sm-offset-12 {
            margin-left: 100%;
        }
        .col-sm-offset-11 {
            margin-left: 91.66666667%;
        }
        .col-sm-offset-10 {
            margin-left: 83.33333333%;
        }
        .col-sm-offset-9 {
            margin-left: 75%;
        }
        .col-sm-offset-8 {
            margin-left: 66.66666667%;
        }
        .col-sm-offset-7 {
            margin-left: 58.33333333%;
        }
        .col-sm-offset-6 {
            margin-left: 50%;
        }
        .col-sm-offset-5 {
            margin-left: 41.66666667%;
        }
        .col-sm-offset-4 {
            margin-left: 33.33333333%;
        }
        .col-sm-offset-3 {
            margin-left: 25%;
        }
        .col-sm-offset-2 {
            margin-left: 16.66666667%;
        }
        .col-sm-offset-1 {
            margin-left: 8.33333333%;
        }
        .col-sm-offset-0 {
            margin-left: 0%;
        }
        .visible-xs {
            display: none !important;
        }
        .hidden-xs {
            display: block !important;
        }
        table.hidden-xs {
            display: table;
        }
        tr.hidden-xs {
            display: table-row !important;
        }
        th.hidden-xs,
        td.hidden-xs {
            display: table-cell !important;
        }
        .hidden-xs.hidden-print {
            display: none !important;
        }
        .hidden-sm {
            display: none !important;
        }
        .visible-sm {
            display: block !important;
        }
        table.visible-sm {
            display: table;
        }
        tr.visible-sm {
            display: table-row !important;
        }
        th.visible-sm,
        td.visible-sm {
            display: table-cell !important;
        }
    }
</style>

<html lang="en">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title>Laporan Penerimaan</title>
    </head>

    <div id="html-2-pdfwrapper">

        <div class="row">
            <!-- left column -->
            <div class="col-md-12">

                <div class="">
                    <div class="pprinta4">
                        <!-- header
                        <img src="" class="img-responsive" style="height:100px; width: 100%;">-->
                    </div>
                    
                    <table width="100%">
                        <tr>
                            <td align="center"><h3 style="margin: 10px 0 20px;">Laporan Penerimaan <?php echo $src_date1; ?> <?php echo $src_date2; ?></h3></td>
                        </tr>
                    </table>
                    <table width="100%" class="paytable2">
                        <tr>
                            <th></th> <td></td>
                            <th class="text-right"></th> 
                            <th class="text-right"> 
                                <?php echo $this->lang->line('date'); ?>: <?php echo $src_date1; ?>
                            </th>

                        </tr>
                    </table>
                    <hr/>
                    <table width="100%" class="paytable2" >

<!--
                        <tr>
                            <th width="25%"><?php echo $this->lang->line('staff_id'); ?></th>
                            <td width="25%"><?php echo $result["employee_id"] ?></td>
                            <th width="25%"><?php echo $this->lang->line("name"); ?></th>
                            <td width="25%"><?php echo $result["name"] . " " . $result["surname"] ?></td>
                        </tr>
                        <tr>
                            <th><?php echo $this->lang->line('department'); ?></th>
                            <td><?php echo $result["department"] ?></td>
                            <th><?php echo $this->lang->line('designation'); ?></th>
                            <td><?php echo $result["designation"] ?></td>
                        </tr>
-->
                    </table>
                    <br/>
                    <table class="earntable table table-striped table-responsive" >
                        <tr>
                            <th>No</th>
                            <th>No Transaksi</th>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>No Jurnal</th>
                            <th>Petugas</th>
                            <th>Jenis Penerimaan</th>
                            <th>Akun Kas</th>
                            <th>Jumlah</th>
                        </tr>
                        <?php

                        if($data_penerimaan){
                            $total_rows = $data_penerimaan['total'];
                            $subtotal = array();
                            $no_transaksi_array = array();
                            $no_transaksi_before = '';
                            $total_all = 0;
                            $no = 1;
                            foreach($data_penerimaan['result'] as $row){
                                if(empty($subtotal[$row['no_transaksi']])){
                                    $subtotal[$row['no_transaksi']] = 0;
                                }
                                $subtotal[$row['no_transaksi']] += $row['jumlah'];

                                if($no_transaksi_before != '' && $no_transaksi_before != $row['no_transaksi']){
                                    ?>
                                    <tr>
                                        <td class="xbold xright" colspan="7">Sub Total</td>
                                        <td></td>
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
                                        <td class="xcenter"><?php echo $row['tanggal'];?></td>
                                        <td class=""><?php echo $row['keterangan'];?></td>
                                        <td class="xcenter"><?php echo $row['no_jurnal'];?></td>
                                        <td class=""><?php echo $row['created_by'];?></td>
                                        <td class=""><?php echo $row['transaksi_nama'];?></td>
                                        <td class=""><?php echo $row['akun_kas_nama'];?></td>
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
                                        <td><?php echo $row['transaksi_nama'];?></td>
                                        <td><?php echo $row['akun_kas_nama'];?></td>
                                        <td class="xright"><?php echo $this->customlib->numberFormatId($row['jumlah']);?></td>

                                    </tr>
                                    <?php
                                }

                                if($total_rows == $no) {
                                    ?>
                                    <tr>
                                        <td class="xbold xright" colspan="7">Sub Total</td>
                                        <td></td>
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
                                <td class="xbold xright" colspan="7"> Total</td>
                                <td class="xbold xright" colspan="2"><?php echo $this->customlib->numberFormatId($total_all);?></td>

                            </tr>
                        <?php
                        }

                        ?>

                        
                    </table>   

                     
                    <!-- <p class="ptt10"><?php //echo $this->lang->line('computer_generated_payslip');     ?></p> -->
                    
                    <p>
                        <!-- footer-->
                        
                    </p>
                </div>
            </div>
            <!--/.col (left) -->

        </div>
    </div>

</html>