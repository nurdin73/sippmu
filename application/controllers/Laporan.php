<?php

class Laporan extends Admin_Controller
{
    public function index()
    {
        if (!$this->rbac->hasPrivilege('laporan', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Laporan');

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
        $this->load->view('laporan/rekapitulasi', $data);
        $this->load->view('layout/footer', $data);
    }
}
