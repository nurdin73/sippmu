<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Userroles_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function getUserRoles($userid) {
        $this->db->select('user_roles.*,roles.name, roles.is_system, roles.is_superadmin');
        $this->db->from('user_roles');
        $this->db->join('roles', 'roles.id=user_roles.role_id', 'inner');
        $this->db->where('user_roles.user_id', $userid);
        $query = $this->db->get();
        return $query->result(); 
    }

}
