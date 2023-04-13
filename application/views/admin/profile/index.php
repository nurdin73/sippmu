<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>profile">Profile</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->session->flashdata('message'); ?>
        <div class="nav-tabs-custom">
            <ul class="nav nav-tabs">
                <li class="active">
                    <a href="#tree" data-toggle="tab" aria-expanded="false">
                        Tree View
                    </a>
                </li>
                <li>
                    <a href="#list" data-toggle="tab" aria-expanded="false">
                        List View
                    </a>
                </li>
            </ul>
        </div>
        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="tree">
                <?php
                if (!$periode) {
                    echo "<div class='alert alert-danger'>
                                Periode sekarang Tidak ditemukan! <a class='text-primary' href='" . base_url("master/periode") . "'>Tambahkan disini</a>
                            </div>";
                }

                ?>

                <?php
                if ($periode) {
                ?>

                    <div class="row">
                        <div class="col-md-4">
                            <input type="text" id="search-unit" placeholder="cari unit kerja" class="form-control mb-3">
                            <ul id="organizations" class="mb-0 list-unstyled">

                            </ul>
                        </div>
                        <div class="col-md-8">
                            <div class="card shadow-none invisible nav-tabs-custom">
                                <ul class="card-header nav nav-tabs" id="list-org-open" role="tablist">

                                </ul>
                                <div class="tab-content card-body" id="list-content-org-open">

                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
            </div>
            <div role="tabpanel" class="tab-pane" id="list">
                <div class="table-responsive">
                    <table class="table table-striped" id="dt_table">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Unit Kerja</th>
                                <th>Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="addHistoryJabatanModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Add Jabatan Periode <span id="periode-title"><?= date('Y', strtotime($periode['start'] ?? date('Y-m-d'))) ?> /
                        <?= date('Y', strtotime($periode['end'] ?? date('Y-m-d'))) ?></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("profile/add") ?>" id="addForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" class="form-control select2 w-100 user_id" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jabatan_id">Jabatan</label>
                        <select name="jabatan_id" class="form-control select2 w-100 jabatan" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>


                    <input type="hidden" name="periode_id" value="<?= $periode['id'] ?>">
                    <input type="hidden" name="unit_id">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-sm btn-default" type="button" data-dismiss="modal">Cancel</button>
                    <button class="btn btn-sm btn-primary" type="submit">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="editHistoryJabatanModal" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog modal-mid" role="document">
        <div class="modal-content modal-media-content">
            <div class="modal-header modal-media-header">
                <h4 class="box-title">Edit Jabatan Periode <span id="periode-title"><?= date('Y', strtotime($periode['start'] ?? date('Y-m-d'))) ?> /
                        <?= date('Y', strtotime($periode['end'] ?? date('Y-m-d'))) ?></span></h4>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>

            <form action="<?= site_url("profile/update") ?>" id="editForm" method="post">
                <div class="modal-body pt0 pb0">

                    <div class="form-group">
                        <label for="user_id">User</label>
                        <select name="user_id" class="form-control select2 w-100 user_id" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="jabatan_id">Jabatan</label>
                        <select name="jabatan_id" class="form-control select2 w-100 jabatan" style="width: 100%">
                            <option value="">Pilih</option>
                        </select>
                    </div>


                    <input type="hidden" name="periode_id" value="<?= $periode['id'] ?>">
                    <input type="hidden" name="unit_id">
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
        $('.nav-tabs').on('click', 'li a', function(e) {
            e.preventDefault()
            const tabs = $(this).parent().parent().children();
            tabs.each((i, element) => {
                element.removeAttribute('class')
            })
            $(this).parent().addClass('active')
            $(this).parent().parent().addClass('active')
        })

        getOrganizations();

        getSdm();

        let collapsed = [];
        let periodes = [];
        let marginSize = 15;
        let tabShowed = [];
        let debounceSearch;

        $.ajax({
            type: "get",
            url: "<?= base_url('master/periode/all') ?>",
            dataType: "json",
            success: function(response) {
                periodes = response.map(item => {
                    return {
                        id: item.id,
                        periode: `${moment(item.start).format('YYYY')} / ${moment(item.end).format('YYYY')}`
                    }
                })
            }
        });

        $('#search-unit').on('keyup', function(e) {
            clearTimeout(debounceSearch)
            $('#organizations').html(`
                <div class='d-flex justify-content-center'><i class='fa fa-spin fa-spinner fa-2x'></i></div>
            `)
            debounceSearch = setTimeout(() => {
                const val = e.target.value
                if (val.length >= 3) {
                    searchOrganization(val, marginSize + 10)
                } else {
                    getOrganizations()
                }
                collapsed = [];
            }, 1000);
        })

        // show child unit
        $('#organizations').on('click', 'li.org-parent button', function(e) {
            const id = $(this).data('id');
            const name = $(this).data('name');
            const find = collapsed.findIndex(collapse => collapse == id);
            if (find != -1) {
                collapsed.splice(find, 1);
                $(`li#org-parent-${id} ul`).empty();
                $(`li#org-parent-${id}`).html(`
                    <button data-name="${name}" data-id="${id}" class='btn btn-xs btn-default'><i class='feather icon-chevron-right'></i></button>
                    <span data-name="${name}" data-id="${id}">${name}</span>              
                `)
                $(`#org-tab-${id}`).remove();
                $(`#org-content-${id}`).remove();
            } else {
                // $('#orgModal').modal('show')
                collapsed.push(id);
                getOrganizations(id, name, marginSize + 10);

                // $(`#list-content-org-open #list-org-table-${id}`);
            }
        })

        // show tab
        $('#organizations').on('click', 'li.org-parent span', function(e) {
            const id = $(this).data('id')
            const name = $(this).data('name')
            const find = tabShowed.findIndex(tab => tab == id);

            if (find != -1) {
                tabShowed.splice(find, 1);
                $(`#org-tab-${id}`).remove();
                $(`#org-content-${id}`).remove();
            } else {
                tabShowed.push(id);
                $('#list-org-open').parent().removeClass('invisible')
                const start = "<?= $periode['start'] ?? '' ?>";
                const end = "<?= $periode['end'] ?? '' ?>";
                const periode_id = "<?= $periode['id'] ?>"
                $('input[name=unit_id]').val(id);
                getData(id, periode_id);
                getUser(id);
                $('#list-org-open').append(`
                    <li role="presentation" id="org-tab-${id}">
                        <a href="#org-content-${id}" class="d-flex justify-content-between align-items-center" aria-controls="org-tab-${id}" role="tab" data-toggle="tab">
                            <span>${name}</span>
                            <button type="button" data-id="${id}" class="btn btn-xs btn-default ml-2"><i class="feather icon-x"></i></button>
                        </a>
                    </li>
                `)
                $('#list-content-org-open').append(`
                <div role="tabpanel" class="tab-pane" id="org-content-${id}">
                    <div class="d-flex justify-content-between mb-4">
                        <div class="d-flex justify-content-start align-items-center">
                            <button type="button" data-periode="${periode_id}" class="prev-btn mr-1 btn btn-default rounded-circle btn-sm" data-id="${id}"><i class="feather icon-chevron-left"></i></button>
                            <input readonly name="periode" value="${moment(start).format('YYYY')}/${moment(end).format('YYYY')}" disabled class="form-control text-center align-middle display-periode w-50" />
                            <button type="button" data-periode="${periode_id}" class="next-btn ml-1 btn-default btn btn-sm rounded-circle" data-id="${id}"><i class="feather icon-chevron-right"></i></button>
                        </div>
                        <button class="btn btn-sm btn-primary btn-add-jabatan" data-id="${id}" type="button">Add</button>
                    </div>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama</th>
                                <th>Jabatan</th>
                                <th>Periode</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody id="list-org-table-${id}">
                            
                        </tbody>
                    </table>
                </div>       
            `)
            }

        })

        // next periode;
        $('#list-content-org-open').on('click', '.next-btn', function(e) {
            e.preventDefault()
            const id = $(this).data('id');
            const periode_id = e.currentTarget.dataset.periode;
            const index = periodes.findIndex(x => x.id == periode_id);
            const nextPeriod = index == (periodes.length - 1) ? periodes[0] : periodes[index + 1]
            const prevPeriod = periodes[index];
            $(this).parent().find('.display-periode').val(nextPeriod.periode);
            $(this).attr('data-periode', nextPeriod?.id)
            if ((index + 1) == (periodes.length - 1)) {
                $(this).attr('disabled', true);
                $(this).parent().find('.prev-btn').removeAttr('disabled')
            }
            $(this).parent().find('.prev-btn').attr('data-periode', prevPeriod?.id);
            $('#periode-title').text(nextPeriod.periode)
            $('input[name=periode_id]').val(nextPeriod.id)
            getData(id, nextPeriod.id)
        })

        // prev periode
        $('#list-content-org-open').on('click', '.prev-btn', function(e) {
            e.preventDefault()
            const id = $(this).data('id');
            const periode_id = e.currentTarget.dataset.periode;
            const index = periodes.findIndex(x => x.id == periode_id);
            const prevPeriod = index == (periodes.length - 1) ? periodes[0] : periodes[index + 1]
            const nextPeriod = periodes[index];
            $(this).parent().find('.display-periode').val(nextPeriod.periode);
            $(this).attr('data-periode', prevPeriod?.id)
            $(this).parent().find('.next-btn').attr('data-periode', nextPeriod?.id);
            if ((index + 1) == (periodes.length - 1)) {
                $(this).attr('disabled', true);
                $(this).parent().find('.next-btn').removeAttr('disabled')
            }
            $('#periode-title').text(nextPeriod.periode)
            $('input[name=periode_id]').val(nextPeriod.id)
            getData(id, nextPeriod.id)
        })

        // clear tab
        $('#list-org-open').on('click', 'li a button', function() {
            const id = $(this).data('id')
            $(`#org-tab-${id}`).remove();
            $(`#org-content-${id}`).remove()
        })

        // open tab
        $('#list-org-open').on('click', 'li a', function() {
            const id = $(this).data('id')
            // $(`#org-content-${id}`).addClass('active');
        })

        // add jabatan
        $('#list-content-org-open').on('click', '.btn-add-jabatan', function(e) {
            e.preventDefault()
            const id = $(this).data('id')
            getJabatan(id);
            $('#addHistoryJabatanModal').modal('show');
        })


        // edit jabatan
        $('#list-content-org-open').on('click', '.btn-edit', function(e) {
            const id = $(this).data('id')
            const unit = $(this).data('unit')
            const nama = $(this).data('nama')
            $('#editForm').attr('action', "<?= base_url('profile/update/') ?>" + id)
            $.ajax({
                type: "get",
                url: "<?= base_url('profile/get/') ?>" + id,
                dataType: "json",
                success: function(response) {
                    const sdm = new Option(response.name, response.id, true, true);
                    const jabatan = new Option(response.jabatan, response.jabatan_id, true,
                        true);
                    $('#editForm select[name=user_id]').append(sdm).trigger('change')
                    $('#editForm select[name=jabatan_id]').append(jabatan).trigger('change')
                    // if (response.is_active == 'f') {
                    //     $('#editForm input[name=status]').attr('checked', false).trigger(
                    //         'change')
                    // }

                    // if (response.is_active == 't') {
                    //     $('#editForm input[name=status]').attr('checked', true).trigger(
                    //         'change')
                    // }

                    $('#editForm input[name=periode_id]').val(response.periode_id);
                    $('#editForm input[name=unit_id]').val(unit);
                    $('#editHistoryJabatanModal').modal('show')

                    $('#editHistoryJabatanModal #periode-title').text(
                        `${moment(response.start).format('YYYY')} / ${moment(response.end).format('YYYY')}`
                    )
                }
            });
        })

        // delete jabatan
        $('#list-content-org-open').on('click', '.btn-delete', function(e) {
            const id = $(this).data('id')
            const nama = $(this).data('nama')
            delete_recordById("<?= base_url("profile/destroy/") ?>" + id, nama)
        })

        // change status SDM
        $('#dt_table').on('click', '.btn-status', function(e) {
            e.preventDefault()
            const id = $(this).data('id')
            const kode = $(this).data('kode');
            const status = $(this).data('status');
            Swal.fire({
                icon: 'warning',
                title: "Perhatian!",
                text: `Ingin mengubah status SDM ${kode}?`,
                showCancelButton: true,
                confirmButtonText: 'Ya',
                cancelButtonText: 'Tidak',
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
            }).then(result => {
                if (result.isConfirmed) {
                    $.ajax({
                        type: "post",
                        url: "<?= base_url("sdm/updateStatus/") ?>" + id,
                        data: {
                            status: (status == 't' || status == true) ? false : true
                        },
                        dataType: "json",
                        success: function(response) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message
                            }).then(() => {
                                window.location.reload()
                            })
                        },
                        error: function(err) {
                            Swal.fire({
                                icon: 'error',
                                title: 'error',
                                text: 'Gagal update status'
                            })
                        }
                    });
                }
            })
        })
    });

    function getData(id, periode_id) {
        $.ajax({
            type: "get",
            url: "<?= base_url('profile/getByPeriode/') ?>" + periode_id + '/' + id,
            dataType: "json",
            success: function(response) {
                $(`#list-content-org-open #list-org-table-${id}`).empty();
                if (response.length == 0) {
                    $(`#list-content-org-open #list-org-table-${id}`).append(`
                    <tr>
                        <td colspan="5" class="text-center">SDM periode ini tidak ditemukan</td>
                    </tr>
                `)
                }
                response.forEach((item) => {
                    $(`#list-content-org-open #list-org-table-${id}`).append(`
                        <tr>
                            <td>${item.kode}</td>
                            <td>${item.nama}</td>
                            <td>${item.jabatan}</td>
                            <td>${moment(item.start).format('YYYY')} / ${moment(item.end).format('YYYY')}</td>
                            <td>
                                <button class="btn btn-xs btn-default btn-edit" data-unit="${id}" data-id="${item.id}" data-nama="${item.nama}">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button class="btn btn-xs btn-default btn-delete" data-unit="${id}" data-id="${item.id}" data-nama="${item.nama}">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>                            
                    `)
                })
            }
        });
    }

    function getJabatan(unit_id) {
        $('.jabatan').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('master/jabatan/unit/') ?>" + unit_id,
                data: function(params) {
                    return {
                        search: params.term
                    }
                },
                processResults: function(response) {
                    return {
                        results: response.map(item => {
                            return {
                                text: item.nama,
                                id: item.id
                            }
                        })
                    }
                }
            }
        })
    }

    function getUser(unit) {
        $('.user_id').select2({
            width: '100%',
            ajax: {
                url: "<?= base_url('admin/user/search') ?>",
                data: function(params) {
                    return {
                        search: params.term,
                        unit: unit,
                    }
                },
                processResults: function(response) {
                    return {
                        results: response.map(item => {
                            return {
                                text: `${item.username} - ${item.name}`,
                                id: item.id
                            }
                        })
                    }
                }
            }
        })
    }

    function searchOrganization(search, margin) {
        let url = "<?= base_url('master/cabang/get_all_data') ?>"
        $.ajax({
            type: "get",
            url: url,
            data: {
                search,
            },
            beforeSend: function() {
                $('#organizations').html(`
                    <i class='feather icon-loader'></i>
                `)
            },
            dataType: "json",
            success: function(response) {
                $('#organizations').empty()
                if (response.length == 0) {
                    $('#organizations').html(`
                    <div class='alert alert-warning'>Unit <b>${search}</b> tidak ditemukan</div>
                `)
                }
                response.forEach(item => {
                    $('#organizations').append(`
                        <li title='(${item.kode}) ${item.nama}' style="cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" id="org-parent-${item.id}" class="org-parent">
                            ${(item.have_child == 't' || item.have_child == true) ? `<button data-name="(${item.kode}) ${item.nama}" data-id="${item.id}" class='btn btn-xs btn-default'><i class='feather icon-chevron-right'></i></button>` : ''}
                            <span class='mb-2' data-name="(${item.kode}) ${item.nama}" data-id="${item.id}">(${item.kode}) ${item.nama}</span>
                        </li>
                    `)
                })
            }
        });
    }

    function getOrganizations(parent_id = null, parent_name = null, margin = 0) {
        let url = "<?= base_url('master/cabang/parent/') ?>";
        if (parent_id) url += parent_id;
        $.ajax({
            type: "get",
            url: url,
            dataType: "json",
            success: function(response) {
                if (!parent_id) {
                    $('#organizations').empty()
                    response.forEach(item => {
                        $('#organizations').append(`
                            <li title='(${item.kode}) ${item.nama}' style="cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" id="org-parent-${item.id}" class="org-parent">
                                ${(item.have_child == 't' || item.have_child == true) ? `<button data-name="(${item.kode}) ${item.nama}" data-id="${item.id}" class='btn btn-xs btn-default'><i class='feather icon-chevron-right'></i></button>` : ''}
                                <span class='mb-2' data-name="(${item.kode}) ${item.nama}" data-id="${item.id}">(${item.kode}) ${item.nama}</span>
                            </li>
                        `)
                    })
                } else {
                    var html = `<ul class='list-unstyled' style='margin-left: ${margin}px'>`;
                    response.forEach(item => {
                        html += `<li title='(${item.kode}) ${item.nama}' style="cursor: pointer; overflow: hidden; text-overflow: ellipsis; white-space: nowrap;" id="org-parent-${item.id}" class="org-parent mb-2">
                            ${(item.have_child == 't' || item.have_child == true) ? `<button data-name="(${item.kode}) ${item.nama}" data-id="${item.id}" class='btn btn-xs mb-2 btn-default'><i class='feather icon-chevron-right'></i></button>` : ''}
                            <span class='mb-2' data-name="(${item.kode}) ${item.nama}" data-id="${item.id}">(${item.kode}) ${item.nama}</span>
                        </li>`
                    })
                    html += "</ul>";
                    $(`li#org-parent-${parent_id}`).html(`
                        <button data-name="${parent_name}" data-id="${parent_id}" class='btn btn-xs mb-2 btn-default'><i class='feather icon-chevron-down'></i></button>
                        <span class='mb-2' data-name="${parent_name}" data-id="${parent_id}">${parent_name}</span>
                        ${html}
                    `).addClass('mb-2');
                }
            }
        });
    }

    function getSdm() {
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
                    data: "kode",
                },
                {
                    data: "name"
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
                    data: null,
                    render: function(data) {
                        if (data.is_active == 't' || data.is_active == true) {
                            return 'Aktif';
                        }
                        return 'Non Aktif';
                    },
                    searchable: false,
                    orderable: false
                },
                {
                    data: null,
                    render: function(data) {
                        return `<button class='btn btn-xs btn-status ${(data.is_active == 't' || data.is_active == true) ? 'btn-danger' : 'btn-success'}' data-status="${data.is_active}" data-id="${data.id}" data-kode="${data.kode}">${(data.is_active == 't' || data.is_active == true) ? 'Non aktifkan' : 'Aktifkan'}</button>`
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
</script>