<?php

class Myprofile extends Admin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->config->load("hejoe");
        $this->load->library('Enc_lib');
        $this->load->model("user_model");
        $this->load->model('oauth_model');
        $this->status = $this->config->item('status');
    }

    public function index()
    {
        if (!$this->rbac->hasPrivilege('user', 'can_view')) {
            access_denied();
        }

        $id = $this->customlib->getStaffID();


        $this->load->model("setting_model");
        $data["id"] = $id;
        $data['title'] = 'Staff Details';
        $user_info = $this->user_model->getProfile($id);
        $userdata = $this->customlib->getUserData();
        $userid = $userdata['id'];

        $data['user_doc_id'] = $id;
        $data['user'] = $user_info;
        $data["status"] = $this->status;
        // $roles = $this->role_model->get();
        // $data["roles"] = $roles;

        $userlist = $this->user_model->get();
        $data['userlist'] = $userlist;

        $userRole = $this->user_model->getStaffRole();
        $data["getStaffRole"] = $userRole;
        $data["cabangs"] = $this->cabang_model->getCabang();
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/user/my-profile', $data);
        $this->load->view('layout/footer', $data);
    }

    public function update()
    {
        if (!$this->rbac->hasPrivilege('user', 'can_edit')) {
            access_denied();
        }
        $a = 0;

        $id = $this->customlib->getStaffID();

        $this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
        $this->form_validation->set_rules('username', 'Username', "trim|required|xss_clean");
        $this->form_validation->set_rules('phone', 'phone', 'trim|min_length[6]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        $this->form_validation->set_rules('nbm', 'NBM', 'trim|required|xss_clean');

        // $this->form_validation->set_rules(
        //     'email',
        //     'Email',
        //     array(
        //         'valid_email',
        //         array('check_exists', array($this->user_model, 'valid_email_id'))
        //     )
        // );

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect("myprofile");
        }

        $data = $this->input->post(['username', 'name', 'gender', 'phone', 'email', 'address', 'note', 'nbm']);

        $user = $this->user_model->get($id);

        if ($user['email'] != $data['email'] && $this->user_model->getByEmail($data['email'])) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>Email sudah ada!</div>");
            redirect("myprofile");
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
                redirect("myprofile");
            }
            if (byteToMega($_FILES['image']['size']) > '2') {
                $this->session->set_flashdata('message', "<div class='alert alert-danger'>File terlalu besar. silahkan pilih file maksimal 2MB!</div>");
                redirect("myprofile");
            }
            move_uploaded_file($_FILES["image"]["tmp_name"], $path);
            $data['image'] = $img_name;
        }

        $this->user_model->add($data);

        $this->session->set_flashdata('message', '<div class="alert alert-success">Record Updated Successfully</div>');
        redirect("myprofile");
    }

    public function update_password()
    {
        if (!$this->rbac->hasPrivilege('user', 'can_edit')) {
            access_denied();
        }

        $id = $this->customlib->getStaffID();

        $this->form_validation->set_rules('old_password', 'Old password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_password', 'New Password', 'trim|required|xss_clean|matches[password_confirmation]');
        $this->form_validation->set_rules('password_confirmation', 'Confirm  password', 'trim|required|xss_clean');

        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>" . validation_errors() . "</div>");
            redirect("myprofile");
        }

        $data = $this->input->post(['old_password', 'new_password']);
        $data['id'] = $id;
        $user = $this->user_model->get($id);
        if (!password_verify($data['old_password'], $user['password'])) {
            $this->session->set_flashdata('message', "<div class='alert alert-danger'>Old password not match!</div>");
            redirect("myprofile");
        }
        unset($data['old_password']);
        $data['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
        unset($data['new_password']);
        $this->user_model->add($data);
        $this->session->set_flashdata('message', '<div class="alert alert-success">Record Updated Successfully</div>');
        redirect("myprofile");
    }
}
