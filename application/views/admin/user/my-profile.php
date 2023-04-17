<div class="page-header">
    <div class="page-block">
        <div class="row align-items-center">
            <div class="col-md-12">
                <div class="page-header-title">
                    <h5 class="m-b-10"><?php echo $title; ?></h5>
                </div>
                <ul class="breadcrumb">
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>dashboard"><i
                                class="feather icon-home"></i></a></li>
                    <li class="breadcrumb-item"><a href="<?= base_url('admin/user') ?>">Manajemen User</a></li>
                    <li class="breadcrumb-item"><a href="<?php echo base_url(); ?>admin/user"><?php echo $title; ?></a>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <div class="row">
            <div class="col-md-4">
                <div class="box box-primary" <?php
                                                if ($user["is_active"] == 0) {
                                                    echo "style='background-color:#f0dddd;'";
                                                }
                                                ?>>
                    <div class="box-body">
                        <div class="d-flex flex-column justify-content-center align-items-center">
                            <?php
                            $image = $user['image'];
                            if (!empty($image)) {

                                $file = $user['image'];
                            } else {
                                $file = "avatar.jpg";
                            }
                            ?>
                            <img src="<?= base_url("uploads/user_images/$file") ?>" alt=""
                                class="img-circle img-fluid img-responsive">
                            <h3><?= $user['name'] ?></h3>
                            <div class="border p-3 w-100 rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="font-weight-bold">Username</span>
                                    <span class="text-primary"><?= $user['username'] ?? 'N/A' ?></span>
                                </div>
                                <div class="d-flex mt-3 justify-content-between align-items-center">
                                    <span class="font-weight-bold">Email</span>
                                    <span
                                        class="text-primary"><?= isset($user['email']) && $user['email'] != '' ? $user['email'] : 'N/A' ?></span>
                                </div>
                                <div class="d-flex mt-3 justify-content-between align-items-center">
                                    <span class="font-weight-bold">Unit Kerja</span>
                                    <span
                                        class="text-primary"><?= isset($user['cabang']) && $user['cabang'] != '' ? $user['cabang'] : 'N/A' ?></span>
                                </div>
                                <div class="d-flex mt-3 justify-content-between align-items-center">
                                    <span class="font-weight-bold">Role</span>
                                    <span
                                        class="text-primary"><?= isset($user['user_type']) && $user['user_type'] != '' ? $user['user_type'] : 'N/A' ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-8">
                <?= $this->session->flashdata('message') ?>

                <div class="box w-100" style="border-top: 0;">
                    <div class="nav-tabs-custom mb-0">
                        <ul class="nav nav-tabs">
                            <li class="active "><a href="#profile" data-toggle="tab" aria-expanded="false">Profile</a>
                            </li>
                            <li class=""><a href="#password" data-toggle="tab" aria-expanded="false">Password</a>
                            </li>
                        </ul>
                    </div>
                    <div class="box-body tab-content">
                        <div class="tab-pane active" role="tabpanel" id="profile">
                            <form action="<?= base_url('myprofile/update/') ?>" method="post"
                                enctype="multipart/form-data">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="username">Username</label>
                                            <input type="text" class="form-control" name="username"
                                                value="<?= $user['username'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="nbm">Nomor Baku Muhammadiyah (NBM)</label>
                                            <input type="text" class="form-control" name="nbm"
                                                value="<?= $user['nbm'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="name">Nama</label>
                                    <input type="text" class="form-control" name="name" value="<?= $user['name'] ?>">
                                </div>
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="email">Email</label>
                                            <input type="text" class="form-control" name="email"
                                                value="<?= $user['email'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="phone">Telepon</label>
                                            <input type="text" class="form-control" name="phone"
                                                value="<?= $user['phone'] ?>">
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="gender">Gender</label>
                                    <select name="gender" class="form-control">
                                        <option value="">Pilih</option>
                                        <?php
                                        foreach ($genderList as $key => $value) {
                                        ?>
                                        <option value="<?php echo $key; ?>"
                                            <?php if ($user['gender'] == $key) echo "selected"; ?>>
                                            <?php echo $value; ?></option>
                                        <?php
                                        }
                                        ?>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label for="address">Alamat</label>
                                    <textarea name="address" cols="30" rows="3"
                                        class="form-control"><?= $user['address'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="note">Note</label>
                                    <textarea name="note" cols="30" rows="3"
                                        class="form-control"><?= $user['note'] ?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="image">Pilih Foto (Maksimal 2MB)</label>
                                    <input type="file" name="image" accept="image/*">
                                </div>
                                <a href="<?= base_url('myprofile') ?>" class="btn btn-outline-primary"
                                    style="width: 150px;">
                                    Reset
                                </a>
                                <button class="btn btn-primary" style="width: 150px;" type="submit">Simpan</button>
                            </form>
                        </div>
                        <div class="tab-pane" role="tabpanel" id="password">
                            <form action="<?= base_url('myprofile/update_password/') ?>" method="post">
                                <div class="form-group">
                                    <label for="old_password">Old Password</label>
                                    <input type="password" name="old_password" id="old_password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="new_password">New Password</label>
                                    <input type="password" name="new_password" id="new_password" class="form-control">
                                </div>
                                <div class="form-group">
                                    <label for="password_confirmation">Password Confirmation</label>
                                    <input type="password" name="password_confirmation" id="password_confirmation"
                                        class="form-control">
                                </div>
                                <a href="<?= base_url('myprofile') ?>" class="btn btn-outline-primary"
                                    style="width: 150px;">
                                    Reset
                                </a>
                                <button class="btn btn-primary" style="width: 150px;" type="submit">Simpan</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
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
});
</script>