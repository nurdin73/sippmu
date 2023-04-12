<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">SDM</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>master/jabatan">Master Jabatan</a>
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
            <button class="btn btn-primary" id="add-jabatan" type="button">Tambah Jabatan</button>
        </div>
        <div class="mailbox-controls">
        </div>
        <div class="table-responsive mailbox-messages">
            <div class="download_label">Master Jabatan</div>
            <table class="table table-striped" id="dt_table">
                <thead>
                    <tr>
                        <th>Kode</th>
                        <th>Nama</th>
                        <th>Unit Kerja</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addJabatanModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Tambah Jabatan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/jabatan/insert") ?>" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="unit_kerja">Unit Kerja</label>
                        <select name="unit_kerja" class="form-control select2 w-100 unit_kerja" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select>
                    </div> -->
                    <div class="d-flex align-items-center">
                        <label for="status" class="mb-0 mr-2">Is Active</label>
                        <input type="checkbox" value="true" name="status" checked>
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

<div class="modal fade" id="editJabatanModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Edit Jabatan</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/jabatan/update") ?>" id="editForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="unit_kerja">Unit Kerja</label>
                        <select name="unit_kerja" class="form-control select2 w-100 unit_kerja" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select>
                    </div> -->
                    <div class="d-flex align-items-center">
                        <label for="status" class="mb-0 mr-2">Is Active</label>
                        <input type="checkbox" value="true" name="status">
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

        // add jabatan
        $('#add-jabatan').on('click', function() {
            $('#addJabatanModal').modal('show')
        })

        // get unit kerja
        $('.unit_kerja').select2({
            width: '100%',
            ajax: {
                url: "<?= site_url('master/cabang/get_all_data') ?>",
                dataType: 'json',
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response.map(r => {
                            return {
                                text: `${r.kode}-${r.nama}`,
                                id: r.id
                            }
                        })
                    }
                }
            }
        })

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
                "url": "<?php echo site_url('master/jabatan/data') ?>",
                "type": "POST"
            },
            columns: [{
                    data: null,
                    render: function(data) {
                        if (data.kode) {
                            return data.kode;
                        }
                        return 'N/A'
                    }
                },
                {
                    data: "nama"
                },
                {
                    data: "unit_kerja"
                },
                {
                    data: null,
                    render: function(data, type, row) {
                        if (data.is_active == 't') {
                            return '<span class="badge badge-success">aktif</span>';
                        }
                        return '<span class="badge badge-danger">non aktif</span>';
                    },
                    width: '7%',
                },
                {
                    data: "view"
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
                [0, 'asc']
            ],
            rowCallback: function(row, data, iDisplayIndex) {
                var info = this.fnPagingInfo();
                var page = info.iPage;
                var length = info.iLength;
                $('td:eq(0)', row).html();
            }

        });


        // edit
        $('#dt_table').on('click', '.btn-edit', function(e) {
            e.preventDefault()
            const id = $(this).data('id');
            $('#editForm').attr('action', "<?= site_url('master/jabatan/update/') ?>" + id)
            $.ajax({
                type: "get",
                url: "<?= base_url('master/jabatan/get/') ?>" + id,
                dataType: "json",
                success: function(response) {
                    $('#editJabatanModal').modal('show')
                    $('#editForm input[name=nama]').val(response.nama);
                    const option = new Option(response.unit_kerja, response.unit_id, true,
                        true);
                    $('#editForm select[name=unit_kerja]').append(option).trigger('change');
                    if (response.status == 'f') {
                        $('#editForm input[name=status]').attr('checked', false).trigger(
                            'change')
                    }

                    if (response.status == 't') {
                        $('#editForm input[name=status]').attr('checked', true).trigger(
                            'change')
                    }
                }
            });
        })

        $('#dt_table').on('click', '.btn-delete', function(e) {
            e.preventDefault()
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            delete_recordById('<?php echo base_url() ?>master/jabatan/destroy/' + id, nama);
        })

    });
</script>