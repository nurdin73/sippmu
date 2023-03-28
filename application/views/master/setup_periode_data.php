<?php
$currency = $data_setup['currency'] ? $data_setup['currency'] : 'IDR';
     
if($data_setup['bln_aktif_sebelumnya']){
    $bln_aktif_sebelumnya = explode('/', $data_setup['bln_aktif_sebelumnya']);
    $result["bln_aktif_sebelumnyax"] = $bln_aktif_sebelumnya[0];
    $result["thn_aktif_sebelumnyax"] = $bln_aktif_sebelumnya[1];
}else{
    $result["bln_aktif_sebelumnyax"] = '';
    $result["thn_aktif_sebelumnyax"] = '';
}

if($data_setup['bln_aktif_saatini']){
    $bln_aktif_saatini = explode('/', $data_setup['bln_aktif_saatini']);
    $result["bln_aktif_saatinix"] = $bln_aktif_saatini[0];
    $result["thn_aktif_saatinix"] = $bln_aktif_saatini[1];
}else{
     $result["bln_aktif_saatinix"] = '';
    $result["thn_aktif_saatinix"] = '';
}

if($data_setup['bln_aktif_akandatang']){
    $bln_aktif_akandatang = explode('/', $data_setup['bln_aktif_akandatang']);
    $result["bln_aktif_akandatangx"] = $bln_aktif_akandatang[0];
    $result["thn_aktif_akandatangx"] = $bln_aktif_akandatang[1];
}else{
    $result["bln_aktif_akandatangx"] = '';
    $result["thn_aktif_akandatangx"] = '';
}

if($data_setup['closing_saldo_awal'] == 't'){
    $is_disabled_saldo_awal = 'disabled="disabled"';
    //$is_disabled_saldo_awal = 'readonly';
}else{
    $is_disabled_saldo_awal = '';
}

if($data_setup['bulan_berjalan']){
    $periode_saldo_awal = explode('/', $data_setup['periode_saldo_awal']);
    $result["bln_periode_saldo_awalx"] = $periode_saldo_awal[0];
    $result["thn_periode_saldo_awalx"] = $periode_saldo_awal[1];
}else{
    $result["bln_periode_saldo_awalx"] = '';
    $result["thn_periode_saldo_awalx"] = '';
}


if($data_setup['bulan_berjalan']){
    $bulan_berjalan = explode('/', $data_setup['bulan_berjalan']);
    $result["bln_bulan_berjalanx"] = $bulan_berjalan[0];
    $result["thn_bulan_berjalanx"] = $bulan_berjalan[1];
}else{
    $result["bln_bulan_berjalanx"] = '';
    $result["thn_bulan_berjalanx"] = '';
}

?>
                                
                                <div class="form-group row">
                                    <label for="cabangx" class="col-sm-3 col-form-label">Mata Uang<small class="req"> *</small></label>
                                    <div class="col-sm-5">
                                        <select id="currency" name="currency" class="form-control" >
                                            <option value=""> - Pilih -</option>
                                            <?php
                                            foreach ($cbx_currency as $key => $row) {
                                                ?>
                                                <option value="<?php echo $row['kode'] ?>" <?php if (isset($currency) && $currency == $row['kode']) { echo 'selected'; } ?> > <?php echo $row["kode"] ?> - <?php echo $row["deskripsi"] ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('currency'); ?></span>
                                    </div>
                                </div>

                                <div class="form-group row">
                                    <label for="bln_periode_saldo_awalx" class="col-sm-3 col-form-label">Periode Saldo Awal<small class="req"> *</small></label>
                                    <div class="col-sm-3">
                                        <select <?php echo $is_disabled_saldo_awal;?> id="bln_periode_saldo_awalx" name="bln_periode_saldo_awalx" class="form-control" >
                                            <option value="">- Pilih -</option>
                                            <?php
                                            foreach ($monthList as $key => $month) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_periode_saldo_awalx"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bln_periode_saldo_awalx'); ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input <?php echo $is_disabled_saldo_awal;?> id="thn_periode_saldo_awalx" name="thn_periode_saldo_awalx" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["thn_periode_saldo_awalx"];
                                        }
                                        ?>" />
                                        <span class="text-danger"><?php echo form_error('thn_periode_saldo_awalx'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bln_aktif_sebelumnyax" class="col-sm-3 col-form-label">Bulan Aktif Sebelumnya<small class="req"> *</small></label>
                                    <div class="col-sm-3">
                                        <select  id="bln_aktif_sebelumnyax" name="bln_aktif_sebelumnyax" class="form-control" >
                                            <option value="">- Pilih -</option>
                                            <?php
                                            foreach ($monthList as $key => $month) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_aktif_sebelumnyax"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bln_aktif_sebelumnyax'); ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input id="thn_aktif_sebelumnyax" name="thn_aktif_sebelumnyax" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["thn_aktif_sebelumnyax"];
                                        }
                                        ?>" />
                                        <span class="text-danger"><?php echo form_error('thn_aktif_sebelumnyax'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bln_aktif_saatinix" class="col-sm-3 col-form-label">Bulan Aktif Saat Ini<small class="req"> *</small></label>
                                    <div class="col-sm-3">
                                        <select  id="bln_aktif_saatinix" name="bln_aktif_saatinix" class="form-control" >
                                            <option value="">- Pilih -</option>
                                            <?php
                                            foreach ($monthList as $key => $month) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_aktif_saatinix"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bln_aktif_saatinix'); ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input id="thn_aktif_saatinix" name="thn_aktif_saatinix" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["thn_aktif_saatinix"];
                                        }
                                        ?>" />
                                        <span class="text-danger"><?php echo form_error('thn_aktif_saatinix'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bln_aktif_akandatangx" class="col-sm-3 col-form-label">Bulan Aktif Akan Datang<small class="req"> *</small></label>
                                    <div class="col-sm-3">
                                        <select  id="bln_aktif_akandatangx" name="bln_aktif_akandatangx" class="form-control" >
                                            <option value="">- Pilih -</option>
                                            <?php
                                            foreach ($monthList as $key => $month) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_aktif_akandatangx"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bln_aktif_akandatangx'); ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input id="thn_aktif_akandatangx" name="thn_aktif_akandatangx" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["thn_aktif_akandatangx"];
                                        }
                                        ?>" />
                                        <span class="text-danger"><?php echo form_error('thn_aktif_akandatangx'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="bln_bulan_berjalanx" class="col-sm-3 col-form-label">Bulan Berjalan<small class="req"> *</small></label>
                                    <div class="col-sm-3">
                                        <select  id="bln_bulan_berjalanx" name="bln_bulan_berjalanx" class="form-control" >
                                            <option value="">- Pilih -</option>
                                            <?php
                                            foreach ($monthList as $key => $month) {
                                                ?>
                                                <option value="<?php echo $key ?>" <?php if (isset($result) && $result["bln_bulan_berjalanx"] == $key) { echo 'selected'; } ?>> <?php echo $month ?></option>
                                            <?php }
                                            ?>
                                        </select>
                                        <span class="text-danger"><?php echo form_error('bln_bulan_berjalanx'); ?></span>
                                    </div>
                                    <div class="col-sm-2">
                                        <input id="thn_bulan_berjalanx" name="thn_bulan_berjalanx" placeholder="Tahun" type="text" class="form-control"  value="<?php
                                        if (isset($result)) {
                                            echo $result["thn_bulan_berjalanx"];
                                        }
                                        ?>" />
                                        <span class="text-danger"><?php echo form_error('thn_bulan_berjalanx'); ?></span>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tutup_bulan_lap" class="col-sm-3 col-form-label">Tutup Bulan </label>
                                    <div class="col-sm-3">
                                        <select id="tutup_bulan_lap" name="tutup_bulan_lap" class="form-control" >
                                            <option value="0" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lap"] == 'f') { echo 'selected'; } ?>> Tidak</option>
                                            <option value="1" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lap"] == 't') { echo 'selected'; } ?>> Ya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tutup_periode_saldoawal" class="col-sm-3 col-form-label">Tutup Periode Saldo Awal </label>
                                    <div class="col-sm-3">
                                        <select <?php echo $is_disabled_saldo_awal;?> id="tutup_periode_saldoawal" name="tutup_periode_saldoawal" class="form-control" >
                                            <option value="0" <?php if (isset($data_setup) && $data_setup["tutup_periode_saldoawal"] == 'f') { echo 'selected'; } ?>> Tidak</option>
                                            <option value="1" <?php if (isset($data_setup) && $data_setup["tutup_periode_saldoawal"] == 't') { echo 'selected'; } ?>> Ya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tutup_bulan_lapinterim" class="col-sm-3 col-form-label">Tutup AJP Interim </label>
                                    <div class="col-sm-3">
                                        <select id="tutup_bulan_lapinterim" name="tutup_bulan_lapinterim" class="form-control" >
                                            <option value="0" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lapinterim"] == 'f') { echo 'selected'; } ?>> Tidak</option>
                                            <option value="1" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lapinterim"] == 't') { echo 'selected'; } ?>> Ya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tutup_bulan_laptahunan" class="col-sm-3 col-form-label">Tutup AJP Tahunan </label>
                                    <div class="col-sm-3">
                                        <select id="tutup_bulan_laptahunan" name="tutup_bulan_laptahunan" class="form-control" >
                                            <option value="0" <?php if (isset($data_setup) && $data_setup["tutup_bulan_laptahunan"] == 'f') { echo 'selected'; } ?>> Tidak</option>
                                            <option value="1" <?php if (isset($data_setup) && $data_setup["tutup_bulan_laptahunan"] == 't') { echo 'selected'; } ?>> Ya</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group row">
                                    <label for="tutup_bulan_lapkap" class="col-sm-3 col-form-label">Tutup AJP KAP </label>
                                    <div class="col-sm-3">
                                        <select id="tutup_bulan_lapkap" name="tutup_bulan_lapkap" class="form-control" >
                                            <option value="0" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lapkap"] == 'f') { echo 'selected'; } ?>> Tidak</option>
                                            <option value="1" <?php if (isset($data_setup) && $data_setup["tutup_bulan_lapkap"] == 't') { echo 'selected'; } ?>> Ya</option>
                                        </select>
                                    </div>
                                </div>
                                
                                <div>
                                    <input name="id" type="hidden" class="form-control"  value="<?php echo $data_setup["id"]; ?>" />
                                    <input name="closing_saldo_awal" type="hidden" class="form-control"  value="<?php echo $data_setup["closing_saldo_awal"]; ?>" />
                                </div>

