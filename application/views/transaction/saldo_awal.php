<!-- [ breadcrumb ] -->
<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Saldo Awal</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#!">Transaksi Akuntansi</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url();?>transaction/saldo_awal">Saldo Awal</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        
        <div class="card">
            <div class="card-header">
                <form action="<?php echo site_url('transaction/saldo_awal') ?>"  name="search_form" method="post" accept-charset="utf-8">
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
                        <div class="col-md-3">
                            <div class="form-group"> 
                                <label for="status">Periode Saldo Awal:</label>
                                <input type="hidden" id="closing_saldo_awal" name="closing_saldo_awal" value="<?php echo $closing_saldo_awal;?>" />
                                <input type="hidden" id="periode_saldo_awal" name="periode_saldo_awal" value="<?php echo $periode_saldo_awal;?>" />
                                <input readonly type="text" name="periode_saldo_awal_txt" value="<?php echo $periode_saldo_awal_txt;?>" class="form-control" />
                            </div>   
                        </div>
                        
                        <div class="col-md-1">
                            <div class="form-group">
                                <label for="tanggal">&nbsp; &nbsp;</label>
                                <a id="src_cari" class="btn btn-primary pull-right btn-sm text-white"><i class="fa fa-search"></i> <?php echo $this->lang->line('search'); ?></a>
                            </div>
                        </div>
                        <div class="col-md-4 text-right">
                            <div class="form-group">
                                <label for="">&nbsp; &nbsp;</label>
                                <?php if ($this->rbac->hasPrivilege('trx_saldo_awal', 'can_add')) { ?>
                                    <a onclick="closingSaldoAwal()" id="btnClosingSaldoAwal" class="btn btn-danger pull-right btn-sm checkbox-toggle"><i class="fa fa-close"></i> Closing Saldo Awal</a>
                                <?php } ?> 
                            </div>
                        </div>
                        
                        
                    </div>
                </form>
            </div>
            <div class="card-body">
                <div class="box-header ptbnull">
                    <h3 class="box-title titlefix"></h3>
                    <div class="box-total pull-right">
                        <table style="width:100%">
                            <tr>
                                <td style="width:300px;text-align:right; "><div id="total_debet_txt" class="total_debet btn btn-default btn-sm">Total Debet: Rp. 0</div></td>
                                <td style="width:300px;text-align:right; "><div id="total_kredit_txt" class="total_kredit btn btn-default btn-sm">Total Kredit: Rp. 0</div></td>
                                <td colspan="2">
                                    <input type="hidden" id="total_debet" name="total_debet" />
                                    <input type="hidden" id="total_kredit" name="total_kredit" />
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                
                <div class="table-responsive mailbox-messages">
                    <div class="download_label">Saldo Awal</div>
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>Kodex</th>
                                <th>Nama</th>
                                <th>Kode Rekening</th>
                                <th>Akun D/K</th>
                                <th>Jml Debet</th>
                                <th>Jml Kredit</th>
                                <th>Keterangan</th>
                                <th>LVL</th>
                                <th></th>
                            </tr>
                        </thead>
                    </table>
                </div>

            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="editmyModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Edit <?php echo $this->lang->line('akun'); ?></h4> 
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <div class="modal-body pt0 pb0">

                <form id="formedit" action="<?php echo site_url('transaction/saldo_awal/edit') ?>"  name="form-area" method="post" accept-charset="utf-8"  enctype="multipart/form-data">
                    <div class="box-body">
                    
                        <div class="form-group">
                            <label for="kode"><?php echo $this->lang->line('code_akun'); ?></label><small class="req"> *</small>
                            <input readonly autofocus="" id="kode"  name="kode" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('kode'); ?></span>
                        </div>
                           
                        <div class="form-group">
                            <label for="nama"><?php echo $this->lang->line('name_akun'); ?></label><small class="req"> *</small>
                            <input readonly id="nama"  name="nama" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('nama'); ?></span>
                        </div>
                        
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_debet">Debet</label>
                                    <input autofocus="" id="jumlah_debet"  name="jumlah_debet" step="any" type="number" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('jumlah_debet'); ?></span>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="jumlah_kredit">Kredit</label>
                                    <input autofocus="" id="jumlah_kredit"  name="jumlah_kredit" step="any" type="number" class="form-control"  value="" />
                                    <span class="text-danger"><?php echo form_error('jumlah_kredit'); ?></span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="keterangan">Keterangan</label>
                            <input autofocus="" id="keterangan"  name="keterangan" placeholder="" type="text" class="form-control"  value="" />
                            <span class="text-danger"><?php echo form_error('keterangan'); ?></span>
                        </div>
                        
                    </div>
                    <div class="box-footer">
                        <input type="hidden" name="idx" id="idx" value="" />
                        <input type="hidden" name="saldo_id" id="saldo_id" value="" />
                        <input type="hidden" name="akun" id="akun" value="" />
                        <input type="hidden" name="cabang" id="cabang" value="" />
                        <button type="submit" id="btn-save-edit" class="btn btn-info pull-right"> Simpan</button>
                    </div>
                </form>

            </div><!--./col-md-12-->       
        </div><!--./row--> 
    </div>
</div>
<script>
$(document).ready(function (e) {
    var closing_saldo_awal = $('#closing_saldo_awal').val();
    
    if(closing_saldo_awal == 't'){
        $('#btnClosingSaldoAwal').attr('disabled','disabled');
        $('#btnClosingSaldoAwal').removeAttr('onclick');
        //$('#btnClosingSaldoAwal').hide();
    }else{
        $('#btnClosingSaldoAwal').removeAttr('disabled');
        $('#btnClosingSaldoAwal').attr('onclick','closingSaldoAwal()');
        //$('#btnClosingSaldoAwal').show();
    }
    
    //cek total balance
    checkTotal();
    
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
        //iDisplayLength: 15,
        paging: false,
        oLanguage: {
            sProcessing: "loading..."
        },
        dom: 'Bfrtip',
        responsive: 'true',
        processing: true,
        serverSide: true,
        ajax: {
            "url": "<?php echo site_url('transaction/saldo_awal/get_ajax') ?>", 
            "type": "POST",
            "data": function ( d ) {
                    d.cabang = $('#cabang').val();
                    d.cabangx = $('#cabangx').val();
                    d.closing_saldo_awal = closing_saldo_awal;
                }
        },
        columns: [
            {"data": "kodex"},
            {"data": "nama"},
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
                    return spaces + data.kode + ' <span style="padding-left:10px;">' + data.nama + '</span>';
                },
                width: "40%",
                orderable: false,
                searchable: false
            },
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.posisi_akun == 1){
                        return 'Debet';
                    }else if(data.posisi_akun == -1){
                        return 'Kredit';
                    }else{
                        return '';
                    }
                },
                class:"xcenter",
                orderable: false,
                searchable: false
            },
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.level == 5){
                        if(data.jumlah_debet > 0){
                            return numberFormatId(data.jumlah_debet);
                        }else{
                            return 0;
                        }
                    }else{
                        return '';
                    }
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.level == 5){
                        if(data.jumlah_kredit > 0){
                            return numberFormatId(data.jumlah_kredit);
                        }else{
                            return 0;
                        }
                    }else{
                        return '';
                    }
                },
                class:"xright",
                orderable: false,
                searchable: false
            },
            {"data": "keterangan", class:"xleft",orderable: false,searchable: true},
            {"data": "level", class:"xcenter"},
            { 
                data: null, render: function ( data, type, row ) {
                    if(data.level == 5){
                        if($('#cabang').val() == $('#cabangx').val()){
                            return data.view;
                        }else{
                            return '-';
                        }
                    }else{
                        return '';
                    }
                },
                class:"xcenter",
                orderable: false,
                searchable: false
            }
            
           // {"data": "view", width: "7%"}
        ],
        //lengthMenu: [[15, 30, 50, -1], [15, 30, 50, "All"]],
        columnDefs: [
            {
                "targets": [ 0,1 ],
                "visible": false,
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
            $('td:eq(0)', row).html();
        }

    });
    // end setup datatables
    
    
    $(document).on('change', '#cabangx', function () {
        let cabang = $('#cabang').val();
        let src_cabangx = $('#cabangx option:selected').val();
        if(src_cabangx != ''){
            oTable.api().ajax.reload();
            checkTotal();
            
            if(cabang !== src_cabangx){
                $('#btn-save-edit').attr('disabled','disabled');
                $('#btnClosingSaldoAwal').attr('disabled','disabled');
                $('#btnClosingSaldoAwal').removeAttr('onclick');
                $('#btnClosingSaldoAwal').hide();
            }else{
                $('#btn-save-edit').removeAttr('disabled');
                $('#btnClosingSaldoAwal').removeAttr('disabled');
                $('#btnClosingSaldoAwal').attr('onclick','closingSaldoAwal()');
                $('#btnClosingSaldoAwal').show();
            }

        }
    });

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
                    
                    checkTotal();
                }

            },
            error: function () {

            }
        });

    }));
    
});


function get(event) {

    let id = $(event).data("id").toString().trim();
    let cabang = $(event).data("cabang").toString().trim();
    let cabangx = $('#cabangx').val();
    
    $('#editmyModal').modal('show');

    $.ajax({

        dataType: 'json',

        url: '<?php echo base_url(); ?>transaction/saldo_awal/get_data/' + id + '/' + cabangx,

        success: function (result) {
            //alert($.trim(result.id));
            $('#cabang').val($.trim(cabangx));
            $('#idx').val($.trim(result.id));
            $('#akun').val($.trim(result.id));
            $('#saldo_id').val($.trim(result.saldo_id));
            $('#kode').val($.trim(result.kode));
            $('#nama').val($.trim(result.nama));
            
            if(result.jumlah_debet === null){
                result.jumlah_debet = 0;
            }
            if(result.jumlah_kredit === null){
                result.jumlah_kredit = 0;
            }
            
            $('#jumlah_debet').val($.trim(result.jumlah_debet));
            $('#jumlah_kredit').val($.trim(result.jumlah_kredit));
        }

    });

}
    
function closingSaldoAwal(){
    let cabang = $('#cabang').val();
    let cabangx = $('#cabangx').val();
    let periode_saldo_awal = $('#periode_saldo_awal').val();
    let total_debet = $('#total_debet').val();
    let total_kredit = $('#total_kredit').val();

    if(cabang !== cabangx){
        errorMsg('Selain Unit Kerja bersangkutan tidak boleh melakukan transaksi !');
    }else 
    if(total_debet !== total_kredit){
        errorMsg('Total Saldo Debet dan Total Saldo Kredit tidak Balance !');
    }else{
        
        Swal.fire({
          title: 'Peringatan!',
          text: 'Silahkan dicek ulang kesesuian data Saldo Awal...\nApakah anda yakin data sudah sesuai dan akan melakukan Closing Saldo Awal?',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Ya, Closing Saldo Awal !',
          cancelButtonText: 'Tidak'
        }).then((result) => {
            if (result.value) {
                $.ajax({
                    dataType: 'json',
                    url: '<?php echo base_url(); ?>transaction/saldo_awal/closing_saldo_awal/' + cabangx,
                    success: function (result) {

                        if(result.success == 'true'){
                            successMsg(result.message);
                            window.location.reload(true);

                        }else{
                            errorMsg(result.message);
                        }

                    }

                });
            }
        });

    }
 
}
    
    
function checkTotal() {
    let cabangx = $('#cabangx').val();
    $.ajax({
        dataType: 'json',
        url: '<?php echo base_url() ?>transaction/saldo_awal/checkTotal/' + cabangx,
        success: function (result) {
            
            if (typeof result.total_debet == "undefined"){
                result.total_debet = 0;
            }
               
            if (typeof result.total_kredit == "undefined"){
                result.total_kredit = 0;
            }
                
            if(result.total_debet === null){
                result.total_debet = 0;
            }
            if(result.total_kredit === null){
                result.total_kredit = 0;
            }
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


