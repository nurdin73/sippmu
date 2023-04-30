<?php

class Asset extends Admin_Controller
{
    const TANAH = [
        'luas_tanah', 'status_tanah', 'jenis', 'perolehan', 'wakif_perolehan',
        'legalitas_bhn', 'pendayagunaan', 'pengelola', 'nilai_njop', 'nilai_bangunan'
    ];
    const GEDUNG = ['luas_tanah', 'perolehan', 'luas_bangunan', 'jml_lokal', 'pendayagunaan'];

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
        $results = $this->asset_model->data();
        header('Content-Type: application/json');
        echo $results;
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
        $this->form_validation->set_rules('luas_tanah', "Luas tanah", 'required|numeric');
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

        $data = $this->input->post(['unit_id', 'luas_tanah', 'tipe_aset', 'status_tanah', 'jenis', 'perolehan', 'wakif_perolehan', 'legalitas_bhn', 'pendayagunaan', 'pengelola', 'nilai_njop', 'nilai_bangunan', 'alamat', 'coord', 'jml_lokal', 'luas_bangunan']);
        $latLng = explode(', ', $data['coord']);
        $cabang = $this->cabang_model->getData($data['unit_id']);
        unset($data['coord']);
        if ($isUpdate) {
            $data['updated_at'] = date('Y-m-d H:i:s');
        }
        $unit = $data['unit_id'];
        $tipeAsset = $data['tipe_aset'];
        $alamat = $data['alamat'];
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
        return $data;
    }
}
