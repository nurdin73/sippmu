<?php

class Dashboard extends Admin_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('sdm_model');
    }

    function index()
    {

        $this->session->set_userdata('top_menu', 'dashboard');
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
        $this->load->view('admin/dashboard', $data);
        $this->load->view('layout/footer', $data);
    }

    public function statistics($type = null, $periodeId = null)
    {
        $results = $this->sdm_model->statistics($type, $periodeId);
        header('Content-Type: application/json');
        echo json_encode($results);
    }
}
