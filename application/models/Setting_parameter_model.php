<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Setting_parameter_model extends CI_model {

    public function valid_data($str) {
        $param_code = $this->input->post('param_code');
        $act = $this->input->post('act');
        
        if ($act == 'add' && $this->check_exists($param_code)) {
            $this->form_validation->set_message('check_exists', 'Record '.$param_code.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_exists($param_code) {

        if ($param_code != 0) {
            $data = array('param_code' => $param_code,'is_deleted' => 2);
            $query = $this->db->where($data)->get('setting_parameter');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } 
        
        return FALSE;
    }

    function getSetting_parameter($code = null) {

        if (!empty($code)) {
            $this->db->where('is_deleted', 2);
            $query = $this->db->where("param_code", $code)->get('setting_parameter');
            return $query->row_array();
        } else {
            $this->db->where('is_deleted', 2);
            $this->db->order_by('param_code', 'ASC');
            $query = $this->db->get("setting_parameter");
            return $query->result_array();
        }
    }

    function getParameter($code = null, $val_array='val') {

        if (!empty($code)) {
            $this->db->where('is_deleted', 2);
            $query = $this->db->where("param_code", $code)->get('setting_parameter');
            
            if($val_array == 'val'){
                $data = $query->row_array();
                return $data['param_value'];
            }
            
            if($val_array == 'array'){
                return $query->row_array();
            }
        } 
        
        return false;
    }

    public function addSetting_parameter($data) {

        $this->db->insert('setting_parameter', $data);
        //return $this->db->insert_id();
        return $data['param_code'];
    }
    
    public function editSetting_parameter($data, $param_code) {

        $this->db->where('param_code', $param_code);
        $this->db->update('setting_parameter', $data);
        
        return $param_code;
    }

    function deleteSetting_parameter($param_code) {
        $userinput = $this->customlib->getSessionUsername();
        $data = array( 
            'is_deleted' => 1,
            'deleted_by' => $userinput,
            'deleted_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('param_code', $param_code);
        $this->db->update('setting_parameter', $data);
        //$this->db->where("param_code", $param_code)->delete("setting_parameter");
    }

    function getKodeRekening($id=null) {

        if (isset($id)) {
            
            $this->db->where("id", $id);
            $query = $this->db->get('m_akun');
            return $query->row_array();
            
        } else {
        
            $this->db->order_by('kode', 'ASC');
            $this->db->where('is_deleted', 2);
            $this->db->where('level', '5');
            $query = $this->db->select('*')->get('m_akun');
            return $query->result_array();
        }
    }

}

?>