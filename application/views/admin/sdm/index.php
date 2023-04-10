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
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>sdm">SDM</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->session->flashdata('message'); ?>
        <div class="d-flex justify-content-end align-items-center mb-3">
            <!-- <div class="w-25">
                <form action="" method="get">
                    <div class="input-group">
                        <input type="text" class="form-control">
                        <div class="input-group-btn">
                            <button type="submit" class="btn btn-default"><i class="feather icon-search"></i></button>
                        </div>
                    </div>
                </form>
            </div> -->
            <button class="btn btn-sm btn-info" onclick="getAdd()">Add</button>
        </div>
        <div class="mailbox-controls">
        </div>
        <div class="table-responsive mailbox-messages">
            <div class="download_label">Master SDM</div>
            <table class="table table-striped" id="dt_table">
                <thead>
                    <tr>
                        <th>NBM</th>
                        <th>Nama</th>
                        <th>Jabatan</th>
                        <th>Unit Kerja</th>
                        <th>Status TTD</th>
                        <th>Action</th>
                    </tr>
                </thead>
            </table>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="addSdmModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Tambah SDM</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("sdm/insert") ?>" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nbm">Nomor Baku Muhammadiyah (NBM)</label>
                        <input type="number" name="nbm" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="unit_kerja">Unit Kerja</label>
                        <select name="unit_kerja" class="form-control select2 w-100 unit_kerja" style="witdh: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan" class="form-control select2 w-100 jabatan" style="witdh: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div> -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="number" name="telepon" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hp">HP</label>
                                <input type="text" name="hp" class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ttd">Status Tanda Tangan</label>
                        <div class="d-flex flex-column">
                            <label class="d-flex align-items-center" for="anggota">
                                <input type="radio" class="mr-2" name="ttd" value="anggota">
                                <span>Anggota</span>
                            </label>
                            <label class="d-flex align-items-center" for="menyusun">
                                <input type="radio" class="mr-2" name="ttd" value="menyusun">
                                <span>Menyusun</span>
                            </label>
                            <label class="d-flex align-items-center" for="mengetahui">
                                <input type="radio" class="mr-2" name="ttd" value="mengetahui">
                                <span>Mengetahui</span>
                            </label>
                            <label class="d-flex align-items-center" for="mengesahkan">
                                <input type="radio" class="mr-2" name="ttd" value="mengesahkan">
                                <span>Mengesahkan</span>
                            </label>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select>
                    </div> -->
                    <div class="d-flex align-items-center">
                        <!-- <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select> -->
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

<div class="modal fade" id="editSdmModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title"> Edit SDM</h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("sdm/update") ?>" id="editForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="nama">Nama</label>
                        <input type="text" name="nama" class="form-control">
                    </div>

                    <div class="form-group">
                        <label for="nbm">Nomor Baku Muhammadiyah (NBM)</label>
                        <input type="number" name="nbm" class="form-control">
                    </div>


                    <div class="form-group">
                        <label for="unit_kerja">Unit Kerja</label>
                        <select name="unit_kerja" class="form-control select2 w-100 unit_kerja" style="witdh: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <!-- <div class="form-group">
                        <label for="jabatan">Jabatan</label>
                        <select name="jabatan" class="form-control select2 w-100 jabatan" style="witdh: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div> -->

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tempat_lahir">Tempat Lahir</label>
                                <input type="text" name="tempat_lahir" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tanggal_lahir">Tanggal Lahir</label>
                                <input type="date" name="tanggal_lahir" class="form-control">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="telepon">Telepon</label>
                                <input type="number" name="telepon" class="form-control">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="hp">HP</label>
                                <input type="text" name="hp" class="form-control">
                            </div>
                        </div>
                    </div>



                    <div class="form-group">
                        <label for="alamat">Alamat</label>
                        <textarea name="alamat" id="alamat" cols="30" rows="3" class="form-control"></textarea>
                    </div>

                    <div class="form-group">
                        <label for="ttd">Status Tanda Tangan</label>
                        <div class="d-flex flex-column">
                            <label class="d-flex align-items-center" for="anggota">
                                <input type="radio" class="mr-2" name="ttd" id="anggota" value="anggota">
                                <span>Anggota</span>
                            </label>
                            <label class="d-flex align-items-center" for="menyusun">
                                <input type="radio" class="mr-2" name="ttd" id="menyusun" value="menyusun">
                                <span>Menyusun</span>
                            </label>
                            <label class="d-flex align-items-center" for="mengetahui">
                                <input type="radio" class="mr-2" name="ttd" id="mengetahui" value="mengetahui">
                                <span>Mengetahui</span>
                            </label>
                            <label class="d-flex align-items-center" for="mengesahkan">
                                <input type="radio" class="mr-2" name="ttd" id="mengesahkan" value="mengesahkan">
                                <span>Mengesahkan</span>
                            </label>
                        </div>
                    </div>

                    <!-- <div class="form-group">
                        <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select>
                    </div> -->
                    <div class="d-flex align-items-center">
                        <!-- <label for="status">Status</label>
                        <select name="status" class="form-control">
                            <option value="true">Aktif</option>
                            <option value="false">Non aktif</option>
                        </select> -->
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
let oTable;

function getAdd() {
    $('#addSdmModal').modal('show');
}

$(document).ready(function() {
    getCabang()

    getData()

    // getJabatan();
    $('.jabatan').attr('disabled', true);
    $('.unit_kerja').on('select2:select', function(e) {
        const val = e.target.value;
        $('.jabatan').val('').trigger('change');
        // getJabatan(val);
        $('.jabatan').attr('disabled', false);
    })
});

function getJabatan(unit = null) {
    $('.jabatan').select2({
        width: '100%',
        ajax: {
            url: "<?= site_url('master/jabatan/ajax') ?>",
            dataType: 'json',
            data: function(params) {
                return {
                    search: params.term,
                    unit: unit
                }
            },
            processResults: function(response) {
                return {
                    results: response.map(r => {
                        return {
                            text: r.nama,
                            id: r.id
                        }
                    })
                }
            }
        }
    })
}

function getCabang() {

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

}


function getData() {
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
            "url": "<?php echo site_url('sdm/datatable') ?>",
            "type": "POST"
        },
        columns: [{
                data: "nbm"
            },
            {
                data: "nama"
            },
            {
                data: null,
                render: function(data) {
                    if (data.jabatan) {
                        return data.jabatan;
                    }
                    return 'N/A'
                },
                searchable: false
            },
            {
                data: "unit_kerja"
            },
            {
                data: "ttd"
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
            [0, 'desc']
        ],
        rowCallback: function(row, data, iDisplayIndex) {
            var info = this.fnPagingInfo();
            var page = info.iPage;
            var length = info.iLength;
            $('td:eq(0)', row).html();
        }

    });
}

function get(event) {
    let id = $(event).data("id").toString().trim();
    $('#editForm').attr('action', "<?= site_url('sdm/update/') ?>" + id)
    $.ajax({
        type: "get",
        url: "<?= site_url('sdm/get/') ?>" + id,
        data: "data",
        dataType: "json",
        success: function(response) {
            $('#editForm input[name=nama]').val(response.sdm_nama)
            $('#editForm input[name=nbm]').val(response.sdm_nbm)
            $('#editForm input[name=jabatan]').val(response.sdm_jabatan)
            $('#editForm input[name=tempat_lahir]').val(response.sdm_tmp_lahir)
            $('#editForm input[name=tanggal_lahir]').val(response.sdm_tgl_lahir)
            $('#editForm input[name=telepon]').val(response.sdm_phone)
            $('#editForm input[name=hp]').val(response.sdm_hp)
            $('#editForm textarea[name=alamat]').val(response.sdm_alamat)
            // $('#editForm select[name=unit_kerja]').val(response.uk_id).trigger('change')
            const option = new Option(response.unit_kerja, response.unit_id, true, true);
            $('#editForm select[name=unit_kerja]').append(option).trigger('change')
            const option2 = new Option(response.jabatan, response.jabatan_id, true, true);
            $('#editForm select[name=jabatan]').append(option2).trigger('change').attr('disabled', false)
            if (response.status == 'f') {
                $('#editForm input[name=status]').attr('checked', false).trigger('change')
            }

            if (response.status == 't') {
                $('#editForm input[name=status]').attr('checked', true).trigger('change')
            }
            // $('#editForm input[name=ttd]').val(response.sdm_status_ttd).trigger('checked')
            $(`#editForm #${response.sdm_status_ttd}`).attr('checked', true)
            $('#editSdmModal').modal('show')
        }
    });

}

function deleterecord(event) {
    let id = $(event).data("id").toString().trim();
    let nama = $(event).data("nama").toString().trim();
    delete_recordById('<?php echo base_url() ?>sdm/destroy/' + id, nama);
}
</script>