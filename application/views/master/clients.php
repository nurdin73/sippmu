<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">SDM</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>master/jabatan">Master Clients</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->session->flashdata('message'); ?>
        <div class="d-flex justify-content-end mb-4">
            <button class="btn btn-primary" id="add-client" type="button">Tambah Client</button>
        </div>
        <div class="mailbox-controls">
        </div>
        <div class="table-responsive mailbox-messages">
            <div class="download_label">Master Client</div>
            <table class="table table-striped" id="dt_table">
                <thead>
                    <tr>
                        <th>Nama Client/Aplikasi</th>
                        <th>Client ID</th>
                        <th>Client Secret</th>
                        <th>URL Aplikasi</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<div class="modal fade" id="addClientModal" role="dialog" aria-labelledby="addClientModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Tambah Client</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/clients/insert") ?>" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="client_name">Nama Client/Aplikasi</label>
                        <input type="text" name="client_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="redirect_uri">Redirect URI</label>
                        <input type="url" name="redirect_uri" placeholder="http:// or https://" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editClientModal" role="dialog" aria-labelledby="editClientModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Edit Client</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/client/update") ?>" id="editForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="client_name">Nama Client/Aplikasi</label>
                        <input type="text" name="client_name" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="redirect_uri">Redirect URI</label>
                        <input type="url" name="redirect_uri" placeholder="http:// or https://" class="form-control">
                    </div>
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    // add client
    $('#add-client').on('click', function(e) {
        e.preventDefault()
        $('#addClientModal').modal('show');
    })

    getClients();

    $('#dt_table').on('click', '.btn-edit', function(e) {
        const id = $(this).data('id')
        const url = "<?= base_url('master/clients/get/') ?>" + id
        $('#editForm').attr('action', "<?= base_url('master/clients/update/') ?>" + id)
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(response) {
                $('#editForm input[name=client_name]').val(response.client_name);
                $('#editForm input[name=redirect_uri]').val(response.redirect_uri);
                $('#editClientModal').modal('show')
            },
            error: function(err) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Client tidak ditemukan!'
                })
            }
        });
    })

    $('#dt_table').on('click', '.btn-delete', function(e) {
        const id = $(this).data('id')
        const nama = $(this).data('nama');
        delete_recordById("<?= base_url('master/clients/delete/') ?>" + id, nama)
    })
});


function getClients() {
    $.fn.dataTableExt.oApi.fnPagingInfo = function(oSettings) {
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

    let oTable = $("#dt_table").dataTable({
        initComplete: function() {
            var api = this.api();
            $('#dt_table_filter input')
                .off('.DT')
                .on('input.DT', function() {
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
            "url": "<?php echo site_url('master/clients/datatable') ?>",
            "type": "POST"
        },
        columns: [{
                data: "nama",
            },
            {
                data: "id"
            },
            {
                data: "client_secret",
                orderable: false,
            },
            {
                data: "redirect_uri",
                orderable: false,
            }, {
                data: "view",
                orderable: false,
                searchable: false
            }
        ],
        lengthMenu: [
            [15, 30, 50, -1],
            [15, 30, 50, "All"]
        ],

        columnDefs: [{

                "targets": [-1], //last column
                "orderable": false, //set not orderable
            },
            {
                "targets": [-1],
                className: "text-right"
            }
        ],
        buttons: [{
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
                customize: function(win) {
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
        order: [
            [1, 'desc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
        }

    });
}
</script>