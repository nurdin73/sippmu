<?php

class Jabatan extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('jabatan_model');
        $this->load->model('cabang_model');
    }

    function index()
    {
        $this->session->set_userdata('top_menu', 'Master Jabatan');
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
        $this->load->view('master/jabatan', $data);
        $this->load->view('layout/footer', $data);
    }

    public function data()
    {
        $all = $this->jabatan_model->getAll();
        header('Content-Type: application/json');
        echo $all;
    }

    function ajax()
    {
        $filter = $this->input->get('search');
        $unit = $this->input->get('unit');
        $results = $this->jabatan_model->getData($unit, $filter);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    function get($id)
    {
        $find = $this->jabatan_model->get($id);
        if (!$find) header("HTTP/1.1 404 Not Found");
        if ($find) {
            header('Content-Type: application/json');
            echo json_encode($find);
        }
    }

    public function unit($id)
    {
        $filter = $this->input->get('search') ?? null;
        $results = $this->jabatan_model->jabatanByUnit($id, $filter);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    function insert()
    {
        $attr = $this->validation();
        $unit = $this->cabang_model->getData($attr['id_unit'])['nama'];
        $attr['kode'] = $this->jabatan_model->generateCode(initial($unit));
        $this->jabatan_model->create($attr);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil dibuat</div>');
        redirect('master/jabatan');
    }

    public function update($id)
    {
        $attr = $this->validation(true);
        $attr['kode'] = null;
        $this->jabatan_model->update($id, $attr);
        $unit = $this->cabang_model->getData($attr['id_unit'])['nama'];
        $kode = $this->jabatan_model->generateCode(initial($unit));
        $this->jabatan_model->update($id, [
            'kode' => $kode
        ]);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil diupdate</div>');
        redirect('master/jabatan');
    }

    public function destroy($id)
    {
        $this->jabatan_model->destroy($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil dihapus</div>');
        redirect('master/jabatan');
    }

    public function validation($isUpdate = false)
    {
        if (!$this->rbac->hasPrivilege('master_jabatan', 'can_view')) {
            access_denied();
        }
        $this->form_validation->set_rules('nama', 'Nama', 'required');
        $this->form_validation->set_rules('unit_kerja', 'Unit Kerja', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('master/jabatan');
        }
        $data = $this->input->post(['nama', 'unit_kerja', 'status']);
        $attr['nama'] = $data['nama'];
        $attr['id_unit'] = $data['unit_kerja'];
        $attr['is_active'] = $data['status'] ?? false;
        if (!$isUpdate) {
            $attr['created_by'] = $this->customlib->getStaffID();
        }
        $attr['updated_by'] = $this->customlib->getStaffID();
        return $attr;
    }
}
