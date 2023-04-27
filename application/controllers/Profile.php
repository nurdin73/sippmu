<?php

class Profile extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('history_jabatan_model');
        $this->load->model('sdm_model');
        $this->load->model('periode_model');
        $this->load->model('user_model');
        $this->load->model('jabatan_model');
    }

    function index()
    {
        $this->session->set_userdata('top_menu', 'Profile');
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
        $date = date('Y-m-d');
        $data['periode'] = $this->periode_model->findPeriodeByDate($date);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/profile/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function histories($unit_id = null)
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $results = $this->history_jabatan_model->all($unit_id);
        header('Content-Type: application/json');
        echo $results;
    }

    public function getByPeriode($periode_id = null, $unit_id)
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $results = $this->history_jabatan_model->byPeriode($periode_id, $unit_id);
        header('Content-Type: application/json');
        echo json_encode($results);
    }

    public function add()
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $attr = $this->validation();
        $history = $attr;
        unset($history['unit_id']);
        $this->history_jabatan_model->create($history);
        $check = $this->sdm_model->getByUserId($attr['user_id']);
        if ($check) {
            $this->sdm_model->update($check['id'], [
                'jabatan_id' => $attr['jabatan_id']
            ]);
        } else {
            $sdm = [
                'user_id' => $attr['user_id'],
                'jabatan_id' => $attr['jabatan_id'],
                'unit_id' => $attr['unit_id'],
                'created_by' => $attr['created_by'],
                'updated_by' => $attr['updated_by'],
                'kode' => $attr['kode']
            ];
            $this->sdm_model->create($sdm);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil dibuat</div>');
        redirect('profile');
    }

    function update($id)
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $attr = $this->validation(true);
        $history = $attr;
        unset($history['unit_id']);
        $find = $this->history_jabatan_model->get($id);
        $this->history_jabatan_model->destroy($id);
        $this->history_jabatan_model->create($history);
        // $this->sdm_model->update($attr['user_id'], [
        //     'sdm_jabatan' => $attr['jabatan_id']
        // ]);
        // $this->sdm_model->update($find['id_sdm'], [
        //     'sdm_jabatan' => null
        // ]);
        $check = $this->sdm_model->getByUserId($attr['user_id']);
        $userPrev = $this->sdm_model->getByUserId($find['user_id']);
        $this->sdm_model->update($userPrev['id'], [
            'jabatan_id' => null
        ]);
        if ($check) {
            $this->sdm_model->update($attr['user_id'], [
                'jabatan_id' => $attr['jabatan_id']
            ]);
        } else {
            $sdm = [
                'user_id' => $attr['user_id'],
                'jabatan_id' => $attr['jabatan_id'],
                'unit_id' => $attr['unit_id'],
                'created_by' => $attr['created_by'],
                'updated_by' => $attr['updated_by'],
                'kode' => $attr['kode']
            ];
            $this->sdm_model->create($sdm);
        }
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil diupdate</div>');
        redirect('profile');
    }

    function get($id)
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $attr = $this->history_jabatan_model->get($id);
        if (!$attr) {
            header("HTTP/1.1 404 Not Found");
        } else {
            header('Content-Type: application/json');
            echo json_encode($attr);
        }
    }

    public function destroy($id)
    {
        if (!$this->rbac->hasPrivilege('profile', 'can_view')) {
            access_denied();
        }
        $attr = $this->history_jabatan_model->get($id);
        if (!$attr) {
            header("HTTP/1.1 404 Not Found");
        }
        $this->history_jabatan_model->destroy($id);
        $this->sdm_model->update($attr['sdm_id'], [
            'jabatan_id' => null
        ]);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Jabatan berhasil dihapus</div>');
        redirect('profile');
    }

    function validation($isUpdate = false)
    {
        $this->form_validation->set_rules('user_id', 'SDM', 'required');
        $this->form_validation->set_rules('jabatan_id', 'Jabatan', 'required');
        $this->form_validation->set_rules('periode_id', 'Periode', 'required');
        $this->form_validation->set_rules('unit_id', 'Unit', 'required');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect('profile');
        }
        $data = $this->input->post(['user_id', 'jabatan_id', 'periode_id', 'unit_id']);
        $user = $this->user_model->getProfile($data['user_id']);
        $jabatan = $this->jabatan_model->get($data['jabatan_id']);
        $data['kode'] = $jabatan['kode'] . '-' . ($user['nbm'] ?? 'N/A');
        // $data['is_active'] = $this->input->post('status') ?? false;
        if (!$isUpdate) {
            $data['created_by'] = $this->customlib->getStaffID();
        }
        $data['updated_by'] = $this->customlib->getStaffID();
        return $data;
    }
}
