<?php

class Clients extends Admin_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('oauth_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Master Clients');
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
        $this->load->view('master/clients', $data);
        $this->load->view('layout/footer', $data);
    }

    public function datatable()
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_view')) {
            access_denied();
        }
        $all = $this->oauth_model->getAll();
        header('Content-Type: application/json');
        echo $all;
    }

    public function get($id)
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_view')) {
            access_denied();
        }
        $find = $this->oauth_model->get($id);
        if (!$find) header("HTTP/1.1 404 Not Found");
        if ($find) {
            header('Content-Type: application/json');
            echo json_encode($find);
        }
    }

    public function insert()
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_add')) {
            access_denied();
        }
        $attr = $this->validation();
        $this->oauth_model->create($attr);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Client berhasil dibuat</div>');
        redirect('master/clients');
    }

    public function update($id)
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_edit')) {
            access_denied();
        }
        $attr = $this->validation(true);
        $this->oauth_model->update($id, $attr);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Client berhasil diubah</div>');
        redirect('master/clients');
    }

    public function delete($id)
    {
        if (!$this->rbac->hasPrivilege('master_clients', 'can_delete')) {
            access_denied();
        }
        $this->oauth_model->destroy($id);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Client berhasil dihapus</div>');
        redirect('master/clients');
    }

    protected function validation($isUpdate = false)
    {
        $this->form_validation->set_rules('client_name', 'Nama', 'required');
        $this->form_validation->set_rules('redirect_uri', 'URL aplikasi', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('master/clients');
        }

        $data = $this->input->post(['client_name', 'redirect_uri']);
        if (!$isUpdate) {
            $data['client_secret'] = randomStr(40);
        }

        if ($isUpdate) {
            $data['updated_at'] = date(DATE_ATOM);
        }

        return $data;
    }
}
