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
                    <li class="breadcrumb-item"><a href="#!">Master Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>master/akun_kas"><?php echo $title; ?></a></li>
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
                <form action="<?php echo site_url('master/akun_kas') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                        
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="tanggal">&nbsp; &nbsp;</label>
                                <a id="src_cari" class="btn btn-primary pull-right btn-sm text-white"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></a>
                            </div>
                        </div>
                        <div class="col-md-6 xright">
                            <?php if ($this->rbac->hasPrivilege('master_akun_kas', 'can_add')) { ?>
                                <a onclick="getAdd()" id="btn-add" class="btn btn-success btn-sm"><i class="fa fa-plus-square-o"></i> Tambah</a>    
                            <?php } ?> 
                        </div>
                    </div>
                </form>
            </div>
            <div class="card-body">
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Master <?php echo $this->lang->line('akun_kas'); ?></div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Deskripsi</th>
                                <th>Kode Akun</th>
                                <th>Nama Akun</th>
                                <th>Saldo Awal</th>
                                <th>Saldo Akhir</th>
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
                <h4 class="box-title"> Tambah <?php echo $this->lang->line('akun_kas'); ?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formadd" action="<?php echo site_url('master/akun_kas/add') ?>" name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_add" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input disabled id="cabang_nama_add" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="parent"><?php echo $this->lang->line('akun'); ?></label><small class="req"> *</small>
                            <select  name="akun" class="form-control select2_add" >
                                <option value="0">- Pilih Kode Rekening -</option>
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
                            <label for="deskripsi"><?php echo $this->lang->line('deskripsi'); ?></label><small class="req"> *</small>
                            <input autofocus="" name="deskripsi" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi'); ?></span>
                        </div>

                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="saldo_awal"><?php echo $this->lang->line('saldo_awal'); ?></label><small class="req"> *</small>
                                    <input id="saldo_awal_add" name="saldo_awal" step="any" type="number" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('saldo_awal'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" value="" />
                        <button type="submit" id="btn-save-add" class="btn btn-info pull-right"> Simpan</button>
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
                <h4 class="box-title"> Edit <?php echo $this->lang->line('akun_kas'); ?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('master/akun_kas/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="form-group">
                                    <label for="kode">Unit Kerja</label>
                                    <input id="cabang_edit" name="cabang" type="hidden" class="form-control"  value="" />
                                    <input disabled id="cabang_nama_edit" name="cabang_nama" type="text" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('cabang'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="akun"><?php echo $this->lang->line('akun'); ?></label><small class="req"> *</small>
                            <select  id="akun" name="akun" class="form-controlx" >
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
                            <label for="deskripsi"><?php echo $this->lang->line('deskripsi'); ?></label><small class="req"> *</small>
                            <input autofocus="" id="deskripsi"  name="deskripsi" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('deskripsi'); ?></span>
                        </div>

                         <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="saldo_awal"><?php echo $this->lang->line('saldo_awal'); ?></label><small class="req"> *</small>
                                    <input id="saldo_awal_edit"  name="saldo_awal" step="any" type="number" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('saldo_awal'); ?></span>
                                </div>
                             </div>
                        </div>

                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" id="idx" value="" />
                        <button type="submit" id="btn-save-edit" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>

<script>
$(document).ready(function (e) {
    
     $('.select2_add').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#myModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
     $('#akun').select2({ 
        placeholder: "&#xf002 - Pilih -",
        width: '100%', 
        dropdownParent: "#editmyModal",
        escapeMarkup: function(m) { 
           return m; 
        }
    });
    $("#akun").val("");
    $("#akun").trigger("change");
    
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
            "url": "<?php echo site_url('master/akun_kas/get_ajax') ?>", 
            "type": "POST",
            "data": function ( d ) {
                d.cabang = $('#cabang').val();
                d.cabangx = $('#cabangx').val();
            }
        },
        columns: [
            {"data": "nomor", width: "5%"},
            {"data": "deskripsi"},
            {"data": "kode_akun"},
            {"data": "nama_akun"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.saldo_awal > 0){
                        //return number_format(data.saldo_awal,0,',','.');
                        return numberFormatId(data.saldo_awal);
                    }else{
                        return 0;
                    }
                },
                class:"xright",
                //width: "25%",
                //orderable: false,
                //searchable: false
            },
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.saldo_akhir > 0){
                        //return number_format(data.saldo_akhir,0,',','.');
                        return numberFormatId(data.saldo_akhir);
                    }else{
                        return 0;
                    }
                },
                class:"xright",
            },
            {"data": "view", width: "7%"}
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
    
    $(document).on('change', '#cabangx', function () {
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            oTable.api().ajax.reload();
            
            if(cabang !== src_cabangx){
                $('#btn-save-add').attr('disabled','disabled');
                $('#btn-save-edit').attr('disabled','disabled');
            }else{
                $('#btn-save-add').removeAttr('disabled');
                $('#btn-save-edit').removeAttr('disabled');
            }


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
    let cabangxx = $('#cabangx').val();
    let cabang_nama = $("#cabangx option:selected").html();
    
    $('#myModal').modal('show');

    $('#cabang_add').val($.trim(cabangxx));
    $('#cabang_nama_add').val($.trim(cabang_nama));
}
    
function get(event) {

    let id = $(event).data("id").toString().trim();
    let cabangxx = $('#cabangx').val();
    let cabang_nama = $("#cabangx option:selected").html();
    //alert(cabang_nama);
    $('#editmyModal').modal('show');

    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>master/akun_kas/get_data/' + id,

        success: function (result) {
            //alert($.trim(result.id));
            $('#idx').val($.trim(result.id));
            $('#deskripsi').val($.trim(result.deskripsi));
            $('#saldo_awal_edit').val($.trim(result.saldo_awal));
            //$('#saldo_akhir').val($.trim(result.saldo_akhir));
            //$("#akun option[value='"+$.trim(result.akun)+"']").prop('selected', true);
            $("#akun").val($.trim(result.akun));
            $("#akun").trigger("change");
            
            $('#cabang_edit').val($.trim(result.cabang));
            $('#cabang_nama_edit').val($.trim(result.cabang_nama));
        }

    });

}

function deleterecord(event)
{
    let cabang = $('#cabang').val();
    let src_cabangx = $('#cabangx option:selected').val();
    
    if(cabang !== src_cabangx){
        errorMsg('Maaf tidak boleh menghapus data cabang lain !');
    }else{
        let id = $(event).data("id").toString().trim();
        let nama = $(event).data("nama").toString().trim();
        delete_recordById('<?php echo base_url() ?>master/akun_kas/delete/' + id, nama);
    }
}
    
function number_format (number, decimals, dec_point, thousands_sep) {
    number = (number + '').replace(/[^0-9+\-Ee.]/g, '');
    var n = !isFinite(+number) ? 0 : +number,
        prec = !isFinite(+decimals) ? 0 : Math.abs(decimals),
        sep = (typeof thousands_sep === 'undefined') ? ',' : thousands_sep,
        dec = (typeof dec_point === 'undefined') ? '.' : dec_point,
        s = '',
        toFixedFix = function (n, prec) {
            var k = Math.pow(10, prec);
            return '' + Math.round(n * k) / k;
        };
    s = (prec ? toFixedFix(n, prec) : '' + Math.round(n)).split('.');
    if (s[0].length > 3) {
        s[0] = s[0].replace(/\B(?=(?:\d{3})+(?!\d))/g, sep);
    }
    if ((s[1] || '').length < prec) {
        s[1] = s[1] || '';
        s[1] += new Array(prec - s[1].length + 1).join('0');
    }
    return s.join(dec);
}
    
function numberFormatId(num) {
    //return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, '$1,')
    var parts = num.toString().split(".");
    parts[0] = parts[0].replace(/\B(?=(\d{3})+(?!\d))/g, ".");
   
    return parts.join(",");
}
</script>


