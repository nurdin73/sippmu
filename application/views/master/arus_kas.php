
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10"><?php echo $title; ?></h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Master Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>master/arus_kas"><?php echo $title; ?></a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- table start -->
    <div class="col-sm-12">
        <div class="card">
            <div class="card-body">

                <div class="xright xright">
                    <?php if ($this->rbac->hasPrivilege('master_arus_kas', 'can_add')) { ?>
                        <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i> Tambah</a>    
                    <?php } ?> 
                    <br><br>
                </div>
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label"><?php echo $title;?></div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Level</th>
                                <th>Action</th>
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
                <h4 class="box-title"> Tambah <?php echo $title;?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('master/arus_kas/add') ?>" name="form-arus_kas" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="parent">Parent</label>
                            <select id="parentid" name="parent" class="form-control select2_add" >
                                <option value="0">- Parent -</option>
                                <?php
                                foreach ($cbx_parent_arus_kas as $key => $row) {
                                    $space = '';
                                    if($row['level'] == '1'){
                                        $space = ' > &#xf149;';
                                    }
                                    if($row['level'] == '2'){
                                        $space = ' > > &#xf149;';
                                    }
                                    if($row['level'] == '3'){
                                        $space = ' > > > &#xf149;';
                                    }
                                    if($row['level'] == '4'){
                                        $space = ' > > > > &#xf149;';
                                    }
                                    if($row['level'] == '5'){
                                        $space = ' > > > > > &#xf149;';
                                    }
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('parent'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode">Kode</label><small class="req"> *</small>
                                    <input id="kodex" name="kode" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('kode'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="nama">Nama</label><small class="req"> *</small>
                            <input autofocus="" name="nama" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('nama'); ?></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-3">
                                <div class="form-group">
                                    <label for="level">Level</label><small class="req"> *</small>
                                    <select id="levelx" name="level" class="form-control" >
                                        <option value="0">- Pilih -</option>
                                        <option value="1"> 1</option>
                                        <option value="2"> 2</option>
                                        <option value="3"> 3</option>
                                        <!-- <option value="4"> 4</option> -->
                                    </select>
                                    <span class="text-danger"><?php echo form_error('level'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" value="" />
                        <input type="button" class="btn btn-default pull-right" id="btn-close" style="margin-left:5px;" value="Tutup">
                        <button type="submit" class="btn btn-info pull-right" > Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>

<div class="modal fade" id="myModalEdit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Mapping Akun <?php echo $title;?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('master/arus_kas/edit') ?>" class="form-horizontal" role="form" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="nama_level" class="col-md-2 control-label" style="vertical-align:top; padding-top:0">Arus Kas</label>
                                    <div class="col-md-8">
                                        <div id="nama_level"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                         
                        
                        <div class="row">
                            <div class="col-md-12 text-right">
                                <?php if ($this->rbac->hasPrivilege('master_arus_kas', 'can_add')) { ?>
                                    <a onclick="getAddDetail()" id="btn_add_detail" class="btn btn-success btn-sm"><i class="fa fa-plus"></i>  Tambah Detail</a>    
                                <?php } ?> 
                            </div>
                        </div>
                        
                        <div class="table-responsive mailbox-messages">
                            <table class="table table-striped" id="dt_table_detail">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Kode Rekening</th>
                                        <th>Nama Rekening</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                            </table>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="arus_kas_id" name="arus_kas_id" value="" />
                        <input type="button" class="btn btn-default pull-right" id="btn-close2" style="margin-left:5px;" value="Tutup">
                        <button type="submit" class="btn btn-info pull-right" > Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>

<div class="modal fade" id="myModalDetail" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title" id="myModalDetail_title"> Tambah Mapping Akun</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="form_detail" action="<?php echo site_url('master/arus_kas/do_detail') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="akun">Akun Mutasi</label>
                            <select  id="akun" name="akun" class="form-control select2_adddetail" >
                                <?php
                                foreach ($cbx_kode_mutasi as $key => $row) {
                                    $space = '';
                                    if($row['level'] == '1'){
                                        $space = '&gt; ';
                                        $is_disabled = 'disabled';
                                    }
                                    if($row['level'] == '2'){
                                        $space = '&nbsp; &gt; &gt; ';
                                        $is_disabled = 'disabled';
                                    }
                                    if($row['level'] == '3'){
                                        $space = '&nbsp; &nbsp; &gt; &gt; &gt; ';
                                        $is_disabled = '';
                                    }
                                    ?>
                                    <option value="<?php echo $row['id'] ?>" <?php echo $is_disabled; ?>> <?php echo $space ?> <?php echo $row['kode'] ?> - <?php echo $row["deskripsi"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('akun'); ?></span>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" id="act_detail" name="act" value="" />
                        <input type="hidden" id="arus_kas_det_id" name="arus_kas_det_id" value="" />
                        <input type="hidden" id="arus_kas_id_detail" name="arus_kas_id" value="" />
                        <button type="submit" id="btn-detail" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>

<!--<script src="<?php echo base_url(); ?>assets/js/app/master_arus_kas.js"></script>-->
<script type="text/javascript">
var base_urlx = "<?php echo base_url(); ?>";
 
$(document).ready(function (e) {
    
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
    $('#akun').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalDetail",
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
        iDisplayLength: 100,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'Bfrtip',
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: {
            "url": base_urlx + 'master/arus_kas/get_ajax', 
            "type": "POST"
        },
        columns: [
            {"data": "nomor", width: "7%"},
            {"data": "kode", width: "20%"},
            { 
                data: null, render: function ( data, type, row ) {
                    var spaces = '';
                    if(data.level == 1){
                        spaces = '<span class="pdl-1"></span>';
                    }
                    if(data.level == 2){
                        spaces = '<span class="pdl-2"></span>';
                    }
                    if(data.level == 3){
                        spaces = '<span class="pdl-3"></span>';
                    }
                    if(data.level == 4){
                        spaces = '<span class="pdl-4"></span>';
                    }
                    if(data.level == 5){
                        spaces = '<span class="pdl-5"></span>';
                    }
                    if(data.level == 6){
                        spaces = '<span class="pdl-6"></span>';
                    }
                    return spaces + data.nama;
                },
                orderable: false,
                searchable: false
            },
            {"data": "level", width: "10%"},
            { 
                data: null, render: function ( data, type, row ) {
                    
                    //if(data.level == 4){
                    if(data.level == 3){
                        return '<a onclick="getEdit(this)" data-id="'+data.id+'" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title="Mapping Akun">'+
                                    '<i class="fa fa-pencil"></i> Mapping Akun</a> '+
                               '<a onclick="deleteData(this)"  data-id="'+data.id+'" data-nama="'+data.nama+'" class="btn btn-default btn-xs" data-toggle="tooltip" title=""  data-original-title="Hapus">'+
                                    '<i class="fa fa-trash"></i></a>';
                    }else{
                        return null;
                    }
                },
                width: "15%",
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
        order: [[1, 'ASC']],
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
    
    $('#parentid').on('select2:select', function (e) {
        var data = e.params.data;
        var value = data.id;
        
        $.ajax({
            dataType: 'json',
            url: base_urlx + 'master/arus_kas//get_kode_parent/' + value,
            success: function (result) {
                //alert($.trim(result));
                $('#kodex').val($.trim(result.next_kode));
                //$('#levelx').val($.trim(result.next_level));
                $("#levelx option[value='"+$.trim(result.next_level)+"']").prop('selected', true);
            }

        });
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
                    
                    if(data.levelx == 4){
                        oTable.api().ajax.reload();
                        $('#myModal').modal('hide');
                        $('#formadd').trigger("reset");

                        //show popup detail
                        getDetail(data.arus_kas_idx);
                    }else{
                        window.location.reload(true);
                    }
                    
                    $('#parentid').select2('val', '');
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
                    //window.location.reload(true);
                    oTable.api().ajax.reload();
                    $('#myModalEdit').modal('hide');
                    $('#formedit').trigger("reset");
                    
                    $('#parentid').select2('val', '');
                    //window.location.reload(true);
                }

            },
            error: function () {

            }
        });

    }));
    
    // detail datatables
    var oTableDet = $("#dt_table_detail").dataTable({
        initComplete: function () {
            var api = this.api();
            $('#dt_table_detail_filter input')
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
            "url": base_urlx + 'master/arus_kas/get_ajax_detail', 
            "type": "POST",
            "data": function ( d ) {
                d.arus_kas_id = $('#arus_kas_id').val();
            }
        },
        columns: [
            {"data": "nomor", width: "5%"},
            {"data": "mutasi_kode"},
            {"data": "mutasi_nama"},
            {"data": "view", width: "5%"}
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
                    //successMsg(data.message);
                    
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
    
    $('#myModal').modal('show');

}
   
function getEdit(event) {
    var id = $(event).data("id").toString().trim();
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: base_urlx + 'master/arus_kas/get_data/' + id,
        success: function (result) {
            
            $('#arus_kas_id').val($.trim(result.id));
            //$('#nama_level').html(': '+ $.trim(result.nama_lv1) + '<br>: ' + $.trim(result.nama_lv2) + '<br>: ' + $.trim(result.nama_lv3) + '<br>: ' + $.trim(result.nama_lv4));
            $('#nama_level').html(': &gt; '+ $.trim(result.nama_lv1) + '<br> &nbsp; &nbsp; &gt; &gt; ' + $.trim(result.nama_lv2) + '<br>&nbsp; &nbsp; &nbsp; &gt; &gt; &gt; <b>' + $.trim(result.nama_lv3) + '</b>');
            
            //reload table detail
            $("#dt_table_detail").dataTable().api().ajax.reload();
        }

    });
}

function getDetail(id) {
    $('#myModalEdit').modal('show');

    $.ajax({
        dataType: 'json',
        url: base_urlx + 'master/arus_kas/get_data/' + id,
        success: function (result) {
            
            $('#arus_kas_id').val($.trim(result.id));
            //$('#nama_level').html(': '+ $.trim(result.nama_lv1) + '<br>: ' + $.trim(result.nama_lv2) + '<br>: ' + $.trim(result.nama_lv3) + '<br>: ' + $.trim(result.nama_lv4));
            $('#nama_level').html(': &gt; '+ $.trim(result.nama_lv1) + '<br> &nbsp; &nbsp; &nbsp; &nbsp; &gt; &gt; ' + $.trim(result.nama_lv2) + '<br> &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &gt; &gt; &gt; <b>' + $.trim(result.nama_lv3) + '</b>');
            
            //reload table detail
            $("#dt_table_detail").dataTable().api().ajax.reload();
        }

    });

}

function getAddDetail() {
    var arus_kas_id = $("#arus_kas_id").val();

    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Tambah Detail");
    
    $('#act_detail').val('add');
    $('#arus_kas_det_id').val('');
    $('#arus_kas_id_detail').val($.trim(arus_kas_id));

    $("#akun").val("");
    $("#akun").select2("destroy");
    $("#akun").select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModalDetail",
        escapeMarkup: function(m) { 
           return m; 
        }
    });

    //reload table detail
    //$("#dt_table_detail").dataTable().api().ajax.reload();
}
   
function getEditDetail(event) {
    var id = $(event).data("id").toString().trim();
    var arus_kas_id = $("#arus_kas_id").val();
    
    $('#myModalDetail').modal('show');
    $("#myModalDetail_title").html("Edit Detail");
    
    $.ajax({
        dataType: 'json',
        url: base_urlx + 'master/arus_kas/get_data_detail/' + id,
        success: function (result) {
            
            $('#act_detail').val('edit');
            $('#arus_kas_det_id').val($.trim(result.id));
            $('#arus_kas_id_detail').val($.trim(result.arus_kas));
            $('#akun').val($.trim(result.akun));

            $("#akun").select2("destroy");
            $("#akun").select2({ 
                placeholder: "&#xf002 - Pilih -",
                width: '100%', 
                dropdownParent: "#myModalDetail",
                escapeMarkup: function(m) { 
                   return m; 
                }
            });

            //reload table detail
            //$("#dt_table_detail").dataTable().api().ajax.reload();

        }

    });
}
 
function deleteData(event) {
    let id = $(event).data("id").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    
    if(id){
        delete_recordById(base_urlx + 'master/arus_kas/delete/' + id, nama);
    }
}
      
function deleteDataDetail(event) {
    let id = $(event).data("id").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    let arus_kas_id = $("#arus_kas_id").val();
   
    if(arus_kas_id){
        //delete_recordById(base_urlx + 'master/arus_kas/deleteDetail/' + id + '/' + arus_kas_id, nama, 'no');
        var url = base_urlx + 'master/arus_kas/deleteDetail/' + id + '/' + arus_kas_id;
        Swal.fire({
              title: 'Peringatan!',
              text: "Anda yakin akan menghapus data '"+nama+"' ?",
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
                            $("#dt_table_detail").dataTable().api().ajax.reload();

                            // Swal.fire(
                            //   'Deleted!',
                            //   'Data berhasil di hapus.',
                            //   'success'
                            // );

                        }
                    })

              }
        });

    }
    
}
      
</script>
