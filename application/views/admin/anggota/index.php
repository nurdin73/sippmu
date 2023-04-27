<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>anggota">Management Anggota</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="d-flex justify-content-end mb-4">
            <a href="<?= base_url('anggota/create') ?>" class="btn btn-success btn-sm">Tambah Anggota</a>
        </div>
        <?= $this->session->flashdata('message'); ?>
        <div class="table-responsive">
            <table class="table table-striped" id="dt_table">
                <thead>
                    <tr>
                        <th>NBM</th>
                        <th>Nama</th>
                        <th>Gender</th>
                        <th>Role</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>


<script>
    $(document).ready(function() {
        getAnggota()

        $('#dt_table').on('click', '.btn-delete', function(e) {
            e.preventDefault()
            const id = $(this).data('id')
            const name = $(this).data('name');
            delete_recordById("<?= base_url("anggota/destroy/") ?>" + id, name)
        })
    });

    function getAnggota() {
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

        oTable = $("#dt_table").dataTable({
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
                "url": "<?php echo site_url('anggota/datatables') ?>",
                "type": "POST"
            },
            columns: [{
                    data: null,
                    orderable: false,
                    searchable: false,
                    render: function(data) {
                        return data.nbm ?? 'N/A';
                    }
                },
                {
                    data: "name"
                },
                {
                    data: "gender",
                },
                {
                    data: "role"
                },
                {
                    data: null,
                    render: function(data) {
                        if (data.is_active == 't' || data.is_active == 1 || data.is_active == true) {
                            return `<span class="badge badge-success badge-xs bg-success">Aktif</span>`;
                        }
                        return `<span class="badge badge-danger badge-xs bg-danger">Non Aktif</span>`;
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                        <a href="<?= base_url('anggota/edit/') ?>${data.id}" class="btn btn-xs btn-default btn-edit" data-id="${data.id}">
                            <i class="fa fa-pencil"></i>
                        </a>
                        <button class="btn btn-xs btn-danger btn-delete" data-name="${data.name}" data-id="${data.id}">
                            <i class="fa fa-trash"></i>
                        </button>
                    `
                    },
                    searchable: false,
                    orderable: false
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
                [1, 'asc']
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