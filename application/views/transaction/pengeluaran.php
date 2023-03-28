<!-- [ breadcrumb ] -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Pengeluaran Kas</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Transaksi Kasir</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>transaction/pengeluaran">Pengeluaran Kas</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-header">
                <form action="<?php echo site_url('transaction/pengeluaran') ?>"  name="search_form" method="post" accept-charset="utf-8">
                    <div class="box-body">
                        <?php if ($this->session->flashdata('msg')) { ?>
                            <?php echo $this->session->flashdata('msg') ?>
                        <?php } ?>        
                        <?php echo $this->customlib->getCSRF(); ?>
                        <div class="col-md-4">
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
                                    <option value="1"> Pengakuan</option>
                                    <option value="2"> Jurnal</option>
                                    <option value="3"> Posting</option>
                                    
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
                        <div class="col-md-2 xright">
                            <?php if ($this->rbac->hasPrivilege('trx_pengeluaran', 'can_add')) { ?>
                                <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i>  Tambah</a>    
                            <?php } ?> 
                        </div>
                        
                        
                    </div>
                </form>
            </div>
            <div class="card-body">
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Pengeluaran Kas</div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Action</th>
                                <th>No Transaksi</th>
                                <th>Tanggal</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <!-- <th>Unit Kerja</th> -->
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
                <h4 class="box-title"> Tambah Pengeluaran</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('transaction/pengeluaran/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
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
                <h4 class="box-title"> Detail Pengeluaran</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('transaction/pengeluaran/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_det" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input readonly id="cabang_nama_det" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="no_transaksi">Nomor Transaksi</label>
                                    <input readonly id="no_transaksi_det" name="no_transaksi" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('no_transaksi'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="status">Status</label>
                                    <input readonly id="status_det" name="status" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('status'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="total">Total Transaksi</label>
                                    <input readonly id="total_det" name="total" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('total'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal</label>
                                    <input id="tanggal_before" name="tanggal_before" type="hidden" />
                                    <input id="tanggal_det" name="tanggal" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('tanggal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="keterangan">Keterangan</label>
                                    <textarea id="keterangan_det" name="keterangan" placeholder="" class="form-control" style="height:30px;"></textarea>
                                    <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="box-header ptbnull">
                            <h3 class="box-title titlefix"></h3>
                            <div class="box-tools pull-right">
                                <?php if ($this->rbac->hasPrivilege('trx_pengeluaran', 'can_add')) { ?>
                                    <a onclick="getAddDetail()" id="btn_add_detail" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>  Tambah Detail</a>    
                                <?php } ?> 
                            </div>
                        </div>
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped" id="dt_table_det">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode</th>
                                        <th>Transaksi</th>
                                        <th>Akun Kas</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="pengeluaran_id" name="pengeluaran_id" value="" />
                        <input type="button" class="btn btn-danger pull-left" id="btn-closing2" value="Closing Transaksi">
                        <input type="button" class="btn btn-success pull-left" id="btn-reopen2" style="margin-left:5px;" value=" Re-Open Transaksi">
                        <input type="button" class="btn btn-default pull-right" id="btn-close2" style="margin-left:5px;" value="Tutup">
                        <button type="submit" id="btn-save-edit" class="btn btn-info pull-right" > Simpan</button>
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

                <form id="form_detail" action="<?php echo site_url('transaction/pengeluaran/do_detail') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="kode_trx">Kode Transaksi</label>
                            <select id="kode_trx_detail" name="kode_trx" class="form-control select2_adddetail">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($cbx_kode_transaksi as $key => $row) {
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
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row['kode'] ?> - <?php echo $row["deskripsi"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('kode_trx'); ?></span>
                        </div>
                        <div class="form-group">
                            <label for="deskripsi_trx">Deskripsi</label>
                            <input type="hidden" id="kode_transaksi_detail" name="kode_transaksi" value="" />
                            <input readonly id="deskripsi_detail" name="deskripsi_trx" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi_trx'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="akun_kas">Akun Kas</label>
                            <select id="akun_kas_detail" name="akun_kas" class="form-control">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($cbx_akun_kas as $key => $row) {
                                   ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row["deskripsi"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('akun_kas'); ?></span>
                        </div>
                        <div class="row">
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="jumlah">Jumlah</label>
                                    <input id="jumlah_detail" name="jumlah" type="number" class="form-control" step="any" value="" />
                                    <span class="text-danger"><?php echo form_error('jumlah'); ?></span>
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
                        <input type="hidden" id="pengeluaran_det_id" name="pengeluaran_det_id" value="" />
                        <input type="hidden" id="pengeluaran_id_detail" name="pengeluaran_id" value="" />
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

    $('.select2').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModal",
        escapeMarkup: function(m) { 
           return m; 
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
    $('#kode_trx_detail').on('select2:select', function (e) {
        var data = e.params.data;
        var value = data.id;
        let deskripsi = data.text;
        
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>transaction/pengeluaran/get_kode_trx/' + value,
            success: function (result) {
                
                $('#kode_transaksi_detail').val($.trim(result.id));
                $('#deskripsi_detail').val($.trim(result.deskripsi));
            
                $("#kode_trx_detail").select2("destroy");
                $('#kode_trx_detail option[value="' + value + '"]').text(result.kode);
                window.setTimeout(function () {
                    $("#kode_trx_detail").select2();
                    $("#kode_trx_detail").trigger("change");
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
            "url": "<?php echo site_url('transaction/pengeluaran/get_ajax') ?>", 
            "type": "POST",
            "data": function ( d ) {
                d.cabang = $('#cabang').val();
                d.cabangx = $('#cabangx').val();
                d.src_status = $('#src_status').val();
                d.src_date1 = $('#src_date1').val();
                d.src_date2 = $('#src_date2').val();
            }
        },
        columns: [
            {"data": "nomor"},
            { 
                data: null, render: function ( data, type, row ) {
                    let icon_edit;
                    let title_edit;
                    let onclick_delete;
                    if(data.status == 1){
                        icon_edit = 'icon-edit';
                        title_edit = 'Edit';
                        onclick_delete = 'onclick="deleteData(this)"';
                    }else{
                        icon_edit = 'icon-eye';
                        title_edit = 'Show';
                        onclick_delete = 'disabled';
                    }
                    
                    return '<a onclick="getEdit(this)" data-id="'+data.id+'" data-status="'+data.status+'" class="btn btn-icon btn-outline-primary" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title="'+title_edit+'">'+
                                    '<i class="feather '+icon_edit+'"></i></a> '+
                               '<a '+ onclick_delete +'  data-id="'+data.id+'" data-nama="'+data.no_transaksi+'" data-status="'+data.status+'" class="btn btn-icon btn-outline-danger" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                                    '<i class="feather icon-trash"></i></a>';
                },
                class:"xcenter",
                orderable: false,
                searchable: false
            },
            {"data": "no_transaksi"},
            {"data": "tanggal"},
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
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.status == 1){
                        return '<font color="green">Pengakuan</font>';
                    }else if(data.status == 2){
                        return '<font color="blue">Jurnal</font>';
                    }else if(data.status == 3){
                        return '<font color="red">Posting</font>';
                    }
                },
                //width: "25%",
                class:"xcenter",
                orderable: false,
                searchable: false
            },
            {"data": "keterangan", orderable: false, searchable: true},
            // {"data": "cabang_nama"},
            
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
                $('#btn-reopen2').attr('disabled','disabled');
                $('#btn-save-add').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-detail').attr('disabled','disabled');
                $('#btn-add').attr('disabled','disabled');
                $('#btn-add').removeAttr('onclick');
            }else{
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').removeAttr('disabled');
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
        let id = $('#pengeluaran_id').val();
        let no_transaksi = $('#no_transaksi_det').val();
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        let total_det = $('#total_det').val();
        
        if(cabang !== src_cabangx){
            errorMsg('Maaf tidak boleh closing transaksi data cabang lain !');
        }else{

            if(total_det == 0){
                errorMsg('Maaf Total Transaksi masih kosong !');
            }else{
                
                Swal.fire({
                  title: 'Peringatan!',
                  text: "Anda yakin akan Closing Transaksi " + no_transaksi + " ?",
                  icon: 'warning',
                  showCancelButton: true,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Ya, Closing Transaksi !',
                  cancelButtonText: 'Tidak'
                }).then((result) => {
                  if (result.value) {
                      $.ajax({
                            dataType: 'json',
                            url: '<?php echo base_url(); ?>transaction/pengeluaran/closing_transaksi/' + id + '/' + cabang,
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
                });

            }
            
        }
    });
    
    $(document).on('click', '#btn-reopen2', function () {
        let id = $('#pengeluaran_id').val();
        let no_transaksi = $('#no_transaksi_det').val();
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        let total_det = $('#total_det').val();
        //alert(total_det);
        if(cabang !== src_cabangx){
            errorMsg('Maaf tidak boleh closing transaksi data cabang lain !');
        }else{
            
            Swal.fire({
              title: 'Peringatan!',
              text: "Anda yakin akan Re-Open Transaksi " + no_transaksi + " ?",
              icon: 'warning',
              showCancelButton: true,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Ya, Re-Open Transaksi !',
              cancelButtonText: 'Tidak'
            }).then((result) => {
              if (result.value) {
                  $.ajax({
                        dataType: 'json',
                        url: '<?php echo base_url(); ?>transaction/pengeluaran/reopen_transaksi/' + id + '/' + cabang,
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
            });
            

        }
        
    });
    
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
            "url": "<?php echo site_url('transaction/pengeluaran/get_ajax_detail') ?>", 
            "type": "POST",
            "data": function ( d ) {
                d.cabang = $('#cabang').val();
                d.cabangx = $('#cabangx').val();
                d.pengeluaran_id = $('#pengeluaran_id').val();
            }
        },
        columns: [
            {"data": "nomor", width: "5%"},
            {"data": "kode_trx", width: "15%"},
            {"data": "transaksi_nama"},
            {"data": "akun_kas_nama"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.jumlah > 0){
                        return numberFormatId(data.jumlah);
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
                        onclick_edit = 'onclick="getEditDetail(this)"';
                        onclick_delete = 'onclick="deleteDataDetail(this)"';
                    }else{
                        onclick_edit = 'disabled';
                        onclick_delete = 'disabled';
                    }
                    
                    return '<a '+ onclick_edit +' data-id="'+data.id+'" data-status="'+data.status+'" data-akunkas="'+data.akun_kas+'" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title="Edit">'+
                                    '<i class="fa fa-pencil"></i></a> '+
                            '<a '+ onclick_delete +'  data-id="'+data.id+'" data-status="'+data.status+'" data-akunkas="'+data.akun_kas+'" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                                    '<i class="fa fa-trash"></i></a>';
                    // return '<a '+ onclick_delete +'  data-id="'+data.id+'" data-status="'+data.status+'" data-akunkas="'+data.akun_kas+'" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                    //                 '<i class="fa fa-trash"></i></a>';
                },
                width: "10%",
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
                    //window.location.reload(true);
                    oTable.api().ajax.reload();
                    $('#myModal').modal('hide');
                    $('#formadd').trigger("reset");
                    
                    //show popup detail
                    getDetail(data.pengeluaran_idx);
                    
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

                    var total = 0;
                    if(data.total > 0){
                        total = numberFormatId(data.total);
                    }
                    //update total view
                    $('#total_det').val(total);

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
        url: '<?php echo base_url(); ?>transaction/pengeluaran/get_data/' + id,
        success: function (result) {
            
            
            $('#pengeluaran_id').val($.trim(result.id));
            $('#cabang_det').val($.trim(result.cabang));
            $('#cabang_nama_det').val($.trim(result.cabang_nama));
            $('#keterangan_det').val($.trim(result.keterangan));
            $('#no_transaksi_det').val($.trim(result.no_transaksi));
            
            $("#tanggal_before").val(result.tanggalx);
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
                $('#status_det').val('Pengakuan');
                $('#status_det').attr('class', 'xgreen form-control');
                $('#tanggal_det').removeAttr('disabled');
                $('#keterangan_det').removeAttr('disabled');
                
                $('#btn_add_detail').removeAttr('disabled');
                $('#btn_add_detail').attr('onclick','getAddDetail()');
                
                $('#btn-save-edit').removeAttr('disabled');
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').attr('disabled','disabled');
            }else
            if(result.status == 2){
                $('#status_det').val('Jurnal');
                $('#status_det').attr('class', 'xblue form-control');
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');

                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                $('#btn-reopen2').removeAttr('disabled');
            }else
            if(result.status == 3){
                $('#status_det').val('Posting');
                $('#status_det').attr('class', 'xred form-control');
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');

                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                $('#btn-reopen2').attr('disabled','disabled');
            }
            
            var total = 0;
            if(result.total > 0){
                total = numberFormatId(result.total);
            }
            $('#total_det').val(total);
            
            //reload table detail
            $("#dt_table_det").dataTable().api().ajax.reload();
        }

    });
}
   
function getDetail(id) {
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>transaction/pengeluaran/get_data/' + id,
        success: function (result) {
            
            $('#pengeluaran_id').val($.trim(result.id));
            $('#cabang_det').val($.trim(result.cabang));
            $('#cabang_nama_det').val($.trim(result.cabang_nama));
            $('#keterangan_det').val($.trim(result.keterangan));
            $('#no_transaksi_det').val($.trim(result.no_transaksi));
            
            $("#tanggal_before").val(result.tanggalx);
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
                $('#status_det').val('Pengakuan');
                $('#status_det').attr('class', 'xgreen form-control');
                $('#tanggal_det').removeAttr('disabled');
                $('#keterangan_det').removeAttr('disabled');
                
                $('#btn_add_detail').removeAttr('disabled');
                $('#btn_add_detail').attr('onclick','getAddDetail()');
                
                $('#btn-save-edit').removeAttr('disabled');
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').attr('disabled','disabled');
            }else
            if(result.status == 2){
                $('#status_det').val('Jurnal');
                $('#status_det').attr('class', 'xblue form-control');
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');

                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                $('#btn-reopen2').removeAttr('disabled');
            }else
            if(result.status == 3){
                $('#status_det').val('Posting');
                $('#status_det').attr('class', 'xred form-control');
                $('#tanggal_det').attr('disabled','disabled');
                $('#keterangan_det').attr('disabled','disabled');

                $('#btn_add_detail').attr('disabled','disabled');
                $('#btn_add_detail').removeAttr('onclick');
                
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btn-closing2').attr('disabled','disabled');
                $('#btn-reopen2').attr('disabled','disabled');
            }
            
            var total = 0;
            if(result.total > 0){
                total = numberFormatId(result.total);
            }
            $('#total_det').val(total);
            
            //reload table detail
            $("#dt_table_det").dataTable().api().ajax.reload();
        }

    });

}

function getAddDetail() {
    let cabangxx = $('#cabangx').val();
    let pengeluaran_id = $("#pengeluaran_id").val();
    let tanggal_detail = $('#tanggal_det').val();

    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Tambah Detail");
    
    $('#act_detail').val('add');
    $('#pengeluaran_det_id').val('');
    $('#cabang_detail').val($.trim(cabangxx));
    $('#pengeluaran_id_detail').val($.trim(pengeluaran_id));
    $('#tanggal_detail').val($.trim(tanggal_detail));

    //reset input
    $('#akun_kas_detail').val('');
    $('#jumlah_detail').val('');
    $('#keterangan_detail').val('');
    $('#kode_transaksi_detail').val('');
    $('#deskripsi_detail').val('');

    $("#akun_trx_detail").val("");
    $("#kode_trx_detail").select2("destroy");
    $("#kode_trx_detail").select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalDetail",
        escapeMarkup: function(m) { 
           return m; 
        }
    });

    //reload table detail
    $("#dt_table_det").dataTable().api().ajax.reload();
}
   
function getEditDetail(event) {
    let id = $(event).data("id").toString().trim();
    let cabangxx = $('#cabangx').val();
    let pengeluaran_id = $("#pengeluaran_id").val();
    let tanggal_detail = $('#tanggal_det').val();
    
    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Edit Detail");

    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>transaction/pengeluaran/get_data_detail/' + id,

        success: function (result) {
            
            $('#act_detail').val('edit');
            $('#pengeluaran_det_id').val($.trim(result.id));
            $('#cabang_detail').val($.trim(result.cabang));
            $('#pengeluaran_id_detail').val($.trim(result.pengeluaran));
            $('#tanggal_detail').val($.trim(tanggal_detail));

            //reset input
            $('#akun_kas_detail').val($.trim(result.akun_kas));
            $('#jumlah_detail').val($.trim(result.jumlah));
            $('#keterangan_detail').val($.trim(result.keterangan));
            $('#kode_transaksi_detail').val($.trim(result.kode_transaksi));
            $('#deskripsi_detail').val($.trim(result.transaksi_nama));
            $('#kode_trx_detail').val($.trim(result.kode_transaksi));

            $("#kode_trx_detail").select2("destroy");
            $("#kode_trx_detail").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalDetail",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });

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
            //delete_recordById('<?php echo base_url() ?>transaction/pengeluaran/delete/' + id, '');
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
                        url: '<?php echo base_url() ?>transaction/pengeluaran/delete/' + id,
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
                txt_status = 'Jurnal';
            }else
            if(status == 3){
                txt_status = 'Posting';
            }
            alert('Data tidak boleh dihapus, status transaksi sudah '+txt_status);
        }
    }
}
      
function deleteDataDetail(event) {
    let id = $(event).data("id").toString().trim();
    let status = $(event).data("status").toString().trim();
    let akun_kas = $(event).data("akunkas").toString().trim();
    let pengeluaran_id = $("#pengeluaran_id").val();
    
    let cabang = $('#cabang').val();
    let src_cabangx = $('#cabangx option:selected').val();
    
    if(cabang !== src_cabangx){
        errorMsg('Maaf tidak boleh menghapus data cabang lain !');
    }else{
        if(status == 1){
            //delete_recordById('<?php echo base_url() ?>transaction/pengeluaran/deleteDetail/' + id + '/' + pengeluaran_id + '/' + akun_kas + '/' + cabang, '', 'no');
            var url = '<?php echo base_url() ?>transaction/pengeluaran/deleteDetail/' + id + '/' + pengeluaran_id + '/' + akun_kas + '/' + cabang;
            Swal.fire({
                  title: 'Peringatan!',
                  text: "Anda yakin akan menghapus data ini ?",
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
                txt_status = 'Jurnal';
            }else
            if(status == 3){
                txt_status = 'Posting';
            }
            alert('Data tidak boleh dihapus, status transaksi sudah '+txt_status);
        }
    }
    
    
}
    
function numberFormatId(num) {
    //return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   
    return parts.join(",");
}
</script>


