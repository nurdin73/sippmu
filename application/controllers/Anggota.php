<?php

class Anggota extends Admin_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->model('oauth_model');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_view')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Keanggotaan');
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
        $date = date('Y-m-d');
        $this->load->view('layout/header', $data);
        $this->load->view('admin/anggota/index', $data);
        $this->load->view('layout/footer', $data);
    }

    public function datatables()
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_view')) {
            access_denied();
        }
        $userdata = $this->customlib->getUserData();
        $results = $this->user_model->getUserByUnit($userdata['cabang']);
        header('Content-Type: application/json');
        echo $results;
    }

    public function create()
    {
        $this->session->set_userdata('top_menu', 'Keanggotaan');
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
        $userRole = $this->user_model->getStaffRole();
        $getStaffRole = array_filter($userRole, function ($q) {
            return strtolower($q['type']) != 'super admin';
        });
        $getStaffRole = array_values($getStaffRole);
        $data["getStaffRole"] = $getStaffRole;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $this->load->view('layout/header', $data);
        $this->load->view('admin/anggota/create', $data);
        $this->load->view('layout/footer', $data);
    }

    public function insert()
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_view')) {
            access_denied();
        }
        $data = $this->validation();
        $role = ['role_id' => $data['role']];
        unset($data['role']);
        $userId = $this->user_model->batchInsert($data, $role);
        $image = $this->saveImage($userId);
        $update = [
            'id' => $userId,
            'image' => $image
        ];
        $this->user_model->add($update);
        $this->session->set_flashdata('message', "<div class='alert alert-success'>Anggota berhasil dibuat</div>");
        redirect('anggota');
    }

    public function edit($id)
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_edit')) {
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'Keanggotaan');
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
        $userRole = $this->user_model->getStaffRole();
        $user = $this->user_model->get($id, $userdata['cabang']);
        if (!$user) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>User not found</div>");
            redirect('anggota');
        }
        $data['user'] = $user;
        $data['title'] = 'Edit Anggota';
        $getStaffRole = array_filter($userRole, function ($q) {
            return strtolower($q['type']) != 'super admin';
        });
        $getStaffRole = array_values($getStaffRole);
        $data["getStaffRole"] = $getStaffRole;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        $data['apps'] = $this->oauth_model->getAllClients();
        $data['permissions'] = $this->oauth_model->getPermissionClientByuserId($id);
        $this->load->view('layout/header', $data);
        $this->load->view('admin/anggota/edit', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update($id)
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_edit')) {
            access_denied();
        }
        $a = 0;

        $id = decrypt_url($id);
        $data = $this->validation(true, $id);
        $user = $this->user_model->get($id);

        if ($user['email'] != $data['email'] && $this->user_model->getByEmail($data['email'])) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>Email sudah ada!</div>");
            redirect("anggota/edit/$id");
        }

        $data['id'] = $id;

        if ($user["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $user["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }
        if (isset($_FILES["image"]) && !empty($_FILES['image']['name'])) {
            $fileInfo = pathinfo($_FILES["image"]["name"]);
            $img_name = $id . '.' . $fileInfo['extension'];
            $path  = BASEPATH . "../uploads/user_images/" . $img_name;
            if (!in_array($fileInfo['extension'], ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Format file tidak sesuai!</div>");
                redirect("anggota/edit/$id");
            }
            if (byteToMega($_FILES['image']['size']) > '2') {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>File terlalu besar. silahkan pilih file maksimal 2MB!</div>");
                redirect("anggota/edit/$id");
            }
            move_uploaded_file($_FILES["image"]["tmp_name"], $path);
            $data['image'] = $img_name;
        }
        $roleData = [
            'user_id' => $id,
            'role_id' => $data['role']
        ];
        unset($data['role']);
        $insert_id = $this->user_model->add($data);

        // $role_data = array('user_id' => $id, 'role_id' => $data['role']);

        $this->user_model->update_role($roleData);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Record Updated Successfully</div>');
        redirect("anggota/edit/$id");
    }

    public function update_password($idx)
    {
        if (!$this->rbac->hasPrivilege('management_keanggotaan', 'can_edit')) {
            access_denied();
        }

        $id = decrypt_url($idx);

        $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Confirm password', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect("anggota/edit/$id");
        }

        $data = $this->input->post(['password']);
        $data['id'] = $id;
        $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
        $this->user_model->add($data);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Record Updated Successfully</div>');
        redirect("anggota/edit/$id");
    }

    public function permission_app($idx)
    {
        $id = decrypt_url($idx);
        $apps = $this->input->post('app');
        if (count($apps) > 0) {
            $this->oauth_model->removePermissionClientByUserId($id);
            foreach ($apps as $app) {
                $this->oauth_model->addPermissionClient($app, $id);
            }
        }

        $this->session->set_flashdata('message', '<div class="alert alert-success">Record Updated Successfully</div>');
        redirect("anggota/edit/$id");
    }

    public function destroy($id)
    {
        if (!$this->rbac->hasPrivilege('user', 'can_delete')) {
            access_denied();
        }

        $a = 0;
        $sessionData = $this->session->userdata('admin');
        $userdata = $this->customlib->getUserData();
        $user = $this->user_model->get($id);
        if ($user["role_id"] == 7) {
            $a = 0;
            if ($userdata["email"] == $user["email"]) {
                $a = 1;
            }
        } else {
            $a = 1;
        }

        if ($a != 1) {
            access_denied();
        }
        $this->user_model->remove($id);
        redirect('anggota');
    }

    public function validation($isUpdate = false, $id = null)
    {
        $this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
        $this->form_validation->set_rules('username', 'Username', "trim|required|xss_clean");
        $this->form_validation->set_rules('phone', 'phone', 'trim|min_length[6]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        if (!$isUpdate) {
            $this->form_validation->set_rules('password', 'Password', 'trim|required|xss_clean|matches[confirm]');
            $this->form_validation->set_rules('confirm', 'Confirm password', 'trim|required|xss_clean');
        }
        $this->form_validation->set_rules('nbm', 'NBM', 'trim|required|xss_clean');
        $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
        if ($this->form_validation->run() == false) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            if ($isUpdate) {
                redirect("anggota/edit/$id");
            } else {
                redirect("anggota/create");
            }
        }
        $data = $this->input->post(['name', 'username', 'phone', 'email', 'gender', 'nbm', 'role', 'password', 'address', 'note']);
        if ($isUpdate) {
            unset($data['password']);
        }
        if (!$isUpdate && $this->user_model->getByEmail($data['email'])) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>Email sudah ada!</div>");
            redirect("anggota/create");
        }

        $userdata = $this->customlib->getUserData();
        $data['cabang'] = $userdata['cabang'];
        $data['is_active'] = 1;
        return $data;
    }


    public function saveImage($userId)
    {
        $nameImage = null;
        if (isset($_FILES["image"]) && !empty($_FILES['image']['name'])) {
            $fileInfo = pathinfo($_FILES["image"]["name"]);
            $nameImage = $userId . '.' . $fileInfo['extension'];
            $path  = BASEPATH . "../uploads/user_images/" . $nameImage;
            if (!in_array($fileInfo['extension'], ['png', 'jpg', 'jpeg', 'gif', 'webp'])) {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>Format file tidak sesuai!</div>");
                redirect("anggota/create");
            }
            if (byteToMega($_FILES['image']['size']) > '2') {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>File terlalu besar. silahkan pilih file maksimal 2MB!</div>");
                redirect("anggota/create");
            }
            move_uploaded_file($_FILES["image"]["tmp_name"], $path);
            $data['image'] = $nameImage;
        }
        return $nameImage;
    }
}