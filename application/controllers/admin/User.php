<?php

class User extends Admin_Controller {

    function __construct() {
        parent::__construct();

        $this->config->load("hejoe");
        $this->load->library('Enc_lib');
        $this->load->model("user_model");
        $this->status = $this->config->item('status');

        header("Cache-Control: no cache");
    }

    function index() {
        if (!$this->rbac->hasPrivilege('user', 'can_view')) {
            access_denied();
        }
        $data['title'] = 'Staff Search';

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/user');
        $search = $this->input->post("search");
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $cabangx = $this->input->post('cabangx');
        $role = $this->input->post('role');
        $search_text = $this->input->post('search_text');
        
        
        $userRole = $this->user_model->getStaffRole();
        $data["role"] = $userRole;
        $data["role_id"] = "";
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabangx ? $cabangx : $cabang;
        $data['is_pusat'] = $is_pusat;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
            $data['cabangx'] = $cabangx ? $cabangx : 'all';
        }
        
        
        $resultlist = $this->user_model->searchFullText($data['cabangx'], $role, "", 1);
        $data['resultlist'] = $resultlist;
        
        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
                if ($this->form_validation->run() == FALSE) {

                    $data["resultlist"] = array();
                } else {
                    $data['searchby'] = "filter";
                    $role = $this->input->post('role');
                    $data['username'] = $this->input->post('username');
                    $data["role_id"] = $role;
                    $data['search_text'] = $search_text;
                    //$resultlist = $this->user_model->getEmployee($role, 1);
                    $resultlist = $this->user_model->searchFullText($data['cabangx'], $role, $search_text, 1);
                    $data['resultlist'] = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby'] = "text";
                $data['search_text'] = trim($search_text);
                $resultlist = $this->user_model->searchFullText($data['cabangx'], $role, $search_text, 1);
                $data['resultlist'] = $resultlist;
                $data['title'] = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header');
        $this->load->view('admin/user/usersearch', $data);
        $this->load->view('layout/footer');
    }

    function disableuserlist() {

        if (!$this->rbac->hasPrivilege('disable_user', 'can_view')) {
            access_denied();
        }

        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/user/disableuserlist');
        $data['title'] = 'Staff Search';
        $userRole = $this->user_model->getStaffRole();
        $data["role"] = $userRole;

        $search = $this->input->post("search");
        $search_text = $this->input->post('search_text');
        $resultlist = $this->user_model->searchFullText($search_text, 0);
        $data['resultlist'] = $resultlist;

        if (isset($search)) {
            if ($search == 'search_filter') {
                $this->form_validation->set_rules('role', 'Role', 'trim|required|xss_clean');
                if ($this->form_validation->run() == FALSE) {

                    $resultlist = array();
                    $data['resultlist'] = $resultlist;
                } else {
                    $data['searchby'] = "filter";
                    $role = $this->input->post('role');
                    $data['username'] = $this->input->post('username');

                    $data['search_text'] = $this->input->post('search_text');
                    $resultlist = $this->user_model->getEmployee($role, 0);
                    $data['resultlist'] = $resultlist;
                }
            } else if ($search == 'search_full') {
                $data['searchby'] = "text";
                $data['search_text'] = trim($this->input->post('search_text'));
                $resultlist = $this->user_model->searchFullText($search_text, 0);
                $data['resultlist'] = $resultlist;
                $data['title'] = 'Search Details: ' . $data['search_text'];
            }
        }
        $this->load->view('layout/header', $data);
        $this->load->view('admin/user/disableuser', $data);
        $this->load->view('layout/footer', $data);
    }

    function profile($idx) {
        if (!$this->rbac->hasPrivilege('user', 'can_view')) {
            access_denied();
        }

        $id = decrypt_url($idx);

        $this->load->model("setting_model");
        $data["idx"] = $idx;
        $data["id"] = $id;
        $data['title'] = 'Staff Details';
        $user_info = $this->user_model->getProfile($id);
        $userdata = $this->customlib->getUserData();
        $userid = $userdata['id'];
        
        $data['user_doc_id'] = $id;
        $data['user'] = $user_info;
        $data["status"] = $this->status;
        $roles = $this->role_model->get();
        $data["roles"] = $roles;

        $userlist = $this->user_model->get();
        $data['userlist'] = $userlist;

        $this->load->view('layout/header', $data);
        $this->load->view('admin/user/userprofile', $data);
        $this->load->view('layout/footer', $data);
    }

    public function download($user_id, $doc) {

        $this->load->helper('download');
        $filepath = "./uploads/user_documents/$user_id/" . $this->uri->segment(5);
        $data = file_get_contents($filepath);
        $name = $this->uri->segment(5);

        force_download($name, $data);
    }

    function doc_delete($id, $doc, $file) {
        $this->user_model->doc_delete($id, $doc, $file);
        $this->session->set_flashdata('msg', '<i class="fa fa-check-square-o" aria-hidden="true"></i> Document Deleted Successfully');
        redirect('admin/user/profile/' . $id);
    }

    
    function create() {
        $this->session->set_userdata('top_menu', 'HR');
        $this->session->set_userdata('sub_menu', 'admin/user');
        $roles = $this->role_model->get();
        $data["roles"] = $roles;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        
        $data["cabangs"] = $this->cabang_model->getCabang();

        $data['title'] = 'Add Staff';


        $this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
        $this->form_validation->set_rules('role', 'Role', 'trim|required|regex_match[/^[0-9]+$/]|xss_clean');
        $this->form_validation->set_rules('username', 'Username', 'trim|required|callback_username_check|xss_clean');
        $this->form_validation->set_rules('phone', 'phone', 'trim|regex_match[/^[0-9]{10}$/]|min_length[6]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('password', 'password', 'trim|required|regex_match[/^([a-zA-Z0-9]|\s)+$/]|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
       
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/user/usercreate', $data);
            $this->load->view('layout/footer', $data);
        } else {

            $username = $this->input->post("username");
            $cabang = $this->input->post("cabang");
            $role = $this->input->post("role");
            $name = $this->input->post("name");
            $gender = $this->input->post("gender");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
            $address = $this->input->post("address");
            $note = $this->input->post("note");
            $password = $this->input->post("password");
            //$password = $this->role->get_random_password($chars_min = 6, $chars_max = 6, $use_upper_case = false, $include_numbers = true, $include_special_chars = false);
            $data_insert = array(
                'password' => $this->enc_lib->passHashEnc($password),
                'username' => $username,
                'email' => $email,
                'cabang' => $cabang,
                'name' => $name,
                'phone' => $phone,
                'address' => $address,
                'note' => $note,
                'gender' => $gender,
                'is_active' => 1
            );

            if($dob != ""){
                $data_insert['dob'] = date('Y-m-d', $this->customlib->datetostrtotime($dob));
            }

            $role_array = array('role_id' => $this->input->post('role'), 'user_id' => 0);
            $insert_id = $this->user_model->batchInsert($data_insert, $role_array);
            $user_id = $insert_id;

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $insert_id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/user_images/" . $img_name);
                $data_img = array('id' => $user_id, 'image' => $img_name);
                $this->user_model->add($data_img);
            }


            //==========================



            $this->session->set_flashdata('msg', '<div class="alert alert-success">Staff Added Successfully</div>');

            redirect('admin/user');
        }
    }


    public function username_check($str)
    {
        if(empty($str)){
        $this->form_validation->set_message('username_check', 'Username field is required');
        return false;
        }else{
          
          $result = $this->user_model->valid_username($str);
          if($result == false){
            
            return false;
          }
            return true ;
        }
    }

    function edit($idx) {
        if (!$this->rbac->hasPrivilege('user', 'can_edit')) {
            access_denied();
        }
        $a = 0 ;
          
        $id = decrypt_url($idx);

        $sessionData = $this->session->userdata('admin');
        $userdata = $this->customlib->getUserData();
            
        
        $data['title'] = 'Edit User';
        $data['id'] = $idx;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        
        $userRole = $this->user_model->getStaffRole();
        $data["getStaffRole"] = $userRole;
        $data["cabangs"] = $this->cabang_model->getCabang();
        
        $data['title'] = 'Edit User';
        $user = $this->user_model->get($id);
        $data['user'] = $user;

            if($user["role_id"] == 7){
                $a = 0;
                if($userdata["email"] == $user["email"]){
                    $a = 1;    
                }
            }else{
                $a = 1 ;
            }

            if($a != 1){
                access_denied();

            }
        
        $this->form_validation->set_rules('name', 'Name', "trim|required|xss_clean");
        $this->form_validation->set_rules('role', 'Role', 'trim|required|regex_match[/^[0-9]+$/]|xss_clean');
        $this->form_validation->set_rules('phone', 'phone', 'trim|regex_match[/^[0-9]{10}$/]|min_length[6]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|xss_clean');
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
       
        $this->form_validation->set_rules(
                'email', 'Email', array('valid_email',
            array('check_exists', array($this->user_model, 'valid_email_id'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/user/useredit', $data);
            $this->load->view('layout/footer', $data);
        } else {
  
            $cabang = $this->input->post("cabang");
            $role = $this->input->post("role");
            $name = $this->input->post("name");
            $gender = $this->input->post("gender");
            $dob = $this->input->post("dob");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
          
            $address = $this->input->post("address");

            $note = $this->input->post("note");
            $password = $this->input->post("password");


            $data1 = array('id' => $id,
                'cabang' => $cabang,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                //'dob' => date('Y-m-d', $this->customlib->datetostrtotime($dob)),
                'address' => $address,
                'note' => $note,
                'gender' => $gender,
            );
              
            if(!empty($password)){
                $data1['password'] = $this->enc_lib->passHashEnc($password);
            }
            $insert_id = $this->user_model->add($data1);

            $role_id = $this->input->post("role");

            $role_data = array('user_id' => $id, 'role_id' => $role_id);

            $this->user_model->update_role($role_data);


            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/user_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => $img_name);
                $this->user_model->add($data_img);
                
//                echo $_FILES["file"]["tmp_name"];echo '<br>';
//                echo $_FILES['file']['name'];echo '<br>';
//                echo $img_name;
//                die();
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Record Updated Successfully</div>');
            redirect('admin/user/edit/' . $idx);
        }
    }

    function edit_profile($idx) {
        if (!$this->rbac->hasPrivilege('can_see_other_users_profile', 'can_edit')) {
            access_denied();
        }
        $a = 0 ;
          
        $id = decrypt_url($idx);

        $sessionData = $this->session->userdata('admin');
        $userdata = $this->customlib->getUserData();
            
        
        $data['title'] = 'Edit Profile';
        $data['id'] = $idx;
        $genderList = $this->customlib->getGender();
        $data['genderList'] = $genderList;
        
        $userRole = $this->user_model->getStaffRole();
        $data["getStaffRole"] = $userRole;
        $data["cabangs"] = $this->cabang_model->getCabang();
        
        $user = $this->user_model->get($id);
        $data['user'] = $user;

            if($user["role_id"] == 7){
                $a = 0;
                if($userdata["email"] == $user["email"]){
                    $a = 1;    
                }
            }else{
                $a = 1 ;
            }

            if($a != 1){
                access_denied();

            }
        
        $this->form_validation->set_rules('name', 'Name', "trim|required|regex_match[/^([a-zA-Z]|\.|\s)+$/]|xss_clean");
        //$this->form_validation->set_rules('role', 'Role', 'trim|required|regex_match[/^[0-9]+$/]|xss_clean');
        $this->form_validation->set_rules('phone', 'phone', 'trim|regex_match[/^[0-9]{10}$/]|min_length[6]|max_length[15]|xss_clean');
        $this->form_validation->set_rules('email', 'email', 'trim|valid_email|xss_clean');
        $this->form_validation->set_rules('gender', 'Gender', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
       
        $this->form_validation->set_rules(
                'email', 'Email', array('valid_email',
            array('check_exists', array($this->user_model, 'valid_email_id'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $this->load->view('layout/header', $data);
            $this->load->view('admin/user/useredit_profile', $data);
            $this->load->view('layout/footer', $data);
        } else {
  
            $cabang = $this->input->post("cabang");
            //$role = $this->input->post("role");
            $name = $this->input->post("name");
            $gender = $this->input->post("gender");
            $phone = $this->input->post("phone");
            $email = $this->input->post("email");
          
            $address = $this->input->post("address");

            $note = $this->input->post("note");
            
            $data1 = array('id' => $id,
                //'cabang' => $cabang,
                'name' => $name,
                'phone' => $phone,
                'email' => $email,
                'address' => $address,
                'note' => $note,
                'gender' => $gender,
            );
            $update = $this->user_model->add($data1);

            if (isset($_FILES["file"]) && !empty($_FILES['file']['name'])) {
                $fileInfo = pathinfo($_FILES["file"]["name"]);
                $img_name = $id . '.' . $fileInfo['extension'];
                move_uploaded_file($_FILES["file"]["tmp_name"], "./uploads/user_images/" . $img_name);
                $data_img = array('id' => $id, 'image' => $img_name);
                $this->user_model->add($data_img);
                
//                echo $_FILES["file"]["tmp_name"];echo '<br>';
//                echo $_FILES['file']['name'];echo '<br>';
//                echo $img_name;
//                die();
            }

            $this->session->set_flashdata('msg', '<div class="alert alert-success">Record Updated Successfully</div>');
            redirect('admin/user/profile/' . $idx);
        }
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('user', 'can_delete')) {
            access_denied();
        }

                $a = 0 ;
          $sessionData = $this->session->userdata('admin');
            $userdata = $this->customlib->getUserData();
            $user = $this->user_model->get($id);
             if($user["role_id"] == 7){
                $a = 0;
                if($userdata["email"] == $user["email"]){
                    $a = 1;    
                }
            }else{
                $a = 1 ;
            }
        
        if($a != 1){
            access_denied();
        }
        $data['title'] = 'Users List';
        $this->user_model->remove($id);
        redirect('admin/user');
    }

    function disableuser($id) {
        if (!$this->rbac->hasPrivilege('disable_user', 'can_view')) {

            access_denied();
        }
        $a = 0 ;
          $sessionData = $this->session->userdata('admin');
            $userdata = $this->customlib->getUserData();
            $user = $this->user_model->get($id);
             if($user["role_id"] == 7){
                $a = 0;
                if($userdata["email"] == $user["email"]){
                    $a = 1;    
                }
            }else{
                $a = 1 ;
            }
        
        if($a != 1){
            access_denied();
        }
        $this->user_model->disableuser($id);
        redirect('admin/user/profile/' . $id);
    }

    function enableuser($id) {

        $a = 0 ;
          $sessionData = $this->session->userdata('admin');
            $userdata = $this->customlib->getUserData();
            $user = $this->user_model->get($id);
             if($user["role_id"] == 7){
                $a = 0;
                if($userdata["email"] == $user["email"]){
                    $a = 1;    
                }
            }else{
                $a = 1 ;
            }
        
        if($a != 1){
            access_denied();
        }
        $this->user_model->enableuser($id);
        redirect('admin/user/profile/' . $id);
    }

    function getEmployeeByRole() {

        $role = $this->input->post("role");

        $data = $this->user_model->getEmployee($role);

        echo json_encode($data);
    }

    function dateDifference($date_1, $date_2, $differenceFormat = '%a') {
        $datetime1 = date_create($date_1);
        $datetime2 = date_create($date_2);

        $interval = date_diff($datetime1, $datetime2);

        return $interval->format($differenceFormat) + 1;
    }

    function permission($id) {
        $data['title'] = 'Add Role';
        $data['id'] = $id;
        $user = $this->user_model->get($id);
        $data['user'] = $user;
        $userpermission = $this->userpermission_model->getUserPermission($id);
        $data['userpermission'] = $userpermission;

        if ($this->input->server('REQUEST_METHOD') == "POST") {
            $user_id = $this->input->post('user_id');
            $prev_array = $this->input->post('prev_array');
            if (!isset($prev_array)) {
                $prev_array = array();
                ;
            }
            $module_perm = $this->input->post('module_perm');
            $delete_array = array_diff($prev_array, $module_perm);
            $insert_diff = array_diff($module_perm, $prev_array);
            $insert_array = array();
            if (!empty($insert_diff)) {

                foreach ($insert_diff as $key => $value) {
                    $insert_array[] = array(
                        'user_id' => $user_id,
                        'permission_id' => $value
                    );
                }
            }

            $this->userpermission_model->getInsertBatch($insert_array, $user_id, $delete_array);

            $this->session->set_flashdata('msg', '<div class="alert alert-success text-left">Record updated successfully</div>');
            redirect('admin/user');
        }

        $this->load->view('layout/header');
        $this->load->view('admin/user/permission', $data);
        $this->load->view('layout/footer');
    }



    function change_password($id){

        $sessionData = $this->session->userdata('admin');
        $userdata = $this->customlib->getUserData();

        $this->form_validation->set_rules('current_pass', 'Old password', 'trim|required|xss_clean');
        $this->form_validation->set_rules('new_pass', 'New password', 'trim|required|xss_clean|matches[confirm_pass]');
        $this->form_validation->set_rules('confirm_pass', 'Confirm password', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'current_pass' => form_error('current_pass'),
                'new_pass' => form_error('new_pass'),
                'confirm_pass' => form_error('confirm_pass'),
                
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');      
        }else{

            $sessionData = $this->session->userdata('admin');
            $userdata = $this->customlib->getUserData();
            $data_array = array(
                'current_pass' => $this->input->post('current_pass'),
                'new_pass' => md5($this->input->post('new_pass')),
                'user_id' => $sessionData['id'],
                'user_email' => $sessionData['email'],
                'user_name' => $sessionData['username']
            );
            $newdata = array(
                'id' => $sessionData['id'],
                'password' => $this->enc_lib->passHashEnc($this->input->post('new_pass'))
            );
            $check = $this->enc_lib->passHashDyc($this->input->post('current_pass'), $userdata["password"]);

            $query1 = $this->admin_model->checkOldPass($data_array);

            if ($query1) {

                if ($check) {
                    $query2 = $this->admin_model->saveNewPass($newdata);
                    if ($query2) {
                        $array = array('status' => 'success', 'error' => '', 'message' => "Password Changed Successfully");
                    }else{
                        $array = array('status' => 'fail', 'error' => '', 'message' => "Password Not Changed");
                    }
                } else {
                    $array = array('status' => 'fail', 'error' => '', 'message' => "Invalid current password");
                }
            } else {

                $array = array('status' => 'fail', 'error' => '', 'message' => "Invalid current password");
            }

        } 
  
        echo json_encode($array);   
    }

}

?>