<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Tambah Asset</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                class="feather icon-home"></i></a></li>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>asset">Management Asset</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>asset/edit/2">
                            Tambah Asset</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->session->flashdata('message') ?>
        <form action="<?= base_url('asset/insert') ?>" method="post" enctype="multipart/form-data">
            <div class="form-group">
                <label for="unit_id">Unit <sup class="text-danger">*</sup></label>
                <select name="unit_id" id="unit_id" class="form-control">
                    <option value="">Pilih</option>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="tipe_aset">Tipe Aset <sup class="text-danger">*</sup></label>
                        <select name="tipe_aset" id="tipe_aset" class="form-control">
                            <option value="">Pilih</option>
                            <?php
                            foreach ($tipe_aset as $ta) {
                                echo "<option value='$ta[name]'>$ta[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="sertifikat">Sertifikat (maksimal 2MB) <sup class="text-danger">*</sup></label>
                        <input type="file" name="sertifikat" accept=".pdf" id="sertifikat">
                    </div>
                </div>
            </div>
            <div class="form-dynamic ">
                <div class="row">
                    <!-- <div class="col-md-6" id="form_luas">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="luas_tanah">Luas tanah (m<sup>2</sup>) <sup class="text-danger">*</sup></label>
                                    <input type="number" name="luas_tanah" value="<?= set_value('luas_tanah') ?>" id="luas_tanah" class="form-control">
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="luas_bangunan">Luas bangunan (m<sup>2</sup>) <sup class="text-danger">*</sup></label>
                                    <input type="number" name="luas_bangunan" value="<?= set_value('luas_bangunan') ?>" id="luas_bangunan" class="form-control">
                                </div>
                            </div>
                        </div>
                    </div> -->
                    <div class="col-md-12">
                        <div class="form-group">
                            <label for="status_tanah">Status Tanah</label>
                            <select name="status_tanah" id="status_tanah" class="form-control">
                                <option value="">Pilih</option>
                                <?php
                                foreach ($status_tanah as $st) {
                                    echo "<option value='$st[name]'>$st[name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="jenis">Jenis <sup class="text-danger">*</sup></label>
                    <select name="jenis" id="jenis" class="form-control">
                        <option value="">Pilih</option>
                        <?php
                        foreach ($jenis as $j) {
                            echo "<option value='$j[name]'>$j[name]</option>";
                        }
                        ?>
                    </select>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="perolehan">Perolehan</label>
                            <select name="perolehan" id="perolehan" class="form-control">
                                <option value="">Pilih</option>
                                <?php
                                foreach ($perolehan as $p) {
                                    echo "<option value='$p[name]'>$p[name]</option>";
                                }
                                ?>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="wakif_perolehan">Wakif Perolehan</label>
                            <input type="text" value="<?= set_value('wakif_perolehan') ?>" name="wakif_perolehan"
                                id="wakif_perolehan" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="form-group">
                    <label for="legalitas_bhn">Legalitas Bukti Hak Nomor</label>
                    <input type="text" name="legalitas_bhn" value="<?= set_value('legalitas_bhn') ?>" id="legalitas_bhn"
                        class="form-control">
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pendayagunaan">Pendayagunaan</label>
                            <input type="text" value="<?= set_value('pendayagunaan') ?>" id="pendayagunaan"
                                name="pendayagunaan" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="pengelola">Pengelola</label>
                            <select name="pengelola" id="pengelola" class="form-control">
                                <option value="">Pilih</option>
                            </select>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_njop">Nilai Tanah NJOP</label>
                            <input type="number" name="nilai_njop" id="nilai_njop"
                                value="<?= set_value('nilai_njop') ?>" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="nilai_bangunan">Nilai Bangunan</label>
                            <input type="number" value="<?= set_value('nilai_bangunan') ?>" id="nilai_bangunan"
                                name="nilai_bangunan" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-10">
                        <div class="form-group">
                            <label for="jml_lokal">Jumlah Lokal</label>
                            <input type="number" name="jml_lokal" id="jml_lokal" class="form-control">
                        </div>
                    </div>
                    <div class="col-md-2">
                        <div class="form-group">
                            <label for="is_pusat">Kantor Pusat</label><br>
                            <input type="checkbox" value="true" name="is_pusat" id="is_pusat">
                            Ya
                        </div>
                    </div>
                </div>
            </div>
            <div class="form-group" id="map" style="height: 500px;"></div>
            <div class="form-group">
                <label for="coord">Koordinat <sup class="text-danger">*</sup></label>
                <input type="text" name="coord" value="<?= set_value('coord') ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat <sup class="text-danger">*</sup></label>
                <textarea name="alamat" cols="30" rows="3"
                    class="form-control">value="<?= set_value('alamat') ?>"</textarea>
            </div>
            <button class="btn btn-primary" style="width: 150px">Simpan</button>
            <a href="<?= base_url('asset') ?>" class="btn btn-default">Kembali</a>
        </form>
    </div>
</div>
<link rel="stylesheet" href="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.css" />
<script src="https://unpkg.com/leaflet-control-geocoder/dist/Control.Geocoder.js"></script>
<script src="<?= base_url('assets/js/map.js') ?>"></script>

<script>
$(document).ready(function() {
    generateMap("map", [-6.9024981, 107.6187750112255]);
    getUnit();
    getPengelola();
    dynamicForm();
});

function dynamicForm() {
    // hide all form
    const form_tanah = ['status_tanah', 'jenis', 'perolehan', 'wakif_perolehan',
        'legalitas_bhn', 'pendayagunaan', 'pengelola', 'nilai_njop', 'nilai_bangunan'
    ];
    const form_bangunan = ['perolehan', 'jml_lokal', 'pendayagunaan', 'is_pusat'];
    [...form_tanah, ...form_bangunan].forEach(val => {
        $(`#${val}`).parent().hide();
    })

    $('#tipe_aset').on('change', function(e) {
        /**
         * TANAH
         * - luas tanah
         * - status tanah
         * - jenis
         * - perolehan
         * - wakif perolehan
         * - legalitas bhn
         * - pendayagunaan
         * - pengelola
         * - nilai njop
         * - nilai bangunan
         * BANGUNAN
         * - luas tanah
         * - perolehan
         * - luas bangunan
         * - jml lokal
         */
        const val = e.target.value
        if (val == 'TANAH') {
            [...form_tanah, ...form_bangunan].forEach(val => {
                $(`#${val}`).parent().hide();
            })
            form_tanah.forEach(el => {
                // if (el == 'luas_tanah') {
                //     $(`#${el}`).parent().parent().removeClass('col-md-6').addClass('col-md-12')
                // }
                if (el == 'perolehan') {
                    $(`#${el}`).parent().parent().removeClass('col-md-12').addClass('col-md-6')
                }
                if (el == 'pendayagunaan') {
                    $(`#${el}`).parent().parent().removeClass('col-md-12').addClass('col-md-6')
                }
                $(`#${el}`).parent().show();
            })

            $('#form_luas').removeClass('col-md-12').addClass('col-md-6')
        } else if (val == 'GEDUNG') {
            [...form_tanah, ...form_bangunan].forEach(val => {
                $(`#${val}`).parent().hide();
            })
            form_bangunan.forEach(el => {
                // if (el == 'luas_tanah') {
                //     $(`#${el}`).parent().parent().removeClass('col-md-12').addClass('col-md-6')
                // }
                // if (el == 'luas_bangunan') {
                //     $(`#${el}`).parent().parent().removeClass('col-md-12').addClass('col-md-6')
                // }

                if (el == 'perolehan') {
                    $(`#${el}`).parent().parent().removeClass('col-md-6').addClass('col-md-12')
                }
                if (el == 'pendayagunaan') {
                    $(`#${el}`).parent().parent().removeClass('col-md-6').addClass('col-md-12')
                }
                $(`#${el}`).parent().show();
            })

            $('#form_luas').removeClass('col-md-6').addClass('col-md-12')
        } else {
            [...form_tanah, ...form_bangunan].forEach(val => {
                $(`#${val}`).parent().hide();
            })
            $('#form_luas').removeClass('col-md-6').addClass('col-md-12')
        }
    })
}

function getPengelola() {
    $('#pengelola').select2({
        width: '100%',
        tags: true,
        ajax: {
            url: "<?= base_url('master/cabang/get_all_data') ?>",
            data: function(params) {
                return {
                    search: params.term
                }
            },
            processResults: function(response) {
                response = JSON.parse(response);
                return {
                    results: response.map(item => {
                        return {
                            text: `(${item.kode}) - ${item.nama}`,
                            id: item.nama
                        }
                    })
                }
            }
        }
    })
}

function getUnit() {
    $('#unit_id').select2({
        width: '100%',
        ajax: {
            url: "<?= base_url('master/cabang/get_all_data') ?>",
            data: function(params) {
                return {
                    search: params.term
                }
            },
            processResults: function(response) {
                response = JSON.parse(response);
                return {
                    results: response.map(item => {
                        return {
                            text: `(${item.kode}) - ${item.nama}`,
                            id: item.id
                        }
                    })
                }
            }
        }
    })
}
</script>