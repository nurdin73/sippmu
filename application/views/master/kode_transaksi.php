<!-- [ breadcrumb ] start -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10"><?php echo $title; ?> (Kode Mutasi)</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Master Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>master/kode_transaksi"><?php echo $title; ?></a></li>
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

                <div class="xright text-right">
                    <?php if ($this->rbac->hasPrivilege('master_kode_transaksi', 'can_add')) { ?>
                        <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i>  Tambah Kode Transaksi</a>    
                    <?php } ?> 
                    <br><br>
                </div>
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Master <?php echo $this->lang->line('kode_transaksi'); ?></div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>Kodex</th>
                                <th>Kode</th>
                                <th>Deskripsi Transaksi</th>
                                <th>Parent</th>
                                <th>Level</th>
                                <th>Tipe</th>
                                <th>Nama Akun Rekening</th>
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
                <h4 class="box-title"> Tambah <?php echo $this->lang->line('kode_transaksi'); ?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('master/kode_transaksi/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="parent">Parent</label>
                            <select id="parentid" name="parent" class="form-control" >
                                <option value="0">- Parent -</option>
                                <?php
                                foreach ($cbx_parent as $key => $row) {
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
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row["kode"] ?> - <?php echo $row["deskripsi"] ?></option>
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
                            <label for="deskripsi">Deskripsi Transaksi</label><small class="req"> *</small>
                            <input autofocus="" name="deskripsi" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi'); ?></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Level</label><small class="req"> *</small>
                                    <input id="levelx" name="level" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('level'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe">Tipe</label><small class="req"> *</small>
                                    <input autofocus="" name="tipe" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('tipe'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="akun">Kode Rekening</label>
                            <select name="akun" class="form-control select2_add">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($cbx_akun as $key => $row) {
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
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('akun'); ?></span>
                        </div>

                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" value="" />
                        <button type="button" class="btn btn-default pull-right" style="margin-left:5px;"  data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>


<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Edit <?php echo $this->lang->line('kode_transaksi'); ?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('master/kode_transaksi/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="parent">Parent</label>
                            <select readonly id="parent" name="parent" class="form-control select2_edit">
                                <option value="0">- Parent -</option>
                                <?php
                                foreach ($cbx_parent as $key => $row) {
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
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row["kode"] ?> - <?php echo $row["deskripsi"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('parent'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode">Kode</label><small class="req"> *</small>
                                    <input readonly id="kode" name="kode" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('kode'); ?></span>
                                </div>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="deskripsi">Deskripsi Transaksi</label><small class="req"> *</small>
                            <input id="deskripsi" name="deskripsi" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi'); ?></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="level">Level</label><small class="req"> *</small>
                                    <input id="level" name="level" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('level'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="tipe">Tipe</label><small class="req"> *</small>
                                    <input id="tipe" name="tipe" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('tipe'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="akun">Kode Rekening</label>
                            <select id="akun" name="akun" class="form-control select2_edit">
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($cbx_akun as $key => $row) {
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
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $space ?> <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('akun'); ?></span>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" id="idx" value="" />
                        <button type="button" class="btn btn-default pull-right" style="margin-left:5px;"  data-dismiss="modal">Close</button>
                        <button type="submit" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>
<script>
$(document).ready(function (e) {
    var placeholder = "&#xf002 - Pilih -";
    $('.select2_add').val(null).trigger('change');
    $('.select2_add').select2({ 
        placeholder: placeholder,
        width: '100%', 
        dropdownParent: "#myModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    
    $('.select2_edit').select2({ 
        placeholder: placeholder,
        width: '100%', 
        dropdownParent: "#editmyModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    $('#parentid').select2({ 
        placeholder: placeholder,
        width: '100%', 
        dropdownParent: "#myModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    $('#parentid').on('select2:select', function (e) {
        var data = e.params.data;
        var value = data.id;
        
        $.ajax({
            dataType: 'json',
            url: '<?php echo base_url(); ?>master/kode_transaksi/get_kode_parent/' + value,
            success: function (result) {
                //alert($.trim(result));
                $('#kodex').val($.trim(result.next_kode));
                $('#levelx').val($.trim(result.next_level));
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
        ajax: {"url": "<?php echo site_url('master/kode_transaksi/get_ajax') ?>", "type": "POST"},
        columns: [
            {"data": "kode"},
            { 
                data: null, render: function ( data, type, row ) {
                    let spaces = '';
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
                    return spaces + data.kode2;
                },
                width: "15%",
                orderable: false,
                searchable: false
            },
            {"data": "deskripsi", searchable: true},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.parent_kode == null || data.parent_kode == 0){
                        return ' - ';
                    }else{
                        return data.parent_kode;
                    }
                },
                class:"xcenter",
                //width: "25%",
                orderable: false,
                searchable: false
            },
            {"data": "level", class:"xcenter"},
            //{"data": "tipe", class:"xcenter"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.tipe == '1'){
                        return 'Penerimaan';
                    }else if(data.tipe == '2'){
                        return 'Pengeluaran';
                    }else if(data.tipe == '3'){
                        return 'Mutasi Masuk';
                    }else if(data.tipe == '4'){
                        return 'Mutasi Keluar';
                    }
                },
                //width: "25%",
                orderable: false,
                searchable: false
            },
            {"data": "akun_nama", width: "25%"},
            {"data": "view", width: "7%"}
        ],
        lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],

        columnDefs: [
            {
                "targets": [ 0 ],
                "visible": false,
                "searchable": true
            },
            {
                "targets": [ 2 ],
                "visible": true,
                "searchable": true
            },
            {
                "targets": [-1], //last column
                "orderable": false, //set not orderable
                className: "text-right"
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
        order: [[0, 'asc']],
        rowCallback: function (row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            //$('td:eq(0)', row).html();
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

                    successMsg(data.message);
                    //window.location.reload(true);
                    oTable.api().ajax.reload();
                    $('#myModal').modal('hide');
                    $('#formadd').trigger("reset");
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
                    $('#editmyModal').modal('hide');
                    $('#formedit').trigger("reset");
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
   
function get(event) {

    let id = $(event).data("id").toString().trim();
    //alert(id);
    $('#editmyModal').modal('show');

    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>master/kode_transaksi/get_data/' + id,

        success: function (result) {
            //alert($.trim(result.id));
            $('#idx').val($.trim(result.id));
            $('#deskripsi').val($.trim(result.deskripsi));
            $('#kode').val($.trim(result.kode));
            $('#level').val($.trim(result.level));
            $('#tipe').val($.trim(result.tipe));
            //$("#akun option[value='"+$.trim(result.akun)+"']").prop('selected', true);
            //$("#parent option[value='"+$.trim(result.parent)+"']").prop('selected', true);
            
            $('#akun').select2('val', $.trim(result.akun));
            $('#akun').trigger('change');
            
            $('#parent').select2('val', $.trim(result.parent));
            $('#parent').trigger('change');
            
        }

    });

}

function deleterecord(event)
{
    let id = $(event).data("id").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    delete_recordById('<?php echo base_url() ?>master/kode_transaksi/delete/' + id, nama);
}
</script>


