<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10">Profile</h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>anggota">Management Anggota</a>
                    <li class="breadcrumb-item active"><a href="#">Tambah Anggota</a></li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary">
                    <div class="box-body  d-flex justify-content-center">
                        <img src="<?= base_url("uploads/user_images/avatar.jpg") ?>" width="150" height="150" id="avatar" alt="avatar" class="img-circle img-fluid img-responsive" style="object-fit: cover;">
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <div class="box box-primary">
                    <div class="box-body">
                        <form action="<?= base_url('anggota/insert') ?>" method="post" enctype="multipart/form-data">
                            <?= $this->session->flashdata('message'); ?>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="username">Username</label>
                                        <input type="text" name="username" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="nbm">Nomor Baku Muhammadiyah(NBM)</label>
                                        <input type="text" name="nbm" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="password">Password</label>
                                <input type="password" name="password" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="confirm">Confirm Password</label>
                                <input type="password" name="confirm" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="name">Nama</label>
                                <input type="text" name="name" class="form-control">
                            </div>
                            <div class="form-group">
                                <label for="role">Role</label>
                                <select name="role" id="role" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($getStaffRole as $val) {
                                        echo "<option value='" . $val['id'] . "'>" . $val['type'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="row">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="email">Email</label>
                                        <input type="text" name="email" class="form-control">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Telepon</label>
                                        <input type="text" name="phone" class="form-control">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label for="gender">Gender</label>
                                <select name="gender" id="gender" class="form-control">
                                    <option value="">Pilih</option>
                                    <?php
                                    foreach ($genderList as $key => $value) {
                                        echo "<option value='" . $key . "'>" . $value . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="address">Alamat</label>
                                <textarea name="address" id="address" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="note">Note</label>
                                <textarea name="note" id="note" cols="30" rows="3" class="form-control"></textarea>
                            </div>
                            <div class="form-group">
                                <label for="image">Pilih Foto (Maksimal 2MB)</label>
                                <input type="file" name="image" accept="image/*">
                            </div>
                            <a href="<?= base_url('anggota') ?>" class="btn btn-outline-primary" style="width: 150px;">
                                Kembali
                            </a>
                            <button class="btn btn-primary" style="width: 150px;" type="submit">Simpan</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    $(document).ready(function() {
        $('input[name=image]').on('change', function(e) {
            const file = e.target.files[0];
            const url = URL.createObjectURL(file);
            $('#avatar').attr('src', url)
            $('#avatar').attr('alt', file.name)
        })
    });
</script>