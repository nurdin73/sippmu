<!-- [ breadcrumb ] -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Mutasi Jurnal</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Transaksi Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>transaction/mutasi_jurnal">Mutasi Jurnal</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-header">
                <form action="<?php echo site_url('transaction/mutasi_jurnal') ?>"  name="search_form" method="post" accept-charset="utf-8">
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>        
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-md-5">
                            <div class="form-group"> 
                                <label for="tanggal">Unit Kerja:</label>
                                <input id="cabang" name="cabang" type="hidden" class="form-control"  value="<?php echo $cabangx; ?>" />
                                <select <?php echo $is_disabled;?> id="cabangx" name="cabangx" class="form-control" >
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
                        <div class="col-md-2">
                            <div class="form-group"> 
                                <label for="tanggal">Status:</label>
                                <select id="src_status" name="status" class="form-control">
                                    <option value=""> Semua Status </option>
                                    <option value="1"> Register</option>
                                    <option value="2"> Posting</option>
                                    <option value="3"> Batal</option>
                                </select>
                            </div>   
                        </div>
                        <div class="col-md-4">
                            <div class="form-group"> 
                                <label for="src_tipe_jurnal">Tipe Jurnal:</label>
                                <select id="src_tipe_jurnal" name="tipe_jurnal" class="form-control" >
                                        <option value=""> - Semua -</option>
                                    <?php
                                    foreach ($cbx_tipe_jurnal as $key => $row) {
                                        ?>
                                        <option value="<?php echo $row['kode'] ?>" <?php if (isset($_POST['tipe_jurnal']) && $_POST['tipe_jurnal'] == $row['kode']) { echo 'selected'; } ?> > <?php echo $row["singkatan"] ?> - <?php echo $row["nama"] ?></option>
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
                                <label for="tanggal">&nbsp; &nbsp;</label>
                                <a id="src_cari" class="btn btn-primary pull-right btn-sm text-white"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></a>
                            </div>
                        </div>
                        <div class="col-md-8 text-right">
                            <div class="form-group">
                                <label style="width:100%;">&nbsp; <br></label>
                                <?php if ($this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_add')) { ?>
                                    <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i> Tambah Mutasi Jurnal</a>    
                                <?php } ?> 
                            </div>
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="card-body">
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Mutasi Jurnal</div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>No Jurnal</th>
                                <th>Tanggal Jurnal</th>
                                <th>Status</th>
                                <th>Tanggal Posting</th>
                                <th>Tipe Jurnal</th>
                                <th>Jumlah</th>
                                <th>Keterangan</th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Tambah Mutasi Jurnal</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('transaction/mutasi_jurnal/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabangxx" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input disabled id="cabang_nama" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label><small class="req"> *</small>
                                    <input id="tanggalxx" name="tanggal" type="text" class="form-control datepicker"  value="" />
                                    <span class="text-danger"><?php echo form_error('tanggal'); ?></span>
                                </div>
                            </div>
                        </div>
                            
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group"> 
                                    <label for="tipe_jurnal">Tipe Jurnal:</label><small class="req"> *</small>
                                    <select id="tipe_jurnal" name="tipe_jurnal" class="form-control" >
                                         <option value=""> - Semua -</option>
                                        <?php
                                        foreach ($cbx_tipe_jurnal as $key => $row) {
                                            ?>
                                            <option value="<?php echo $row['kode'] ?>" <?php if (isset($_POST['tipe_jurnal']) && $_POST['tipe_jurnal'] == $row['kode']) { echo 'selected'; } ?> > <?php echo $row["singkatan"] ?> - <?php echo $row["nama"] ?></option>
                                        <?php }
                                        ?>
                                    </select>
                                </div>   
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea name="keterangan" placeholder="" class="form-control"></textarea>
                            <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" value="" />
                        <input type="button" class="btn btn-default pull-right" id="btn-close" style="margin-left:5px;" value="Tutup">
                        <button type="submit" id="btn-save-add" class="btn btn-info pull-right" > Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>


<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-dialog2 modal-lg">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Detail Mutasi Jurnal</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('transaction/mutasi_jurnal/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_det" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input readonly id="cabang_nama_det" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="no_jurnal">Nomor Jurnal</label>
                                    <input readonly id="no_jurnal_det" name="no_jurnal" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('no_jurnal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input id="tanggal_det" name="tanggal" type="text" class="form-control datepicker"  value="" />
                                    <span class="text-danger"><?php echo form_error('tanggal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tipe_jurnal">Tipe Jurnal</label>
                                    <input readonly id="tipe_jurnal_det" name="tipe_jurnal" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('tipe_jurnal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input readonly id="status_det" name="status" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea id="keterangan_det" name="keterangan" placeholder="" class="form-control" style="height:30px;"></textarea>
                                    <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"></h3>
                            <div class="box-total pull-left">
                                <table style="width:100%">
                                    <tr>
                                        <td style="width:280px;text-align:right; "><div id="total_debet_txt" class="total_debet btn btn-default btn-sm">Total Debet: Rp. 0 </div></td>
                                        <td style="width:320px;text-align:right; "><div id="total_kredit_txt" class="total_kredit btn btn-default btn-sm">Total Kredit: Rp. 0 </div></td>
                                        <td colspan="2">
                                            <input type="hidden" id="total_debet" name="total_debet" />
                                            <input type="hidden" id="total_kredit" name="total_kredit" />
                                        </td>
                                    </tr>
                                </table>
                            </div>
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_add')) { ?>
                                    <a onclick="getAddDetail()" class="btn btn-success btn-sm" id="btn_add_detail"><i class="fa fa-plus"></i>  Tambah Detail</a>    
                                <?php } ?> 
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped table-bordered" id="dt_table_det">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Rek</th>
                                        <th>Nama Rek</th>
                                        <th>Debet</th>
                                        <th>Kredit</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                            
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="jurnal_id" name="jurnal_id" value="" />
                        <input type="button" class="btn btn-danger pull-left" id="btn-closing2" value="Posting Jurnal">
                        <input type="button" class="btn btn-default pull-right" id="btn-close2" style="margin-left:5px;" value="Tutup">
                        <button type="submit" id="btn-save-edit" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>

<div class="modal fade" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-sm400">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title" id="myModalDetail_title"> Tambah Detail</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="form_detail" action="<?php echo site_url('transaction/mutasi_jurnal/do_detail') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="akun">Kode Rekening</label>
                            <select id="akun_trx_detail" name="akun_trx" class="form-control select2_adddetail">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($cbx_kode_rekening as $key => $row) {
                                    $space = '';
                                    if($row['level'] == '1'){
                                        $space = '&gt; ';
                                    }
                                    if($row['level'] == '2'){
                                        $space = '&gt; &gt; ';
                                    }
                                    if($row['level'] == '3'){
                                        $space = '&gt; &gt; &gt; ';
                                    }
                                    if($row['level'] == '4'){
                                        $space = '&gt; &gt; &gt; &gt; ';
                                    }
                                    if($row['level'] == '5'){
                                        $space = '&gt; &gt; &gt; &gt; &gt; ';
                                    }
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row['kode'] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('akun'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_trx">Nama Rekening</label>
                            <input type="hidden" id="akun_detail" name="akun" value="" />
                            <input readonly id="deskripsi_detail" name="deskripsi_trx" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi_trx'); ?></span>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="debet">Jumlah Debet</label>
                                    <input id="debet_detail" name="debet" type="number" class="form-control" step="any" value="" />
                                    <span class="text-danger"><?php echo form_error('debet'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="kredit">Jumlah Kredit</label>
                                    <input id="kredit_detail" name="kredit" type="number" class="form-control" step="any" value="" />
                                    <span class="text-danger"><?php echo form_error('kredit'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <textarea id="keterangan_detail" name="keterangan" placeholder="" class="form-control"></textarea>
                            <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="act_detail" name="act" value="" />
                        <input type="hidden" id="jurnal_det_id" name="jurnal_det_id" value="" />
                        <input type="hidden" id="jurnal_header" name="jurnal_id" value="" />
                        <input type="hidden" id="cabang_detail" name="cabang" value="" />
                        <input type="hidden" id="tanggal_detail" name="tanggal" value="" />
                        <button type="submit" id="btn-detail" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>


<script>
$(document).ready(function (e) {
    
    $('#daterange').daterangepicker({ startDate: '<?php echo date('01/m/Y') ?>', endDate: '<?php echo date('t/m/Y') ?>' });
    $("#src_date1").val('<?php echo date('Y-m-01') ?>');
    $("#src_date2").val('<?php echo date('Y-m-t') ?>');
    
    $("#tanggalxx").val('<?php echo date('d-m-Y') ?>');
    $('input[name="tanggal"]').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
        autoApply: true,
        locale: {
		  format: 'DD-MM-YYYY'
		}
	});
    
    $('.select2_adddetail').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalDetail",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    $('#akun_trx_detail').on('select2:select', function (e) {
        var data = e.params.data;
        var value = data.id;
        let deskripsi = data.text;
        
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>transaction/mutasi_jurnal/get_kode_rek/' + value,
            success: function (result) {
                
                $('#akun_detail').val($.trim(result.id));
                $('#deskripsi_detail').val($.trim(result.nama));
                
                /*
                if(result.posisi_akun == 1){
                    $('#debet_detail').removeAttr('disabled');
                    $('#kredit_detail').attr('disabled', 'disabled');
                }else{
                    $('#debet_detail').attr('disabled', 'disabled');
                    $('#kredit_detail').removeAttr('disabled');
                }*/
            
                $("#akun_trx_detail").select2("destroy");
                $('#akun_trx_detail option[value="' + value + '"]').text(result.kode);
                window.setTimeout(function () {
                    $("#akun_trx_detail").select2();
                    $("#akun_trx_detail").trigger("change");
                },0);
            }

        });
    });

    
    // Setup datatables
    $.fn.dataTableExt.oApi.fnPagingInfo = function (oSettings)
    {
        return {
            "iStart": oSettings._iDisplayStart,
            "iEnd": oSettings.fnDisplayEnd(),
            "iLength": oSettings._iDisplayLength,
            "iTotal": oSettings.fnRecordsTotal(),
            "iFilteredTotal": oSettings.fnRecordsDisplay(),
            "iPage": Math.ceil(oSettings._iDisplayStart / oSettings._iDisplayLength),
            "iTotalPages": Math.ceil(oSettings.fnRecordsDisplay() / oSettings._iDisplayLength)
        };
    };

    var oTable = $("#dt_table").dataTable({
        initComplete: function () {
            var api = this.api();
            $('#dt_table_filter input')
                    .off('.DT')
                    .on('input.DT', function () {
                        api.search(this.value).draw();
                    });
        },
        iDisplayLength: 15,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'Bfrtip',
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: {
            "url": "<?php echo site_url('transaction/mutasi_jurnal/get_ajax') ?>", 
            "type": "POST",
            "data": function ( d ) {
                d.cabang = $('#cabang').val();
                d.cabangx = $('#cabangx').val();
                d.src_status = $('#src_status').val();
                d.src_date1 = $('#src_date1').val();
                d.src_date2 = $('#src_date2').val();
                d.src_tipe_jurnal = $('#src_tipe_jurnal').val();
            }
        },
        columns: [
            {"data": "nomor", width: "5%"},
            { 
                data: null, render: function ( data, type, row ) {
                    
                    let icon_edit;
                    let title_edit;
                    let onclick_delete;
                    if(data.status == 1){
                        //if(data.penerimaan == null && data.pengeluaran == null && data.mutasi_kas_bank == null){ //dari manual jurnal
                            icon_edit = 'icon-edit';
                            title_edit = 'Edit';
                            onclick_delete = 'onclick="deleteData(this)"';
                        //}else{ //dari penerimaan, pengeluaran, mutasi kas bank tidak boleh dihapus
                        //    icon_edit = 'fa-pencil';
                        //    title_edit = 'Edit';
                        //    onclick_delete = 'disabled';
                        //}
                    }else{
                        icon_edit = 'icon-eye';
                        title_edit = 'Show';
                        onclick_delete = 'disabled';
                    }
                    
                    return '<a onclick="getEdit(this)" data-id="'+data.id+'" data-status="'+data.status+'" class="btn btn-icon btn-outline-primary" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title="'+title_edit+'">'+
                                    '<i class="feather '+icon_edit+'"></i></a> '+
                               '<a '+ onclick_delete +' data-id="'+data.id+'" data-nama="'+data.no_jurnal+'" data-status="'+data.status+'" class="btn btn-icon btn-outline-danger" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                                    '<i class="feather icon-trash"></i></a>';
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            {"data": "no_jurnal", width: "10%"},
            {"data": "tanggal", class:"xcenter"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.status == 1){
                        return '<span class="xgreen">Register</span>';
                    }else if(data.status == 2){
                        return '<span class="xred">Posting</span>';
                    }else if(data.status == 3){
                        return '<span class="xpurple">Batal</span>';
                    }
                },
                //width: "25%",
                class:"xcenter",
                orderable: false,
                searchable: false
            },
            {"data": "tanggal_posting", class:"xcenter"},
            {"data": "tipe_jurnal"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.total > 0){
                        return numberFormatId(data.total);
                    }else{
                        return 0;
                    }
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            {"data": "keterangan", class:"xketerangan", orderable: false, searchable: true},
            
        ],
        lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],

        columnDefs: [
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }
        ],
        buttons: [
            {
                extend: 'copyHtml5',
                text: '<i class="fa fa-files-o"></i>',
                titleAttr: 'Copy',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'excelHtml5',
                text: '<i class="fa fa-file-excel-o"></i>',
                titleAttr: 'Excel',

                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'csvHtml5',
                text: '<i class="fa fa-file-text-o"></i>',
                titleAttr: 'CSV',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'pdfHtml5',
                text: '<i class="fa fa-file-pdf-o"></i>',
                titleAttr: 'PDF',
                title: $('.download_label').html(),
                exportOptions: {
                    columns: ':visible'

                }
            },
            {
                extend: 'print',
                text: '<i class="fa fa-print"></i>',
                titleAttr: 'Print',
                title: $('.download_label').html(),
                customize: function (win) {
                    $(win.document.body)
                            .css('font-size', '10pt');

                    $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                },
                exportOptions: {
                    columns: ':visible'
                }
            },
            {
                extend: 'colvis',
                text: '<i class="fa fa-columns"></i>',
                titleAttr: 'Columns',
                title: $('.download_label').html(),
                postfixButtons: ['colvisRestore']
            },
        ],
        order: [[2, 'desc']],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
            
            var index = (page * length) + iDisplayIndex +1;
            $('td:eq(0)',row).html(index);
            return row;
        }

    });
    // end setup datatables

    $(document).on('change', '#cabangx', function () {
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            oTable.api().ajax.reload();
            
            if(cabang !== src_cabangx){
                $('#btn-closing2').attr('disabled','disabled');
                $('#btn-save-add').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-detail').attr('disabled','disabled');
                $('#btn-add').attr('disabled','disabled');
                $('#btn-add').removeAttr('onclick');
            }else{
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-save-add').removeAttr('disabled');
                $('#btn-save-edit').removeAttr('disabled');
                $('#btn-detail').removeAttr('disabled');
                $('#btn-add').removeAttr('disabled');
                $('#btn-add').attr('onclick', 'getAdd()');
            }

        }
    });
    $(document).on('change', '#src_status', function () {
            oTable.api().ajax.reload();
    });
    $(document).on('change', '#src_tipe_jurnal', function () {
        oTable.api().ajax.reload();
    });
    
    $(document).on('click', '#src_cari', function () {
        oTable.api().ajax.reload();
    });
    
    $(document).on('click', '#btn-close', function () {
        oTable.api().ajax.reload();
        $('#myModal').modal('hide');
        $('#formadd').trigger("reset");

    });
    
    $(document).on('click', '#btn-close2', function () {
        oTable.api().ajax.reload();
        $('#myModalEdit').modal('hide');
        $('#formedit').trigger("reset");
    });
    
    $(document).on('click', '#btn-closing2', function () {
        let id = $('#jurnal_id').val();
        let no_jurnal = $('#no_jurnal_det').val();
        let total_debet = $("#total_debet").val();
        let total_kredit = $("#total_kredit").val();
        let tanggal_jurnal = $("#tanggal_det").val();
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();

        if(cabang !== src_cabangx){
            errorMsg('Maaf tidak boleh Posting Jurnal data cabang lain !');
        }else{
            
            if(total_debet != total_kredit){
                errorMsg('Total Debet & Total Kredit tidak balance!!');
            }else{
                
                Swal.fire({
                  title: 'Peringatan!',
                  text: "Anda yakin akan melakukan Posting Jurnal Transaksi " + no_jurnal + " ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ya, Closing transaksi!',
                  cancelButtonText: 'Tidak'
                }).then((result) => {
                    if (result.value) {
                      
                        if(id != '' && tanggal_jurnal != '' && cabang != ''){
                            $.ajax({
                                dataType: 'json',
                                url: '<?php echo base_url(); ?>transaction/mutasi_jurnal/posting_jurnal/' + id + '/' + cabang + '/' + tanggal_jurnal,
                                success: function (result) {

                                    if(result.success == 'true'){
                                        successMsg(result.message);

                                        oTable.api().ajax.reload();
                                        $('#myModalEdit').modal('hide');
                                        $('#formedit').trigger("reset");
                                        $('#myModal').modal('hide');
                                        $('#formadd').trigger("reset");
                                    }else{
                                        errorMsg(result.message);
                                    }
                                }

                            });
                        }
                    }
                });

            }
        }
        
    });
    
    //$("#dt_table_det").append('<tfoot><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tfoot>');
    // detail datatables
    var oTableDet = $("#dt_table_det").dataTable({
        initComplete: function () {
            var api = this.api();
            $('#dt_table_det_filter input')
                    .off('.DT')
                    .on('input.DT', function () {
                        api.search(this.value).draw();
                    });
        },
        iDisplayLength: 15,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'frtip',
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: {
            "url": "<?php echo site_url('transaction/mutasi_jurnal/get_ajax_detail') ?>", 
            "type": "POST",
            "data": function ( d ) {
                d.cabang = $('#cabang').val();
                d.cabangx = $('#cabangx').val();
                d.jurnal_id = $('#jurnal_id').val();
            }
        },
        columns: [
            {"data": "nomor", width: "5%"},
            {"data": "kode_rek", width: "15%"},
            {"data": "nama_rek"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.debet > 0){
                        return numberFormatId(data.debet);
                    }else{
                        return 0;
                    }
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.kredit > 0){
                        return numberFormatId(data.kredit);
                    }else{
                        return 0;
                    }
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            {"data": "keterangan"},
            //{"data": "view", width: "7%"},
            { 
                data: null, render: function ( data, type, row ) {
                    let onclick_edit;
                    let onclick_delete;
                    if(data.status == 1){
                        if(data.penerimaan == null && data.pengeluaran == null && data.mutasi_kas_bank == null){ //dari manual jurnal
                            onclick_edit = 'onclick="getEditDetail(this)"';
                            onclick_delete = 'onclick="deleteDataDetail(this)"';
                        }else{ //dari penerimaan, pengeluaran, mutasi kas bank tidak boleh dihapus
                            onclick_edit = 'disabled';
                            onclick_delete = 'disabled';
                        }
                        
                    }else{
                        onclick_edit = 'disabled';
                        onclick_delete = 'disabled';
                    }
                    
                    return '<a '+ onclick_edit +' data-id="'+data.id+'" data-status="'+data.status+'" data-akun="'+data.akun+'" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title="Edit">'+
                                    '<i class="fa fa-pencil"></i></a> '+
                               '<a '+ onclick_delete +' data-id="'+data.id+'" data-status="'+data.status+'" data-akun="'+data.akun+'" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                                    '<i class="fa fa-trash"></i></a>';
                },
                class:"xright",
                orderable: false,
                searchable: false
            }
        ],
        lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],
        columnDefs: [
            {
                "searchable": false,
                "orderable": false,
                "targets": 0
            },
            {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
            }
        ],
        order: [[1, 'asc']],
        
        footerCallback: function ( row, data, start, end, display ) {
            var api = this.api(), data;

            // Remove the formatting to get integer data for summation
            var intVal = function ( i ) {
                return typeof i === 'string' ?
                    i.replace(/[\$,]/g, '')*1 :
                    typeof i === 'number' ?
                        i : 0;
            };

            // Total over all pages
            total = api
                .column( 4 )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );
            
            // Total over this page
            pageTotal = api
                .column( 4, { page: 'current'} )
                .data()
                .reduce( function (a, b) {
                    return intVal(a) + intVal(b);
                }, 0 );

            // Update footer
            $( api.column( 4 ).footer() ).html(
                '$'+pageTotal +' ( $'+ total +' total)'
            );

        },
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
            
            var index = (page * length) + iDisplayIndex +1;
            $('td:eq(0)',row).html(index);
            return row;
        },

    });
    // end setup datatables
    
    $('#formadd').on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if (data.status == "fail") {
                    if(data.message){
                        errorMsg(data.message);
                    }else{
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    }
                    
                } else {
                    //successMsg(data.message);
                    oTable.api().ajax.reload();
                    $('#myModal').modal('hide');
                    $('#formadd').trigger("reset");
                    
                    //show popup detail
                    getDetail(data.jurnal_idx);
                    
                }

            },
            error: function () {

            }
        });

    }));

    $('#formedit').on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if (data.status == "fail") {
                    if(data.message){
                        errorMsg(data.message);
                    }else{
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    }
                    
                } else {
                    successMsg(data.message);
                    
                    oTable.api().ajax.reload();
                    $('#myModalEdit').modal('hide');
                    $('#formedit').trigger("reset");
                }

            },
            error: function () {

            }
        });

    }));
    
    $('#form_detail').on('submit', (function (e) {
        e.preventDefault();
        $.ajax({
            url: $(this).attr('action'),
            type: "POST",
            data: new FormData(this),
            dataType: 'json',
            contentType: false,
            cache: false,
            processData: false,
            success: function (data) {

                if (data.status == "fail") {
                    if(data.message){
                        errorMsg(data.message);
                    }else{
                        var message = "";
                        $.each(data.error, function (index, value) {
                            message += value;
                        });
                        errorMsg(message);
                    }
                    
                } else {
                    successMsg(data.message);
                    
                    //update total view
                    checkTotal();
                    
                    oTableDet.api().ajax.reload();
                    $('#myModalDetail').modal('hide');
                    $('#form_detail').trigger("reset");
                    
                }

            },
            error: function () {

            }
        });

    }));

});


function getAdd() {
    let cabangxx = $('#cabangx').val();
    let cabang_nama = $("#cabangx option:selected").html();
    
    $('#myModal').modal('show');

    $('#cabangxx').val($.trim(cabangxx));
    $('#cabang_nama').val($.trim(cabang_nama));
}
    
function getEdit(event) {
    let id = $(event).data("id").toString().trim();
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>transaction/mutasi_jurnal/get_data/' + id,
        success: function (result) {
            
            $('#jurnal_id').val($.trim(result.id));
            $('#cabang_det').val($.trim(result.cabang));
            $('#cabang_nama_det').val($.trim(result.cabang_nama));
            $('#keterangan_det').val($.trim(result.keterangan));
            $('#no_jurnal_det').val($.trim(result.no_jurnal));
            $('#tipe_jurnal_det').val($.trim(result.tipe_jurnal));
            
            $("#tanggal_det").val(result.tanggalx);
            $('#tanggal_det').daterangepicker({
                singleDatePicker: true, 
                startDate: result.tanggalx ,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

            if(result.status == 1){
                
                $('#status_det').val('Registrasi');
                $('#status_det').attr('class', 'xgreen form-control');
                $('#btn-closing2').removeAttr('disabled');

                if(result.penerimaan == null && result.pengeluaran == null && result.mutasi_kas_bank == null){ //dari manual jurnal
                    
                    $('#tanggal_det').removeAttr('disabled');
                    $('#keterangan_det').removeAttr('disabled');
                    $('#btn-save-edit').removeAttr('disabled');
                    $('#btn_add_detail').removeAttr('disabled');
                    $('#btn_add_detail').attr('onclick','getAddDetail()');

                }else{
                    
                    $('#tanggal_det').attr('disabled','disabled');
                    $('#keterangan_det').attr('disabled','disabled');
                    $('#btn-save-edit').attr('disabled','disabled');
                    $('#btn_add_detail').attr('disabled','disabled');
                    $('#btn_add_detail').removeAttr('onclick');
                }
                
            }else if(result.status == 2){
                
                $('#status_det').val('Posting');
                $('#status_det').attr('class', 'xred form-control');
                
                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                
            }else if(result.status == 3){
                
                $('#status_det').val('Batal');
                $('#status_det').attr('class', 'xpurple form-control');
                
                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                
            }
            
            $('#total_det').val(numberFormatId(result.total));
            
            //reload table detail
            $("#dt_table_det").dataTable().api().ajax.reload();
            checkTotal();
        }

    });
}
    
function getDetail(id) {
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>transaction/mutasi_jurnal/get_data/' + id,
        success: function (result) {
            
            $('#jurnal_id').val($.trim(result.id));
            $('#cabang_det').val($.trim(result.cabang));
            $('#cabang_nama_det').val($.trim(result.cabang_nama));
            $('#keterangan_det').val($.trim(result.keterangan));
            $('#no_jurnal_det').val($.trim(result.no_jurnal));
            $('#tipe_jurnal_det').val($.trim(result.tipe_jurnal));
            
            $("#tanggal_det").val(result.tanggalx);
            $('#tanggal_det').daterangepicker({
                singleDatePicker: true, 
                startDate: result.tanggalx ,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

            if(result.status == 1){
                
                $('#status_det').val('Registrasi');
                $('#status_det').attr('class', 'xgreen form-control');
                $('#btn-closing2').removeAttr('disabled');
                $('#tanggal_det').removeAttr('disabled');
                $('#keterangan_det').removeAttr('disabled');
                $('#btn-save-edit').removeAttr('disabled');

                if(result.penerimaan == null && result.pengeluaran == null && result.mutasi_kas_bank == null){ //dari manual jurnal
                    
                    $('#btn_add_detail').removeAttr('disabled');
                    $('#btn_add_detail').attr('onclick','getAddDetail()');

                }else{
                    
                    $('#btn_add_detail').attr('disabled','disabled');
                    $('#btn_add_detail').removeAttr('onclick');
                }
                
            }else if(result.status == 2){
                
                $('#status_det').val('Posting');
                $('#status_det').attr('class', 'xred form-control');
                
                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                
            }else if(result.status == 3){
                
                $('#status_det').val('Batal');
                $('#status_det').attr('class', 'xpurple form-control');
                
                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                
            }
            
            $('#total_det').val(numberFormatId(result.total));
            
            //reload table detail
            $("#dt_table_det").dataTable().api().ajax.reload();
            checkTotal();
        }

    });

}

function getAddDetail() {
    let cabangxx = $('#cabangx').val();
    let jurnal_id = $("#jurnal_id").val();
    let tanggal_detail = $('#tanggal_det').val();

    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Tambah Detail");
    
    $('#act_detail').val('add');
    $('#jurnal_det_id').val('');
    $('#cabang_detail').val($.trim(cabangxx));
    $('#jurnal_header').val($.trim(jurnal_id));
    $('#tanggal_detail').val($.trim(tanggal_detail));

    //reset input
    $('#debet_detail').val('');
    $('#kredit_detail').val('');
    $('#keterangan_detail').val('');
    $('#akun_detail').val('');
    $('#deskripsi_detail').val('');

    $("#akun_trx_detail").val("");
    $("#akun_trx_detail").select2("destroy");
    $("#akun_trx_detail").select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalDetail",
        escapeMarkup: function(m) { 
           return m; 
        }
    });

    //reload table detail
    //$("#dt_table_det").dataTable().api().ajax.reload();
    //checkTotal();
}
   
function getEditDetail(event) {
    let id = $(event).data("id").toString().trim();
    let cabangxx = $('#cabangx').val();
    let jurnal_id = $("#jurnal_id").val();
    let tanggal_detail = $('#tanggal_det').val();
    
    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Edit Detail");
    
    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>transaction/mutasi_jurnal/get_data_detail/' + id,

        success: function (result) {
            
            $('#act_detail').val('edit');
            $('#jurnal_det_id').val($.trim(result.id));
            $('#cabang_detail').val($.trim(result.cabang));
            $('#jurnal_header').val($.trim(result.jurnal_header));
            $('#tanggal_detail').val($.trim(tanggal_detail));

            //reset input
            $('#debet_detail').val($.trim(result.debet));
            $('#kredit_detail').val($.trim(result.kredit));
            $('#keterangan_detail').val($.trim(result.keterangan));
            $('#akun_detail').val($.trim(result.akun));
            $('#deskripsi_detail').val($.trim(result.transaksi_nama));
            $('#akun_trx_detail').val($.trim(result.akun));

            $("#akun_trx_detail").select2("destroy");
            $("#akun_trx_detail").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalDetail",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });

            /*
            if(result.posisi_akun == 1){
                $('#debet_detail').removeAttr('disabled');
                $('#kredit_detail').attr('disabled', 'disabled');
            }else{
                $('#debet_detail').attr('disabled', 'disabled');
                $('#kredit_detail').removeAttr('disabled');
            }*/
            
            
            //reload table detail
            //$("#dt_table_det").dataTable().api().ajax.reload();

        }

    });
}
 
function deleteData(event) {
    let id = $(event).data("id").toString().trim();
    let status = $(event).data("status").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    let cabang = $('#cabang').val();
    let src_cabangx = $('#cabangx option:selected').val();
    
    if(cabang !== src_cabangx){
        errorMsg('Maaf tidak boleh menghapus data cabang lain !');
    }else{
        if(status == 1){
            //delete_recordById('<?php echo base_url() ?>transaction/mutasi_jurnal/delete/' + id, '');
            
            Swal.fire({
              title: 'Peringatan!',
              text: "Anda yakin akan menghapus '"+nama+"' ?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Hapus data!',
              cancelButtonText: 'Tidak'
            }).then((result) => {
              if (result.value) {
                  $.ajax({
                    dataType: 'json',
                    url: '<?php echo base_url() ?>transaction/mutasi_jurnal/delete/' + id,
                    success: function (res) { 
                        if(res.status == 'success'){
                            successMsg(res.message);
                            //window.location.reload(true);
                            //reload table detail
                            $("#dt_table").dataTable().api().ajax.reload();
                        }else{
                            errorMsg(res.message);
                            $("#dt_table").dataTable().api().ajax.reload();
                        }
                    }
                 })
              }
            });

        }else{
            let txt_status='';
            if(status == 2){
                txt_status = 'Posting';
            }else
            if(status == 3){
                txt_status = 'Batal';
            }
            alert('Data tidak boleh dihapus, status jurnal sudah '+txt_status);
        }
    }
}
      
function deleteDataDetail(event) {
    let id = $(event).data("id").toString().trim();
    let status = $(event).data("status").toString().trim();
    let akun = $(event).data("akun").toString().trim();
    let jurnal_id = $("#jurnal_id").val();
    
    let cabang = $('#cabang').val();
    let src_cabangx = $('#cabangx option:selected').val();
    
    if(cabang !== src_cabangx){
        errorMsg('Maaf tidak boleh menghapus data cabang lain !');
    }else{
        if(status == 1){
            //delete_recordById('<?php echo base_url() ?>transaction/mutasi_jurnal/deleteDetail/' + id + '/' + jurnal_id + '/' + akun + '/' + cabang, akun, 'no');
            var url = '<?php echo base_url() ?>transaction/mutasi_jurnal/deleteDetail/' + id + '/' + jurnal_id + '/' + akun + '/' + cabang;
            Swal.fire({
                  title: 'Peringatan!',
                  text: "Anda yakin akan menghapus data '"+akun+"' ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ya, Hapus data!',
                  cancelButtonText: 'Tidak'
                }).then((result) => {
                  if (result.value) {
                      $.ajax({
                            url: url,
                            success: function (res) {
                                
                                //reload table detail
                                $("#dt_table_det").dataTable().api().ajax.reload();
                                
                                Swal.fire(
                                  'Deleted!',
                                  'Data berhasil di hapus.',
                                  'success'
                                );
                                
                            }
                        })

                  }
            });
            
            
        }else{
            let txt_status='';
            if(status == 2){
                txt_status = 'Posting';
            }else
            if(status == 3){
                txt_status = 'Batal';
            }
            alert('Data tidak boleh dihapus, status jurnal sudah '+txt_status);
        }
    }
    
    checkTotal();
}
       
function checkTotal() {
    let jurnal_id = $("#jurnal_id").val();
    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url() ?>transaction/mutasi_jurnal/checkTotal/' + jurnal_id,
        success: function (result) {
            
            //$('#total_det').val(numberFormatId(result.total_debet));
            $("#total_debet").val(result.total_debet);
            $("#total_kredit").val(result.total_kredit);
            $("#total_debet_txt").html('Total Debet: Rp. '+ numberFormatId(result.total_debet));
            $("#total_kredit_txt").html('Total Kredit: Rp. '+ numberFormatId(result.total_kredit));

        }
    });
    
}
    
function numberFormatId(num) {
    //return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   
    return parts.join(",");
}
</script>


