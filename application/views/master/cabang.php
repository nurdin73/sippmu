
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
                    <li class="breadcrumb-item"><a href="#!">Master Data</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>master/cabang"><?php echo $title; ?></a></li>
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

                <div class="row">
                    <div class="col-md-12">              
                        <div class="box box-primary" id="tachelist">
                            <div class="box-header ptbnull">
                                <h3 class="box-title titlefix"> Unit Kerja List</h3>
                                <div class="box-tools pull-right">
                                    <?php if ($this->rbac->hasPrivilege('master_cabang', 'can_add')) { ?>
                                        <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i>  Tambah Unit Kerja</a>     
                                    <?php } ?> 
                                </div>
                            </div>
                            <div class="box-body">
                                <div class="mailbox-controls">
                                </div>
                                <div class="table-responsive mailbox-messages">
                                    <div class="download_label">Master Unit Kerja</div>
                                    <table class="table table-striped" id="dt_table">
                                        <thead>
                                            <tr>
                                                <th>Kode</th>
                                                <th>Parent</th>
                                                <th>Nama Unit Kerja</th>
                                                <!-- <th>Alamat</th> -->
                                                <th>Group</th>
                                                <th>Status</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                    </table>
                                </div>
                            </div>

                            <div class="">
                                <div class="mailbox-controls">
                                </div>
                            </div>
                        </div>
                    </div> 
                </div>
            
            </div>
        </div>
    </div>
</div>


<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Tambah Unit Kerja</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('master/cabang/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="klasifikasi">Group Unit Kerja</label><small class="req"> *</small>
                            <select name="klasifikasi" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($klasifikasis as $key => $row) {
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('klasifikasi'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="parent"><?php echo $this->lang->line('parent'); ?></label>
                            <select name="parent" class="form-control" >
                                <option value="0">- Parent -</option>
                                <?php
                                foreach ($parents as $key => $row) {
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('parent'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode"><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small>
                                    <input autofocus="" name="kode" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('kode'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="nama" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('nama'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat"><?php echo $this->lang->line('address'); ?></label>
                            <input autofocus="" name="alamat" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('alamat'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_telp"><?php echo $this->lang->line('no_telp'); ?></label><small class="req"> *</small>
                                    <input autofocus="" name="no_telp" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('no_telp'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fax"><?php echo $this->lang->line('fax'); ?></label>
                                    <input autofocus="" name="fax" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('fax'); ?></span>
                                </div>
                            </div>
                        </div>
                        
<!-- 
                        <div class="form-group">
                            <label for="website"><?php echo $this->lang->line('website'); ?></label>
                            <input autofocus="" name="website" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('website'); ?></span>
                        </div> -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active"><?php echo $this->lang->line('active'); ?> <?php echo $this->lang->line('status'); ?></label>
                                    <br/>
                                    <label class="radio-inline">
                                        <input type="radio" checked value="1" name="is_active"><?php echo $this->lang->line('yes'); ?>
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio" value="0" name="is_active"><?php echo $this->lang->line('no'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_pusat"><?php echo $this->lang->line('is_pusat'); ?> </label>
                                    <br/>
                                    <label class="radio-inline">
                                        <input type="radio" value="1" name="is_pusat"><?php echo $this->lang->line('yes'); ?>
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio" checked value="0" name="is_pusat"><?php echo $this->lang->line('no'); ?>
                                    </label>
                                </div>
                            </div>
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
                <h4 class="box-title"> Edit Unit Kerja</h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('master/cabang/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        
                        <div class="form-group">
                            <label for="klasifikasi">Group Unit Kerja</label><small class="req"> *</small>
                            <select  id="klasifikasi" name="klasifikasi" class="form-control" >
                                <option value=""><?php echo $this->lang->line('select'); ?></option>
                                <?php
                                foreach ($klasifikasis as $key => $row) {
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('klasifikasi'); ?></span>
                        </div>

                        <div class="form-group">
                            <label for="parent"><?php echo $this->lang->line('parent'); ?></label>
                            <select  id="parent" name="parent" class="form-control" >
                                <option value="0">- Parent -</option>
                                <?php
                                foreach ($parents as $key => $row) {
                                    ?>
                                    <option value="<?php echo $row['id'] ?>"> <?php echo $row["kode"] ?> - <?php echo $row["nama"] ?></option>
                                <?php }
                                ?>
                            </select>
                            <span class="text-danger"><?php echo form_error('parent'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="kode"><?php echo $this->lang->line('code'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="kode"  name="kode" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('kode'); ?></span>
                                </div>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="nama"><?php echo $this->lang->line('name'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="nama"  name="nama" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('nama'); ?></span>
                        </div>
                        
                        <div class="form-group">
                            <label for="alamat"><?php echo $this->lang->line('address'); ?></label>
                            <input autofocus="" id="alamat"  name="alamat" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('alamat'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="no_telp"><?php echo $this->lang->line('no_telp'); ?></label><small class="req"> *</small>
                                    <input autofocus="" id="no_telp"  name="no_telp" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('no_telp'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="fax"><?php echo $this->lang->line('fax'); ?></label>
                                    <input autofocus="" id="fax"  name="fax" placeholder="" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('fax'); ?></span>
                                </div>
                            </div>
                        </div>
                        

                        <!-- <div class="form-group">
                            <label for="website"><?php echo $this->lang->line('website'); ?></label>
                            <input autofocus="" id="website"  name="website" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('website'); ?></span>
                        </div> -->

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_active"><?php echo $this->lang->line('active'); ?> <?php echo $this->lang->line('status'); ?></label>
                                    <br/>
                                    <label class="radio-inline">
                                        <input type="radio" value="1" id="is_active_A" name="is_active"><?php echo $this->lang->line('yes'); ?>
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio" value="0" id="is_active_T" name="is_active"><?php echo $this->lang->line('no'); ?>
                                    </label>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="is_pusat"><?php echo $this->lang->line('is_pusat'); ?> </label>
                                    <br/>
                                    <label class="radio-inline">
                                        <input type="radio" value="1" id="is_pusat_A" name="is_pusat"><?php echo $this->lang->line('yes'); ?>
                                    </label>

                                    <label class="radio-inline">
                                        <input type="radio" value="0" id="is_pusat_T" name="is_pusat"><?php echo $this->lang->line('no'); ?>
                                    </label>
                                </div>
                            </div>
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
        ajax: {"url": "<?php echo site_url('master/cabang/get_ajax') ?>", "type": "POST"},
        columns: [
            {"data": "kode"},
            //{"data": "parent_kode"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.parent_kode == null || data.parent_kode == 0){
                        return ' - ';
                    }else{
                        return data.parent_kode;
                    }
                },
                // class:"xcenter",
                orderable: false,
                searchable: false,
                //width: "25%",
            },
            {"data": "nama"},
            // {"data": "alamat"},
            {"data": "nama_klasifikasi"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.is_active == 't'){
                        return 'Aktif';
                    }else{
                        return 'Tidak Aktif';
                    }
                },
                class:"xcenter",
                orderable: false,
                searchable: false,
                //width: "25%",
            },
            {"data": "view"}
        ],
        lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],

        columnDefs: [
            {

                "targets": [-1], //last column
                "orderable": false, //set not orderable
            },
            {
                "targets": [-1],
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
            $('td:eq(0)', row).html();
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

        url: '<?php echo base_url(); ?>master/cabang/get_data/' + id,

        success: function (result) {
            //alert($.trim(result.id));
            $('#idx').val($.trim(result.id));
            $('#nama').val($.trim(result.nama));
            $('#kode').val($.trim(result.kode));
            $('#no_telp').val($.trim(result.no_telp));
            $('#fax').val($.trim(result.fax));
            $('#alamat').val($.trim(result.alamat));
            $('#website').val($.trim(result.website));
            $("#klasifikasi option[value='"+$.trim(result.klasifikasi)+"']").prop('selected', true);
            $("#parent option[value='"+$.trim(result.parent)+"']").prop('selected', true);
            if ($.trim(result.is_active) == 't') {
                $('#is_active_A').prop('checked', true);
            }
            if ($.trim(result.is_active) == 'f') {
                $('#is_active_T').prop('checked', true);
            }
            if ($.trim(result.is_pusat) == 't') {
                $('#is_pusat_A').prop('checked', true);
            }
            if ($.trim(result.is_pusat) == 'f') {
                $('#is_pusat_T').prop('checked', true);
            }

        }

    });

}

function deleterecord(event)
{
    let id = $(event).data("id").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    delete_recordById('<?php echo base_url() ?>master/cabang/delete/' + id, nama);
    
}
</script>


