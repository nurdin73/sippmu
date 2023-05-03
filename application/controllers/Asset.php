<?php

class Asset extends Admin_Controller
{
    const TANAH = [
        'status_tanah', 'jenis', 'perolehan', 'wakif_perolehan',
        'legalitas_bhn', 'pendayagunaan', 'pengelola', 'nilai_njop', 'nilai_bangunan'
    ];
    const GEDUNG = ['perolehan', 'jml_lokal', 'pendayagunaan', 'is_pusat'];

    public function __construct()
    {
        parent::__construct();
        $this->load->model('asset_model');
        $this->load->model('cabang_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Asset');
        $role = $this->customlib->getStaffRole();

        $tot_roles = $this->role_model->get();
        foreach ($tot_roles as $key => $value) {
            if ($value["id"] != 1) {
                $count_roles[$value["name"]] = $this->role_model->count_roles($value["id"]);
            }
        }
        $data["roles"] = $count_roles;

        $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"] = $event_colors;
        $userdata = $this->customlib->getUserData();
        $data["role"] = $userdata["user_type"];
        $this->load->view('layout/header', $data);
        $this->load->view('admin/asset/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function datatables()
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_view')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
        $role = strtolower($userdata['user_type']);
        $unitId = null;
        if ($role != 'super admin') {
            $unitId = $userdata['cabang'];
        }
        $results = $this->asset_model->data($unitId);
        header('Content-Type: application/json');
        echo $results;
    }

    public function unit($id = null)
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_view')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
        $role = strtolower($userdata['user_type']);
        if ($role != 'super admin') {
            $id = $userdata['cabang'];
        }
        $results = $this->asset_model->getByUnit($role, $id);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function create()
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_create')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Asset');
        $role = $this->customlib->getStaffRole();

        $tot_roles = $this->role_model->get();
        foreach ($tot_roles as $key => $value) {
            if ($value["id"] != 1) {
                $count_roles[$value["name"]] = $this->role_model->count_roles($value["id"]);
            }
        }
        $data = $this->getCategories();
        $data["roles"] = $count_roles;

        $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"] = $event_colors;
        $userdata = $this->customlib->getUserData();
        $data["role"] = $userdata["user_type"];

        $this->load->view('layout/header', $data);
        $this->load->view('admin/asset/create', $data);
        $this->load->view('layout/footer', $data);
    }

    public function insert()
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_create')) {
            access_denied();
        }
        $data = $this->validation();
        $this->asset_model->create($data);
        $this->session->set_flashdata('message', "<div class='alert alert-success'>Asset berhasil ditambahkan</div>");
        redirect('asset');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_edit')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'Asset');
        $role = $this->customlib->getStaffRole();

        $tot_roles = $this->role_model->get();
        foreach ($tot_roles as $key => $value) {
            if ($value["id"] != 1) {
                $count_roles[$value["name"]] = $this->role_model->count_roles($value["id"]);
            }
        }
        $data = $this->getCategories();
        $data["roles"] = $count_roles;

        $event_colors = array("#03a9f4", "#c53da9", "#757575", "#8e24aa", "#d81b60", "#7cb342", "#fb8c00", "#fb3b3b");
        $data["event_colors"] = $event_colors;
        $userdata = $this->customlib->getUserData();
        $data["role"] = $userdata["user_type"];

        $data['asset'] = $this->asset_model->get($id);

        $this->load->view('layout/header', $data);
        $this->load->view('admin/asset/edit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update($id)
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_edit')) {
            access_denied();
        }
        $data = $this->validation(true, $id);
        $this->asset_model->update($id, $data);
        $this->session->set_flashdata('message', "<div class='alert alert-success'>Asset berhasil Diubah</div>");
        redirect('asset');
    }

    public function destroy($id)
    {
        if (!$this->rbac->hasPrivilege('management_asset', 'can_delete')) {
            access_denied();
        }
        $this->asset_model->destroy($id);
        $this->session->set_flashdata('message', "<div class='alert alert-success'>Asset berhasil Dihapus</div>");
        redirect('asset');
    }

    protected function getCategories()
    {
        $data['jenis'] = $this->asset_model->getCategories('JENIS');
        $data['status_tanah'] = $this->asset_model->getCategories('STATUS_TANAH');
        $data['perolehan'] = $this->asset_model->getCategories('PEROLEHAN');
        $data['tipe_aset'] = $this->asset_model->getCategories('TIPE_ASET');
        return $data;
    }

    protected function validation($isUpdate = false, $id = null)
    {
        $this->form_validation->set_rules('unit_id', "Unit", 'required');
        $this->form_validation->set_rules('tipe_aset', "Tipe aset", 'required');
        // $this->form_validation->set_rules('luas_tanah', "Luas tanah", 'required|numeric');
        // $this->form_validation->set_rules('status_tanah', "Status tanah", 'required');
        // $this->form_validation->set_rules('jenis', "Jenis", 'required');
        $this->form_validation->set_rules('nilai_njop', "Nilai NJOP", 'numeric');
        $this->form_validation->set_rules('nilai_bangunan', "Nilai Bangunan", 'numeric');
        $this->form_validation->set_rules('coord', "Koordinat", 'required');
        $this->form_validation->set_rules('alamat', "Alamat", 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            if ($isUpdate) {
                redirect("asset/edit/$id");
            } else {
                redirect("asset/create");
            }
        }

        $data = $this->input->post(['unit_id', 'tipe_aset', 'status_tanah', 'jenis', 'perolehan', 'wakif_perolehan', 'legalitas_bhn', 'pendayagunaan', 'pengelola', 'nilai_njop', 'nilai_bangunan', 'alamat', 'coord', 'jml_lokal', 'is_pusat']);
        $latLng = explode(', ', $data['coord']);
        $cabang = $this->cabang_model->getData($data['unit_id']);
        unset($data['coord']);
        $unit = $data['unit_id'];
        $tipeAsset = $data['tipe_aset'];
        $alamat = $data['alamat'];
        $isPusat = $data['is_pusat'] ?? false;
        if ($isPusat) {
            if ($isUpdate) {
                $check = $this->asset_model->get($id);
                if (($unit != $check['unit_id']) && $this->asset_model->checkPusatIsExist($unit)) {
                    $this->session->set_flashdata('message', "<div class='alert alert-danger'>Gedung pusat sudah ada!</div>");
                    if ($isUpdate) {
                        redirect("asset/edit/$id");
                    } else {
                        redirect("asset/create");
                    }
                }
            } else {
                if ($this->asset_model->checkPusatIsExist($unit)) {
                    $this->session->set_flashdata('message', "<div class='alert alert-danger'>Gedung pusat sudah ada!</div>");
                    if ($isUpdate) {
                        redirect("asset/edit/$id");
                    } else {
                        redirect("asset/create");
                    }
                }
            }
        }
        if ($tipeAsset == 'TANAH') {
            foreach ($data as $key => $value) {
                if (!in_array($key, self::TANAH)) {
                    // unset($data[$key]);
                    $data[$key] = null;
                }
            }
        }
        if ($tipeAsset == 'GEDUNG') {
            foreach ($data as $key => $value) {
                if (!in_array($key, self::GEDUNG)) {
                    // unset($data[$key]);
                    $data[$key] = null;
                }
            }
        }
        $data['unit_id'] = $unit;
        $data['tipe_aset'] = $tipeAsset;
        $data['name'] = $data['tipe_aset'] . '-' . $cabang['kode'];
        $data['latitude'] = $latLng[0];
        $data['longitude'] = $latLng[1];
        $data['alamat'] = $alamat;
        $data['is_pusat'] = $isPusat;
        if ($isUpdate) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }

        if (!$isUpdate) {
            if (!isset($_FILES['sertifikat']) && empty($_FILES['sertifikat']['name'])) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Sertifikat wajib di isi!</div>");
                if ($isUpdate) {
                    redirect("asset/edit/$id");
                } else {
                    redirect("asset/create");
                }
            }
            $fileInfo = pathinfo($_FILES["sertifikat"]["name"]);
            if ($fileInfo['extension'] != 'pdf') {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Sertifikat wajib berisi dokumen pdf!</div>");
                if ($isUpdate) {
                    redirect("asset/edit/$id");
                } else {
                    redirect("asset/create");
                }
            }
            if (number_format((($_FILES['sertifikat']['size'] / 1024) / 1024), 2) > 2) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Sertifikat maksimal 2MB</div>");
                if ($isUpdate) {
                    redirect("asset/edit/$id");
                } else {
                    redirect("asset/create");
                }
            }
            $fileName = randomStr(10) . '.' . $fileInfo['extension'];
            move_uploaded_file($_FILES["sertifikat"]["tmp_name"], "./uploads/sertifikat/" . $fileName);
            $data['sertifikat'] = $fileName;
        } else {
            if (isset($_FILES['sertifikat']) && !empty($_FILES['sertifikat']['name'])) {
                $fileInfo = pathinfo($_FILES["sertifikat"]["name"]);
                if ($fileInfo['extension'] != 'pdf') {
                    $this->session->set_flashdata('message', "<div class='alert alert-danger'>Sertifikat wajib berisi dokumen pdf!</div>");
                    if ($isUpdate) {
                        redirect("asset/edit/$id");
                    } else {
                        redirect("asset/create");
                    }
                }
                if (number_format((($_FILES['sertifikat']['size'] / 1024) / 1024), 2) > 2) {
                    $this->session->set_flashdata('message', "<div class='alert alert-danger'>Sertifikat maksimal 2MB</div>");
                    if ($isUpdate) {
                        redirect("asset/edit/$id");
                    } else {
                        redirect("asset/create");
                    }
                }
                $fileName = randomStr(10) . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["sertifikat"]["tmp_name"], "./uploads/sertifikat/" . $fileName);
                $data['sertifikat'] = $fileName;
            }
        }
        return $data;
    }
}
