<?php

class User_model extends CI_Model {

    function __construct() {
        parent::__construct();
    }

    public function get($id = null) {

        $this->db->select('users.*,roles.name as user_type,roles.id as role_id')->from('users')->join("user_roles", "user_roles.user_id = users.id", "left")->join("roles", "user_roles.role_id = roles.id", "left");


        if ($id != null) {
            $this->db->where('users.id', $id);
        } else {
            $this->db->where('users.is_active', 1);
            $this->db->order_by('users.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function getAll($id = null, $is_active = null) {

        $this->db->select("users.*,roles.id as role_id, roles.name as role");
        $this->db->from('users');
        $this->db->join('user_roles', "user_roles.user_id = users.id", "left");
        $this->db->join('roles', "roles.id = user_roles.role_id", "left");

        if ($id != null) {
            $this->db->where('users.id', $id);
        } else {
            if ($is_active != null) {

                $this->db->where('users.is_active', $is_active);
            }
            $this->db->order_by('users.id');
        }
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function add($data) {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('users', $data);
        } else {
            $this->db->insert('users', $data);
            return $this->db->insert_id();
        }
    }

    public function update($data) {
        $this->db->where('id', $data['id']);
        $query = $this->db->update('users', $data);
        if ($query) {
            return true;
        } else {
            return false;
        }
    }

    public function getByVerificationCode($ver_code) {
        $condition = "verification_code =" . "'" . $ver_code . "'";
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where($condition);
        $this->db->limit(1);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return false;
        }
    }

    public function batchInsert($data, $roles = array()) {

        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        $this->db->insert('users', $data);
        $user_id = $this->db->insert_id();
        $roles['user_id'] = $user_id;
        $this->db->insert_batch('user_roles', array($roles));

        $this->db->trans_complete();

        if ($this->db->trans_status() === FALSE) {

            $this->db->trans_rollback();
            return FALSE;
        } else {

            $this->db->trans_commit();
            return $user_id;
        }
    }

    public function adddoc($data) {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('user_documents', $data);
        } else {
            $this->db->insert('user_documents', $data);
            return $this->db->insert_id();
        }
    }

    public function remove($id) {

        $this->db->where('id', $id);
        $this->db->delete('users');

    }

    public function valid_username($str) {
        $name = $this->input->post('name');
        $id = $this->input->post('username');
        $user_id = $this->input->post('editid');

        if((!isset($id)))  {
            $id = 0;
        }
        if (!isset($user_id)) {
            $user_id = 0;
        }

        if ($this->check_data_exists($name, $id, $user_id)) {
            $this->form_validation->set_message('username_check', 'Record already exists');
            return FALSE;
           
        } else {
           
            return TRUE;
        }
       
    }

    function check_data_exists($name, $id, $user_id) {

        if ($user_id != 0) {
            $data = array('id != ' => $user_id, 'username' => $id);
            $query = $this->db->where($data)->get('users');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('username', $id);
            $query = $this->db->get('users');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function valid_email_id($str) {
        $email = $this->input->post('email');
        $id = $this->input->post('username');
        $user_id = $this->input->post('editid');

        if (!isset($id)) {
            $id = 0;
        }
        if (!isset($user_id)) {
            $user_id = 0;
        }

        if ($this->check_email_exists($email, $id, $user_id)) {
            $this->form_validation->set_message('check_exists', 'Email already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_email_exists($email, $id, $user_id) {

        if ($user_id != 0) {
            $data = array('id != ' => $user_id, 'email' => $email);
            $query = $this->db->where($data)->get('users');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('email', $email);
            $query = $this->db->get('users');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    public function getStaffRole($id = null) {

         $userdata = $this->customlib->getUserData();
        if($userdata["role_id"] != 1){
            $this->db->where("id !=", 1);
        }

        $this->db->select('roles.id,roles.name as type')->from('roles');
        if ($id != null) {
            $this->db->where('id', $id);
        } else {
            $this->db->order_by('id');
        }
        $this->db->where("is_active", 1);
        $query = $this->db->get();
        if ($id != null) {
            return $query->row_array();
        } else {
            return $query->result_array();
        }
    }

    public function alloted_leave($user_id) {


        $query2 = $this->db->select('sum(alloted_leave) as alloted_leave')->where(array('user_id' => $user_id))->get("user_leave_details");

        return $query2->result_array();
    }

    function getEmployee($role, $active = 1) {

        $addwhere = '';
        $userdata = $this->customlib->getUserData();
        if($userdata["role_id"] != 1){
            $this->db->where("users.id !=", 1);
        }
        
        $query = $this->db->select("users.*,m_cabang.nama as cabang,roles.name as user_type")
                ->join('user_roles', "user_roles.user_id = users.id", "left")
                ->join('roles', "roles.id = user_roles.role_id", "left")
                ->join('m_cabang', "m_cabang.id = users.cabang", "left")
                ->where("users.is_active", $active)
                ->where("roles.name", $role)
                ->get("users");

        return $query->result_array();
    }

    function getEmployeeByRoleID($role, $active = 1) {

        $query = $this->db->select("users.*,m_cabang.nama as cabang, roles.id as role_id, roles.name as role")
                ->join('user_roles', "user_roles.user_id = users.id", "left")
                ->join('roles', "roles.id = user_roles.role_id", "left")
                ->join('m_cabang', "m_cabang.id = users.cabang", "left")
                ->where("users.is_active", $active)
                ->where("roles.id", $role)
                ->get("users");


        return $query->result_array();
    }

    function getStaffId($username) {

        $data = array('username' => $username);
        $query = $this->db->select('id')->where($data)->get("users");


        return $query->row_array();
    }

    function getProfile($id) {

        $this->db->select('users.*,user_roles.role_id, m_cabang.nama as cabang,roles.name as user_type');
        $this->db->join("m_cabang", "m_cabang.id = users.cabang", "left");
        $this->db->join("user_roles", "user_roles.user_id = users.id", "left");
        $this->db->join("roles", "user_roles.role_id = roles.id", "left");

        $this->db->where("users.id", $id);

        $this->db->from('users');
        $query = $this->db->get();

        return $query->row_array();
    }

    public function searchFullText($cabang, $role, $searchterm, $active) {
        
        $addwhere = '';$addwhere2 = '';$addwhere3 = '';
        $userdata = $this->customlib->getUserData();
        if($userdata["role_id"] != 1){
            $addwhere = " AND users.id != 1";
        }
        if($role){
            $addwhere2 = " AND roles.name = '".$role."'";
        }
        if($cabang == 'all'){
            $addwhere3 = " ";
        }else{
            $addwhere3 = " AND users.cabang = '".$cabang."'";
        }
        $query = $this->db->query("SELECT users.*, m_cabang.nama as cabang, roles.name as user_type  
            FROM users
            LEFT JOIN user_roles ON user_roles.user_id = users.id 
            LEFT JOIN roles ON user_roles.role_id = roles.id 
            LEFT JOIN m_cabang ON m_cabang.id = users.cabang 
            WHERE  users.is_active = '$active' ".$addwhere."
            ".$addwhere3." ".$addwhere2."
            and (users.name LIKE '%$searchterm%' ESCAPE '!' OR users.username LIKE '%$searchterm%' ESCAPE '!' OR users.email LIKE '%$searchterm%' ESCAPE '!' OR users.phone LIKE '%$searchterm%' ESCAPE '!')");

        return $query->result_array();
    }

    public function searchByEmployeeId($username) {

        $this->db->select('*');
        $this->db->from('users');
        $this->db->like('users.username', $username);
        $this->db->like('users.is_active', 1);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function getStaffDoc($id) {

        $this->db->select('*');
        $this->db->from('user_documents');
        $this->db->where('user_id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function doc_delete($id, $doc, $file) {

        if ($doc == 1) {

            $data = array('resume' => '',);
        } else
        if ($doc == 2) {

            $data = array('joining_letter' => '',);
        } else
        if ($doc == 3) {

            $data = array('resignation_letter' => '',);
        } else
        if ($doc == 4) {

            $data = array('other_document_name' => '', 'other_document_file' => '',);
        }
        unlink(BASEPATH . "uploads/user_documents/" . $file);
        $this->db->where('id', $id)->update("users", $data);
    }

    public function disableuser($id) {

        $data = array('is_active' => 0);

        $query = $this->db->where("id", $id)->update("users", $data);
    }

    public function enableuser($id) {

        $data = array('is_active' => 1);

        $query = $this->db->where("id", $id)->update("users", $data);
    }

    public function getByEmail($email) {
        $this->db->select('*');
        $this->db->from('users');
        $this->db->where('email', $email);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function getByUsername($username) {
        $this->db->select('users.*, m_cabang.nama as cabang_name, m_cabang.is_pusat as is_pusat');
        $this->db->from('users');
        $this->db->join("m_cabang", "m_cabang.id = users.cabang", "left");
        $this->db->where('users.username', $username);
        $query = $this->db->get();
        if ($query->num_rows() == 1) {
            return $query->row();
        } else {
            return FALSE;
        }
    }

    public function checkLogin($data) {

        //$record = $this->getByEmail($data['email']);
        $record = $this->getByUsername($data['username']);
        //print_r($record);
        if ($record) {
            $pass_verify = $this->enc_lib->passHashDyc($data['password'], $record->password);
            if ($pass_verify) {
                $roles = $this->userroles_model->getUserRoles($record->id);

                $record->roles = array(
                    $roles[0]->name => $roles[0]->role_id,
                    'is_admin'      => $roles[0]->is_system,
                    'is_superadmin'      => $roles[0]->is_superadmin,
                );

                return $record;
            }
        }
        return false;
    }

    function getStaffbyrole($id) {

        $this->db->select('users.*,user_roles.role_id, m_cabang.nama as cabang,roles.name as user_type');
        $this->db->join("m_cabang", "m_cabang.id = users.cabang", "left");
        $this->db->join("user_roles", "user_roles.user_id = users.id", "left");
        $this->db->join("roles", "user_roles.role_id = roles.id", "left");
        $this->db->where("user_roles.role_id", $id);
        $this->db->where("users.is_active", "1");
        $this->db->from('users');
        $query = $this->db->get();

        return $query->result_array();
    }

    public function searchNameLike($searchterm) {
        $this->db->select('users.*')->from('users');
        $this->db->group_start();
        $this->db->like('users.name', $searchterm);
        $this->db->group_end();
        $this->db->order_by('users.id');

        $query = $this->db->get();
        return $query->result_array();
    }

    public function update_role($role_data) {

        $this->db->where("user_id", $role_data["user_id"])->update("user_roles", $role_data);
    }

}

?>