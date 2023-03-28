<!-- [ breadcrumb ] -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Mutasi Kas Bank</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Transaksi Kasir</a></li>
                    <li class="breadcrumb-item"><a href="#!">Mutasi Kas Bank</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-header">
                <form action="<?php echo site_url('transaction/mutasi_kas') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                            <?php if ($this->rbac->hasPrivilege('trx_mutasi_kas', 'can_add')) { ?>
                                <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i>  Tambah</a>    
                            <?php } ?> 
                        </div>
                        
                    </div>
                </form>
            </div>
            <div class="card-body">
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Mutasi Kas Bank</div>
                    <table class="table table-striped display table-bordered table-hover"  cellspacing="0" width="100%" id="dt_table">
                        <thead>
                            <tr>
                                <th rowspan="2">No</th>
                                <th rowspan="2" class="xcenter">Action</th>
                                <th rowspan="2">No Transaksi</th>
                                <th rowspan="2">Tanggal</th>
                                <th colspan="2" style="text-align:center">Mutasi Masuk</th>
                                <th colspan="2" style="text-align:center">Mutasi Keluar</th>
                                <th rowspan="2">Jumlah</th>
                                <th rowspan="2">Status Jurnal</th>
                                <th rowspan="2">Keterangan</th>
                                <!--<th rowspan="2">Unit Kerja</th>-->
                            </tr>
                            <tr>
                                <th>Akun Mutasi Masuk</th>
                                <th>Deskripsi</th>
                                <th>Akun Mutasi Keluar</th>
                                <th>Deskripsi</th>
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
                <h4 class="box-title"> Tambah Mutasi Kas Bank</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('transaction/mutasi_kas/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Mutasi</label><small class="req"> *</small>
                                    <input id="tanggal_add" name="tanggal" type="text" class="form-control datepicker"  value="" />
                                    <span class="text-danger"><?php echo form_error('tanggal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_add" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input disabled id="cabang_nama" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Mutasi Masuk</legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="akun_kas_masuk">Akun Mutasi:</label>
                                        <select name="akun_kas_masuk" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($cbx_akun_kas as $key => $row) { ?>
                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row["deskripsi"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('akun_kas_masuk'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_transaksi_masuk">Kode Transaksi:</label>
                                        <select name="kode_transaksi_masuk" class="form-control select2_add">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($cbx_kode_transaksi_masuk as $key => $row) {
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
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('kode_transaksi_masuk'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Mutasi Keluar </legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="akun_kas_keluar">Akun Mutasi:</label>
                                        <select name="akun_kas_keluar" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($cbx_akun_kas as $key => $row) { ?>
                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row["deskripsi"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('akun_kas_keluar'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_transaksi_keluar">Kode Transaksi:</label>
                                        <select name="kode_transaksi_keluar" class="form-control select2_add">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($cbx_kode_transaksi_keluar as $key => $row) {
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
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('kode_transaksi_keluar'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Nominal </legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah:</label>
                                        <input type="number" name="jumlah" step="any" placeholder="" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('jumlah'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea name="keterangan" placeholder="" class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                    </div>
                    <div class="box-footer">
                        
                        <input type="button" class="btn btn-default pull-right" id="btn-close" style="margin-left:5px;" value="Tutup">
                        <button type="submit" id="btn-save-add" class="btn btn-info pull-right" > Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>


<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title" id="box-title-edit"> Edit Mutasi Kas Bank</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('transaction/mutasi_kas/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-4">
                                <div class="form-group">
                                    <label for="tanggal">Tanggal Mutasi</label><small class="req"> *</small>
                                    <input id="tanggal_edit" name="tanggal" type="text" class="form-control datepicker"  value="" />
                                    <span class="text-danger"><?php echo form_error('tanggal'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-8">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_edit" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input disabled id="cabang_nama_edit" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                        </div>
                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Mutasi Masuk</legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="akun_kas_masuk">Akun Mutasi:</label>
                                        <select id="akun_kas_masuk" name="akun_kas_masuk" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($cbx_akun_kas as $key => $row) { ?>
                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row["deskripsi"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('akun_kas_masuk'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_transaksi_masuk">Kode Transaksi:</label>
                                        <select id="kode_transaksi_masuk" name="kode_transaksi_masuk" class="form-control select2_edit">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($cbx_kode_transaksi_masuk as $key => $row) {
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
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('kode_transaksi_masuk'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Mutasi Keluar </legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="akun_kas_keluar">Akun Mutasi:</label>
                                        <select id="akun_kas_keluar" name="akun_kas_keluar" class="form-control">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php foreach ($cbx_akun_kas as $key => $row) { ?>
                                                <option value="<?php echo $row['id'] ?>"> <?php echo $row["deskripsi"] ?></option>
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('akun_kas_keluar'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="kode_transaksi_keluar">Kode Transaksi:</label>
                                        <select id="kode_transaksi_keluar" name="kode_transaksi_keluar" class="form-control select2_edit">
                                            <option value=""><?php echo $this->lang->line('select'); ?></option>
                                            <?php
                                            foreach ($cbx_kode_transaksi_keluar as $key => $row) {
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
                                            <?php } ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('kode_transaksi_keluar'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>

                        <fieldset class="form-group">
                            <div class="row">
                                <legend class="col-md-3 col-form-label pt-0">Nominal </legend>
                                <div class="col-md-9">
                                    <div class="form-group">
                                        <label for="jumlah">Jumlah:</label>
                                        <input type="number" id="jumlah" name="jumlah" step="any" placeholder="" class="form-control" />
                                        <span class="text-danger"><?php echo form_error('jumlah'); ?></span>
                                    </div>
                                    <div class="form-group">
                                        <label for="keterangan">Keterangan</label>
                                        <textarea id="keterangan" name="keterangan" placeholder="" class="form-control"></textarea>
                                        <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                                    </div>
                                </div>
                            </div>
                        </fieldset>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="mutasi_kas_id" name="mutasi_kas_id" value="" />
                        <input type="hidden" id="no_transaksi" name="no_transaksi" value="" />
                        
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

<script>
$(document).ready(function (e) {
   
    $('#daterange').daterangepicker({ startDate: '<?php echo date('01/m/Y') ?>', endDate: '<?php echo date('t/m/Y') ?>' });
    $("#src_date1").val('<?php echo date('Y-m-01') ?>');
    $("#src_date2").val('<?php echo date('Y-m-t') ?>');
    
    $(".tanggal_add").val('<?php echo date('d-m-Y') ?>');
    $('input[name="tanggal"]').daterangepicker({
		singleDatePicker: true,
		showDropdowns: true,
        autoApply: true,
        locale: {
		  format: 'DD-MM-YYYY'
		}
	});

    $('.select2_add').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    
    $('.select2_edit').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalEdit",
        escapeMarkup: function(m) { 
           return m; 
        }
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
        iDisplayLength: 10,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'Bfrtip',
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: {
            "url": "<?php echo site_url('transaction/mutasi_kas/get_ajax') ?>", 
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
            {"data": "tanggal", class:"xcenter"},
            {"data": "akun_mutasi_masuk"},
            {"data": "desc_mutasi_masuk"},
            {"data": "akun_mutasi_keluar"},
            {"data": "desc_mutasi_keluar"},
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
                class:"xcenter",
                orderable: false,
                searchable: false
            },
            {"data": "keterangan", orderable: false, searchable: true},
            //{"data": "cabang_nama"},
            
            
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
                $('#btn-add').attr('disabled','disabled');
                $('#btn-add').removeAttr('onclick');
            }else{
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').removeAttr('disabled');
                $('#btn-save-add').removeAttr('disabled');
                $('#btn-save-edit').removeAttr('disabled');
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
        let id = $('#mutasi_kas_id').val();
        let no_transaksi = $('#no_transaksi').val();
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        let total_det = $('#jumlah').val();
        
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
                            url: '<?php echo base_url(); ?>transaction/mutasi_kas/closing_transaksi/' + id + '/' + cabang,
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
        let id = $('#mutasi_kas_id').val();
        let no_transaksi = $('#no_transaksi').val();
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        let total_det = $('#jumlah').val();
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
                        url: '<?php echo base_url(); ?>transaction/mutasi_kas/reopen_transaksi/' + id + '/' + cabang,
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
                    successMsg(data.message);
                    oTable.api().ajax.reload();
                    
                    //if(data.mutasi_kas_idx > 0){
                        //load popup edit
                    //    getEditById(data.mutasi_kas_idx);
                        
                    //}else{
                        
                        $('#myModal').modal('hide');
                        $('#formadd').trigger("reset");
                    //}
                        
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
    

});


function getAdd() {
    let cabangxx = $('#cabangx').val();
    let cabang_nama = $("#cabangx option:selected").html();
    
    $('#myModal').modal('show');

    $('#cabang_add').val($.trim(cabangxx));
    $('#cabang_nama').val($.trim(cabang_nama));
}
    
function getEdit(event) {
    let id = $(event).data("id").toString().trim();
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>transaction/mutasi_kas/get_data/' + id,
        success: function (result) {
            
            $('#box-title-edit').html('Edit Mutasi Kas Bank '+ result.no_transaksi);
            $('#mutasi_kas_id').val($.trim(result.id));
            $('#no_transaksi').val($.trim(result.no_transaksi));
            $('#cabang_edit').val($.trim(result.cabang));
            $('#cabang_nama_edit').val($.trim(result.cabang_nama));
            $('#keterangan').val($.trim(result.keterangan));
            $('#akun_kas_masuk').val($.trim(result.akun_kas_masuk));
            $('#akun_kas_keluar').val($.trim(result.akun_kas_keluar));
            $('#kode_transaksi_masuk').val($.trim(result.kode_transaksi_masuk));
            $('#kode_transaksi_keluar').val($.trim(result.kode_transaksi_keluar));
            
            $("#tanggal_edit").val(result.tanggalx);
            $('#tanggal_edit').daterangepicker({
                singleDatePicker: true, 
                startDate: result.tanggalx ,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });

            $("#kode_transaksi_masuk").select2("destroy");
            $("#kode_transaksi_masuk").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalEdit",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });
            
            $("#kode_transaksi_keluar").select2("destroy");
            $("#kode_transaksi_keluar").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalEdit",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });
            
            let is_status = '';
            if(result.status == 1){
                is_status = 'Pengakuan';
                $('#tanggal_edit').removeAttr('disabled');
                
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').attr('disabled', 'disabled');
                $('#btn-save-edit').removeAttr('disabled');
            }else
            if(result.status == 2){
                is_status = 'Jurnal';
                $('#tanggal_edit').attr('disabled','disabled');

                $('#btn-closing2').attr('disabled', 'disabled');
                $('#btn-reopen2').removeAttr('disabled');
                $('#btn-save-edit').attr('disabled', 'disabled');
            }else
            if(result.status == 3){
                is_status = 'Posting';
                $('#tanggal_edit').attr('disabled','disabled');

                $('#btn-closing2').attr('disabled', 'disabled');
                $('#btn-reopen2').attr('disabled', 'disabled');
                $('#btn-save-edit').attr('disabled', 'disabled');
            }
            
            $('#status').val($.trim(is_status));
            $('#jumlah').val($.trim(result.jumlah));
            
            
        }

    });
}

function getEditById(id) {
    
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url(); ?>transaction/mutasi_kas/get_data/' + id,
        success: function (result) {
            
            $('#box-title-edit').html('Edit Mutasi Kas Bank '+ result.no_transaksi);
            $('#mutasi_kas_id').val($.trim(result.id));
            $('#no_transaksi').val($.trim(result.no_transaksi));
            $('#cabang_edit').val($.trim(result.cabang));
            $('#cabang_nama_edit').val($.trim(result.cabang_nama));
            $('#keterangan').val($.trim(result.keterangan));
            $('#akun_kas_masuk').val($.trim(result.akun_kas_masuk));
            $('#akun_kas_keluar').val($.trim(result.akun_kas_keluar));
            $('#kode_transaksi_masuk').val($.trim(result.kode_transaksi_masuk));
            $('#kode_transaksi_keluar').val($.trim(result.kode_transaksi_keluar));
            
            $("#tanggal_edit").val(result.tanggalx);
            $('#tanggal_edit').daterangepicker({
                singleDatePicker: true, 
                startDate: result.tanggalx ,
                showDropdowns: true,
                autoApply: true,
                locale: {
                    format: 'DD-MM-YYYY'
                }
            });
            
            $("#kode_transaksi_masuk").select2("destroy");
            $("#kode_transaksi_masuk").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalEdit",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });
            
            $("#kode_transaksi_keluar").select2("destroy");
            $("#kode_transaksi_keluar").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalEdit",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });
            
            let is_status = '';
            if(result.status == 1){
                is_status = 'Pengakuan';
                $('#tanggal_edit').removeAttr('disabled');
                
                $('#btn-closing2').removeAttr('disabled');
                $('#btn-reopen2').attr('disabled', 'disabled');
                $('#btn-save-edit').removeAttr('disabled');
            }else
            if(result.status == 2){
                is_status = 'Jurnal';
                $('#tanggal_edit').attr('disabled','disabled');

                $('#btn-closing2').attr('disabled', 'disabled');
                $('#btn-reopen2').removeAttr('disabled');
                $('#btn-save-edit').attr('disabled', 'disabled');
            }else
            if(result.status == 3){
                is_status = 'Posting';
                $('#tanggal_edit').attr('disabled','disabled');

                $('#btn-closing2').attr('disabled', 'disabled');
                $('#btn-reopen2').attr('disabled', 'disabled');
                $('#btn-save-edit').attr('disabled', 'disabled');
            }
            
            $('#status').val($.trim(is_status));
            $('#jumlah').val($.trim(result.jumlah));
            
            
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
            //delete_recordById('<?php echo base_url() ?>transaction/mutasi_kas/delete/' + id + '/' + cabang, '');
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
                        url: '<?php echo base_url() ?>transaction/mutasi_kas/delete/' + id + '/' + cabang,
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
      
function numberFormatId(num) {
    //return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   
    return parts.join(",");
}
</script>


