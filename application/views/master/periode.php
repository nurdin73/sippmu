<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Periode</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="#">Master</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>master/periode">Master Periode</a>
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
            <button class="btn btn-primary" id="add-periode" type="button">Tambah Periode</button>
        </div>
        <div class="mailbox-controls">
        </div>
        <div class="table-responsive mailbox-messages">
            <div class="download_label">Master Periode</div>
            <table class="table table-striped" id="dt_table">
                <thead>
                    <tr>
                        <th>Judul</th>
                        <th>Periode</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<div class="modal fade" id="addPeriodeModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Tambah Periode</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/periode/create") ?>" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start">Periode Awal</label>
                                <input type="date" name="start" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end">Periode Akhir</label>
                                <input type="date" name="end" class="form-control">
                            </div>
                        </div>
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

<div class="modal fade" id="editPeriodeModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Edit Periode</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("master/periode/update") ?>" id="editForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="title">Judul</label>
                        <input type="text" name="title" class="form-control">
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="start">Periode Awal</label>
                                <input type="date" name="start" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="end">Periode Akhir</label>
                                <input type="date" name="end" class="form-control">
                            </div>
                        </div>
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
        $('#add-periode').on('click', function(e) {
            e.preventDefault()
            $('#addPeriodeModal').modal('show')
        })

        $('#dt_table').on('click', '.btn-delete', function(e) {
            e.preventDefault()
            const id = $(this).data('id')
            const nama = $(this).data('nama')
            delete_recordById('<?php echo base_url() ?>master/periode/delete/' + id, nama);
        })

        $('#dt_table').on('click', '.btn-edit', function(e) {
            e.preventDefault()
            const id = $(this).data('id')
            $.ajax({
                type: "get",
                url: "<?= base_url('master/periode/get/') ?>" + id,
                dataType: "json",
                success: function(response) {
                    $('#editForm').attr('action', "<?= base_url('master/periode/update/') ?>" +
                        id)
                    $('#editPeriodeModal').modal('show')
                    $('#editForm input[name=title]').val(response.title);
                    $('#editForm input[name=start]').val(response.start);
                    $('#editForm input[name=end]').val(response.end);
                }
            });
        })

        generateTable()
    });

    function generateTable() {
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
                "url": "<?php echo site_url('master/periode/table') ?>",
                "type": "POST"
            },
            columns: [{
                    data: "title"
                },
                {
                    data: null,
                    render: function(data) {
                        return moment(data.start).format('YYYY') + "/" + moment(data.end)
                            .format('YYYY')
                    },
                    searchable: false
                },
                {
                    data: "view",
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
    }
</script>