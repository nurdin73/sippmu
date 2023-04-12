<?php

class Sdm extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('sdm_model');
    }

    function index()
    {
        $this->session->set_userdata('top_menu', 'SDM');
        $role = $this->customlib->getStaffRole();
        $role_id = json_decode($role)->id;
        $userid = $this->customlib->getStaffID();

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
        $this->load->view('admin/sdm/index', $data);
        $this->load->view('layout/footer', $data);
    }

    function datatable()
    {
        header('Content-Type: application/json');
        echo $this->sdm_model->getAll();
    }

    function insert()
    {
        if (!$this->rbac->hasPrivilege('sdm', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nbm', 'Nomor Baku Muhammadiyah', 'required');
        $this->form_validation->set_rules('unit_kerja', 'Unit Kerja', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Nama', 'required');
        $this->form_validation->set_rules('ttd', 'Status Tanda tangan', 'required');

        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('sdm');
        } else {
            $data = $this->input->post(['nama', 'nbm', 'unit_kerja', 'tanggal_lahir', 'telepon', 'hp', 'alamat', 'ttd', 'tempat_lahir', 'status']);
            $attr['sdm_nama'] = $data['nama'];
            $attr['sdm_jabatan'] = null;
            $attr['sdm_alamat'] = $data['alamat'];
            $attr['sdm_phone'] = $data['telepon'];
            $attr['sdm_hp'] = $data['hp'];
            $attr['sdm_tmp_lahir'] = $data['tempat_lahir'];
            $attr['sdm_tgl_lahir'] = $data['tanggal_lahir'];
            $attr['sdm_nbm'] = $data['nbm'];
            $attr['sdm_status_ttd'] = $data['ttd'];
            $attr['uk_id'] = $data['unit_kerja'];
            $attr['create_date'] = time();
            $attr['create_userid'] = $this->customlib->getStaffID();
            $attr['update_date'] = time();
            $attr['update_userid'] = $this->customlib->getStaffID();
            $attr['status'] = $data['status'] ?? false;
            $this->sdm_model->create($attr);

            $this->session->set_flashdata('message', '<div class="alert alert-success">Sdm berhasil dibuat</div>');
            redirect('sdm');
            // echo json_encode($data);
        }
    }

    function get($id)
    {
        $find = $this->sdm_model->get($id);
        header('Content-Type: application/json');
        echo json_encode($find);
    }

    function sdmByUnit($id)
    {
        $results = $this->sdm_model->sdmByUnit($id);
        header('Content-Type: application/json');
        echo $results;
    }

    public function all()
    {
        $filter = $this->input->get('search');
        $results = $this->sdm_model->all($filter);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function update($id)
    {
        if (!$this->rbac->hasPrivilege('sdm', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('nbm', 'Nomor Baku Muhammadiyah', 'required');
        $this->form_validation->set_rules('unit_kerja', 'Unit Kerja', 'required');
        $this->form_validation->set_rules('tanggal_lahir', 'Nama', 'required');
        $this->form_validation->set_rules('ttd', 'Status Tanda tangan', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('sdm');
        }
        $data = $this->input->post(['nama', 'nbm', 'unit_kerja', 'tanggal_lahir', 'telepon', 'hp', 'alamat', 'ttd', 'tempat_lahir', 'status']);
        $attr['sdm_nama'] = $data['nama'];
        $attr['sdm_jabatan'] = null;
        $attr['sdm_alamat'] = $data['alamat'];
        $attr['sdm_phone'] = $data['telepon'];
        $attr['sdm_hp'] = $data['hp'];
        $attr['sdm_tmp_lahir'] = $data['tempat_lahir'];
        $attr['sdm_tgl_lahir'] = $data['tanggal_lahir'];
        $attr['sdm_nbm'] = $data['nbm'];
        $attr['sdm_status_ttd'] = $data['ttd'];
        $attr['uk_id'] = $data['unit_kerja'];
        $attr['update_date'] = time();
        $attr['update_userid'] = $this->customlib->getStaffID();
        $attr['status'] = $data['status'] ?? false;

        $this->sdm_model->update($id, $attr);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Sdm berhasil diupdate</div>');
        redirect('sdm');
    }

    public function updateStatus($id)
    {
        $status = $this->input->post('status');
        $this->sdm_model->update($id, [
            'is_active' => $status,
        ]);
        header('Content-Type: application/json');
        echo json_encode([
            'message' => "Status berhasil diubah"
        ]);
    }

    function destroy($id)
    {
        if (!$this->sdm_model->get($id)) {
            $this->session->set_flashdata('message', '<div class="alert alert-error">Sdm tidak ditemukan</div>');
            redirect('sdm');
        }
        $this->sdm_model->destroy($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Sdm berhasil dihapus</div>');
        redirect('sdm');
    }
}
