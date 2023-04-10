<?php

class Periode extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('periode_model');
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
        $this->load->view('master/periode', $data);
        $this->load->view('layout/footer', $data);
    }

    function table()
    {
        $results = $this->periode_model->table();
        header('Content-Type: application/json');
        echo $results;
    }

    function all()
    {
        $filter = $this->input->get('search') ?? null;
        $limit = $this->input->get('limit') ?? null;
        $results = $this->periode_model->all($filter, $limit);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    function get($id)
    {
        $find = $this->periode_model->get($id);
        if (!$find) header("HTTP/1.1 404 Not Found");
        if ($find) {
            header('Content-Type: application/json');
            echo json_encode($find);
        }
    }

    function create()
    {
        $attr = $this->validation();
        $this->periode_model->create($attr);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Periode berhasil dibuat</div>');
        redirect('master/periode');
    }

    function update($id)
    {
        $attr = $this->validation(true);
        $this->periode_model->update($id, $attr);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Periode berhasil diubah</div>');
        redirect('master/periode');
    }

    function delete($id)
    {
        $this->periode_model->delete($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Periode berhasil dihapus</div>');
        redirect('master/periode');
    }

    function validation($isUpdate = false)
    {
        $this->form_validation->set_rules('title', 'Title', 'required');
        $this->form_validation->set_rules('start', 'Mulai', 'required');
        $this->form_validation->set_rules('end', 'Akhir', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('master/periode');
        }
        $data = $this->input->post(['title', 'start', 'end']);
        if (!$isUpdate) {
            $data['created_by'] = $this->customlib->getStaffID();
        }
        $data['updated_by'] = $this->customlib->getStaffID();
        return $data;
    }
}
