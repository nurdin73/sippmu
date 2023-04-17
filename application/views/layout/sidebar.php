                    <ul class="nav pcoded-inner-navbar ">
                        <?php

                        $is_admin = $this->customlib->getSessionIsAdmin();
                        $is_superadmin = $this->customlib->getSessionIsSuperadmin();

                        if ($this->module_lib->hasActive('dashboard')) {
                            if ($this->rbac->hasPrivilege('dashboard', 'can_view')) {
                        ?>
                                <li class="nav-item <?php echo set_Topmenu('dashboard'); ?>"><a href="<?php echo base_url(); ?>dashboard" class="nav-link "><span class="pcoded-micon"><i class="feather icon-home"></i></span><span class="pcoded-mtext">Dashboard</span></a></li>

                            <?php
                            }
                        }


                        if ($this->module_lib->hasActive('profile')) {
                            if ($this->rbac->hasPrivilege('profile', 'can_view')) {
                            ?>
                                <li class="nav-item <?php echo set_Topmenu('profile'); ?>"><a href="<?php echo base_url(); ?>profile" class="nav-link "><span class="pcoded-micon"><i class="feather icon-users"></i></span><span class="pcoded-mtext">Profile</span></a></li>

                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('transaksi_kasir')) {
                            if (
                                $this->rbac->hasPrivilege('trx_penerimaan', 'can_view') || $this->rbac->hasPrivilege('trx_pengeluaran', 'can_view') ||
                                $this->rbac->hasPrivilege('trx_mutasi_kas', 'can_view')
                            ) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('transaksi_kasir'); ?>"> -->
                                <!-- <a href="#!" class="nav-link has-ripple"><span class="pcoded-micon"><i class="feather icon-layers"></i></span><span class="pcoded-mtext">Transaksi Kas</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php
                                if ($this->rbac->hasPrivilege('trx_penerimaan', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/penerimaan'); ?>"><a href="<?php echo base_url(); ?>transaction/penerimaan"> <?php echo $this->lang->line('penerimaan'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('trx_pengeluaran', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/pengeluaran'); ?>"><a href="<?php echo base_url(); ?>transaction/pengeluaran"> <?php echo $this->lang->line('pengeluaran'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('trx_mutasi_kas', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/mutasi_kas'); ?>"><a href="<?php echo base_url(); ?>transaction/mutasi_kas"> <?php echo $this->lang->line('mutasi_kas'); ?></a></li> -->

                                <?php
                                }
                                ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('transaksi_akuntansi')) {
                            if (
                                $this->rbac->hasPrivilege('trx_saldo_awal', 'can_view') || $this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_view') ||
                                $this->rbac->hasPrivilege('trx_tutup_periode', 'can_view')
                            ) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('transaksi_akuntansi'); ?>"> -->
                                <!-- <a href="#!" class="nav-link has-ripple"><span class="pcoded-micon"><i class="feather icon-codepen"></i></span><span class="pcoded-mtext">Transaksi Akuntansi</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php
                                if ($this->rbac->hasPrivilege('trx_saldo_awal', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/saldo_awal'); ?>"><a href="<?php echo base_url(); ?>transaction/saldo_awal"> <?php echo $this->lang->line('saldo_awal'); ?> </a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/mutasi_jurnal'); ?>"><a href="<?php echo base_url(); ?>transaction/mutasi_jurnal"> <?php echo $this->lang->line('mutasi_jurnal'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('trx_tutup_periode', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('transaction/tutup_periode'); ?>"><a href="<?php echo base_url(); ?>transaction/tutup_periode"> <?php echo $this->lang->line('tutup_periode'); ?></a></li> -->

                                <?php
                                }
                                ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('laporan_kas_bank')) {
                            if (
                                $this->rbac->hasPrivilege('report_mutasi_kas_bank', 'can_view') || $this->rbac->hasPrivilege('posisi_saldo_kas_bank', 'can_view')
                            ) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('laporan_kas_bank'); ?>"> -->
                                <!-- <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-book"></i></span><span class="pcoded-mtext">Laporan Kas Bank</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php
                                if ($this->rbac->hasPrivilege('report_penerimaan', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/penerimaan'); ?>"><a class="report" href="<?php echo base_url(); ?>laporan/penerimaan">Laporan Penerimaan</a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_pengeluaran', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/pengeluaran'); ?>"><a class="report" href="<?php echo base_url(); ?>laporan/pengeluaran">Laporan Pengeluaran</a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_pindah_buku', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/pindah_buku'); ?>"><a class="report" href="<?php echo base_url(); ?>laporan/pindah_buku">Laporan Pindah Buku</a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_mutasi_kas_bank', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/mutasi_kas_bank'); ?>"><a class="report" href="<?php echo base_url(); ?>laporan/mutasi_kas_bank">Mutasi Kas Bank</a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_posisi_saldo_kas_bank', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/posisi_saldo_kas_bank'); ?>"><a class="report" href="<?php echo base_url(); ?>laporan/posisi_saldo_kas_bank">Posisi Saldo Kas Bank</a></li> -->
                                <?php
                                }
                                ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('laporan_akuntansi')) {
                            if (
                                $this->rbac->hasPrivilege('report_buku_besar', 'can_view') || $this->rbac->hasPrivilege('report_neraca_saldo', 'can_view') ||
                                $this->rbac->hasPrivilege('report_arus_kas', 'can_view') || $this->rbac->hasPrivilege('report_perubahan_dana', 'can_view') ||
                                $this->rbac->hasPrivilege('report_posisi_keuangan', 'can_view') || $this->rbac->hasPrivilege('report_aktivitas', 'can_view') ||
                                $this->rbac->hasPrivilege('report_perubahan_dana_tahun', 'can_view') || $this->rbac->hasPrivilege('report_arus_kas_tahun', 'can_view')
                            ) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('laporan_akuntansi'); ?>"> -->
                                <!-- <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-package"></i></span><span class="pcoded-mtext">Laporan Akuntansi</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php
                                if ($this->rbac->hasPrivilege('report_buku_besar', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/buku_besar'); ?>"><a href="<?php echo base_url(); ?>laporan/buku_besar"> <?php echo $this->lang->line('buku_besar2'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_neraca_saldo', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/neraca_saldo'); ?>"><a href="<?php echo base_url(); ?>laporan/neraca_saldo"> <?php echo $this->lang->line('neraca_saldo2'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_arus_kas', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/arus_kas'); ?>"><a href="<?php echo base_url(); ?>laporan/arus_kas"> <?php echo $this->lang->line('arus_kas2'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_arus_kas_tahun', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/arus_kas_tahun'); ?>"><a href="<?php echo base_url(); ?>laporan/arus_kas_tahun"> <?php echo $this->lang->line('arus_kas_tahun2'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_aktivitas', 'can_view')) {
                                    //Laporan Aktivitas
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/aktivitas'); ?>"><a href="<?php echo base_url(); ?>laporan/aktivitas"> Laporan Aktivitas</a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_posisi_keuangan', 'can_view')) {
                                    //Laporan Posisi Keuangan / Laporan Neraca
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/posisi_keuangan'); ?>"><a href="<?php echo base_url(); ?>laporan/posisi_keuangan"> Laporan Posisi Keuangan</a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_perubahan_dana', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/perubahan_dana'); ?>"><a href="<?php echo base_url(); ?>laporan/perubahan_dana"> <?php echo $this->lang->line('perubahan_dana2'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('report_perubahan_dana_tahun', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('laporan/perubahan_dana_tahun'); ?>"><a href="<?php echo base_url(); ?>laporan/perubahan_dana_tahun"> <?php echo $this->lang->line('perubahan_dana_tahun2'); ?></a></li> -->

                                <?php
                                }
                                ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('master_akuntansi') || $this->module_lib->hasActive('master_buku_kas')) {
                            if (
                                $this->rbac->hasPrivilege('master_kel_akun', 'can_view') || $this->rbac->hasPrivilege('master_akun', 'can_view') ||
                                $this->rbac->hasPrivilege('master_tipe_jurnal', 'can_view') || $this->rbac->hasPrivilege('master_currency', 'can_view') ||
                                $this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_view') || $this->rbac->hasPrivilege('master_arus_kas', 'can_view') ||
                                //$this->rbac->hasPrivilege('master_jenis_dana', 'can_view') || $this->rbac->hasPrivilege('master_jenis_dana_transaksi', 'can_view') ||
                                $this->rbac->hasPrivilege('master_akun_kas', 'can_view') || $this->rbac->hasPrivilege('master_kode_transaksi', 'can_view')
                            ) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('master_akuntansi'); ?>"> -->
                                <!-- <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-align-justify"></i></span><span class="pcoded-mtext">Master Akuntansi</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php

                                if ($this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/setup_periode_laporan'); ?>"><a href="<?php echo base_url(); ?>master/setup_periode_laporan"> <?php echo $this->lang->line('setup_periode_laporan'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_akun', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/akun'); ?>"><a href="<?php echo base_url(); ?>master/akun"> <?php echo $this->lang->line('akun'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_kode_transaksi', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/kode_transaksi'); ?>"><a href="<?php echo base_url(); ?>master/kode_transaksi"> <?php echo $this->lang->line('kode_transaksi'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_akun_kas', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/akun_kas'); ?>"><a href="<?php echo base_url(); ?>master/akun_kas"> <?php echo $this->lang->line('akun_kas'); ?></a></li> -->
                                <?php
                                }

                                if ($this->rbac->hasPrivilege('master_arus_kas', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/arus_kas'); ?>"><a href="<?php echo base_url(); ?>master/arus_kas"> Mapping Arus Kas</a></li> -->
                                <?php
                                }

                                if ($this->rbac->hasPrivilege('mapping_aktivitas', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/mapping_aktivitas'); ?>"><a href="<?php echo base_url(); ?>master/mapping_aktivitas"> Mapping Laporan Aktivitas</a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/mapping_posisi_keuangan'); ?>"><a href="<?php echo base_url(); ?>master/mapping_posisi_keuangan"> Mapping Laporan Posisi Keuangan</a></li> -->
                                <?php
                                }

                                if ($this->rbac->hasPrivilege('master_kel_akun', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master_kel_akun'); ?>"><a href="<?php echo base_url(); ?>master/kel_akun"> <?php echo $this->lang->line('kel_akun'); ?></a></li> -->

                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_tipe_jurnal', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/tipe_jurnal'); ?>"><a href="<?php echo base_url(); ?>master/tipe_jurnal"> <?php echo $this->lang->line('tipe_jurnal'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_currency', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/currency'); ?>"><a href="<?php echo base_url(); ?>master/currency"> <?php echo $this->lang->line('currency'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('master_setting_parameter', 'can_view')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('master/setting_parameter'); ?>"><a href="<?php echo base_url(); ?>master/setting_parameter"> Setting Parameter</a></li> -->
                                <?php
                                }

                                ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                            <?php
                            }
                        }


                        if ($this->module_lib->hasActive('master_data')) {
                            if (
                                $this->rbac->hasPrivilege('master_cabang', 'can_view') || $this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_view') ||
                                $this->rbac->hasPrivilege('master_program', 'can_view')
                            ) {
                            ?>
                                <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('master_data'); ?>">
                                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-cpu"></i></span><span class="pcoded-mtext">Master
                                            Data</span></a>
                                    <ul class="pcoded-submenu">
                                        <?php
                                        if ($this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_view')) {
                                        ?>
                                            <li class="<?php echo set_Submenu('master/cabang_klasifikasi'); ?>"><a href="<?php echo base_url(); ?>master/cabang_klasifikasi"> Group Unit</a></li>

                                        <?php
                                        }
                                        if ($this->rbac->hasPrivilege('master_cabang', 'can_view')) {
                                        ?>
                                            <li class="<?php echo set_Submenu('master/cabang'); ?>"><a href="<?php echo base_url(); ?>master/cabang"> Unit Kerja</a></li>

                                        <?php
                                        }

                                        if ($this->rbac->hasPrivilege('master_jabatan', 'can_view')) {
                                        ?>
                                            <li class="<?php echo set_Submenu('master/jabatan'); ?>"><a href="<?php echo base_url(); ?>master/jabatan"> Jabatan</a></li>

                                        <?php
                                        }

                                        if ($this->rbac->hasPrivilege('master_periode', 'can_view')) {
                                        ?>
                                            <li class="<?php echo set_Submenu('master/periode'); ?>"><a href="<?php echo base_url(); ?>master/periode"> Periode</a></li>

                                        <?php
                                        }

                                        if ($this->rbac->hasPrivilege('master_clients', 'can_view')) {
                                        ?>
                                            <li class="<?php echo set_Submenu('master/clients'); ?>"><a href="<?php echo base_url(); ?>master/clients"> Clients</a></li>
                                        <?php
                                        }
                                        ?>
                                    </ul>
                                </li>
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('user_management')) {
                            if (($this->rbac->hasPrivilege('user', 'can_view'))) {
                            ?>
                                <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('HR'); ?>">
                                    <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-user"></i></span><span class="pcoded-mtext">Manajemen
                                            User</span></a>
                                    <ul class="pcoded-submenu">
                                        <?php if ($this->rbac->hasPrivilege('user', 'can_view')) { ?>
                                            <li class="<?php echo set_Submenu('admin/user'); ?>"><a href="<?php echo base_url(); ?>admin/user">
                                                    <?php echo $this->lang->line('user_directory'); ?></a></li>

                                        <?php
                                        }
                                        //                                      
                                        ?>

                                        <?php if ($is_admin || $is_superadmin) { ?>
                                            <li class="<?php echo set_Submenu('admin/roles'); ?>"><a href="<?php echo base_url(); ?>admin/roles">
                                                    <?php echo $this->lang->line('roles_permissions'); ?></a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                            <?php
                            }
                        }

                        if ($this->module_lib->hasActive('system_settings')) {
                            if (($this->rbac->hasPrivilege('general_setting', 'can_edit') ||
                                $this->rbac->hasPrivilege('email_setting', 'can_edit') ||
                                $this->rbac->hasPrivilege('backup_restore', 'can_view'))) {
                            ?>
                                <!-- <li class="nav-item pcoded-hasmenu <?php echo set_Topmenu('System Settings'); ?>"> -->
                                <!-- <a href="#!" class="nav-link "><span class="pcoded-micon"><i class="feather icon-settings"></i></span><span class="pcoded-mtext">Settings</span></a> -->
                                <!-- <ul class="pcoded-submenu"> -->
                                <?php
                                if ($this->rbac->hasPrivilege('general_setting', 'can_edit')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('vsettings/index'); ?>"><a href="<?php echo base_url(); ?>vsettings"> <?php echo $this->lang->line('general_settings'); ?></a></li> -->
                                <?php
                                }
                                if ($this->rbac->hasPrivilege('email_setting', 'can_edit')) {
                                ?>
                                    <!-- <li class="<?php echo set_Submenu('emailconfig/index'); ?>"><a href="<?php echo base_url(); ?>emailconfig"> <?php echo $this->lang->line('email_setting'); ?></a></li> -->
                                <?php
                                }

                                ?>

                                <?php
                                if ($is_superadmin) { ?>
                                    <!-- <li class="<?php echo set_Submenu('admin/module'); ?>"><a href="<?php echo base_url(); ?>admin/module"> <?php echo $this->lang->line('modules'); ?></a></li> -->
                                <?php  } ?>
                                <!-- </ul> -->
                                <!-- </li> -->
                        <?php
                            }
                        }
                        ?>


                    </ul>
                    </section>
                    </aside>