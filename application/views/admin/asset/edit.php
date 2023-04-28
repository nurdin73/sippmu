<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Edit Assets</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                class="feather icon-home"></i></a></li>
                    </li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>asset">Management Asset</a>
                    </li>
                    <li class="breadcrumb-item active"><a href="<?php echo base_url(); ?>asset/edit/2">
                            Asset</a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <?= $this->session->flashdata('message') ?>
        <form action="<?= base_url('asset/update/') . $asset['id'] ?>" method="post">
            <div class="form-group">
                <label for="unit_id">Unit <sup class="text-danger">*</sup></label>
                <select name="unit_id" id="unit_id" class="form-control">
                    <option value="">Pilih</option>
                </select>
            </div>
            <div class="form-group">
                <label for="tipe_aset">Tipe Aset <sup class="text-danger">*</sup></label>
                <select name="tipe_aset" id="tipe_aset" class="form-control">
                    <option value="">Pilih</option>
                    <?php
                    foreach ($tipe_aset as $ta) {
                        $selected = '';
                        if ($ta['name'] == $asset['tipe_aset']) {
                            $selected = "selected";
                        } else {
                            $selected = '';
                        }
                        echo "<option value='$ta[name]' $selected>$ta[name]</option>";
                    }
                    ?>
                </select>
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="luas_tanah">Luas tanah (m<sup>2</sup>) <sup class="text-danger">*</sup></label>
                        <input type="number" name="luas_tanah" value="<?= $asset['luas_tanah'] ?>" id="luas_tanah"
                            class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="status_tanah">Status Tanah <sup class="text-danger">*</sup></label>
                        <select name="status_tanah" id="status_tanah" class="form-control">
                            <option value="">Pilih</option>
                            <?php
                            foreach ($status_tanah as $st) {
                                $selected = '';
                                if ($st['name'] == $asset['status_tanah']) {
                                    $selected = "selected";
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='$st[name]' $selected>$st[name]</option>";
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
                        $selected = '';
                        if ($j['name'] == $asset['jenis']) {
                            $selected = "selected";
                        } else {
                            $selected = '';
                        }
                        echo "<option value='$j[name]' $selected>$j[name]</option>";
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
                                $selected = '';
                                if ($p['name'] == $asset['perolehan']) {
                                    $selected = "selected";
                                } else {
                                    $selected = '';
                                }
                                echo "<option value='$p[name]' $selected>$p[name]</option>";
                            }
                            ?>
                        </select>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="wakif_perolehan">Wakif Perolehan</label>
                        <input type="text" value="<?= $asset['wakif_perolehan'] ?>" name="wakif_perolehan"
                            id="wakif_perolehan" class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group">
                <label for="legalitas_bhn">Legalitas Bukti Hak Nomor</label>
                <input type="text" name="legalitas_bhn" value="<?= $asset['legalitas_bhn'] ?>" id="legalitas_bhn"
                    class="form-control">
            </div>
            <div class="row">
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="pendayagunaan">Pendayagunaan</label>
                        <input type="text" value="<?= $asset['pendayagunaan'] ?>" name="pendayagunaan"
                            class="form-control">
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
                        <input type="number" name="nilai_njop" value="<?= $asset['nilai_njop'] ?>" class="form-control">
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="form-group">
                        <label for="nilai_bangunan">Nilai Bangunan</label>
                        <input type="number" value="<?= $asset['nilai_bangunan'] ?>" name="nilai_bangunan"
                            class="form-control">
                    </div>
                </div>
            </div>
            <div class="form-group" id="map" style="height: 500px;"></div>
            <div class="form-group">
                <label for="coord">Koordinat <sup class="text-danger">*</sup></label>
                <input type="text" name="coord" value="<?= $asset['coord'] ?>" class="form-control" readonly>
            </div>
            <div class="form-group">
                <label for="alamat">Alamat <sup class="text-danger">*</sup></label>
                <textarea name="alamat" cols="30" rows="3" class="form-control"><?= $asset['alamat'] ?></textarea>
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
    const loc = ("<?= $asset['coord'] ?>").split(', ')
    generateMap("map", loc);
    getUnit();
    getPengelola();
});

function getPengelola() {
    const option = new Option("<?= $asset['pengelola'] ?>", "<?= $asset['pengelola'] ?>", true, true)
    $('#pengelola').append(option).trigger('change')
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
    const option = new Option("<?= $asset['unit'] ?>", "<?= $asset['unit_id'] ?>", true, true)
    $('#unit_id').append(option).trigger('change')
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