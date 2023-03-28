<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Program_model extends CI_model {

    public function valid_program($str) {
        $kode = $this->input->post('kode');
        $act = $this->input->post('act');
        
        if ($act == 'add' && $this->check_program_exists($kode)) {
            $this->form_validation->set_message('check_exists', 'Record '.$kode.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_program_exists($kode) {

        if ($kode != 0) {
            $data = array('kode' => $kode,'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_program');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } 
        
        return FALSE;
    }

    function getProgram($id = null) {

        if (!empty($id)) {
            $this->db->where('is_deleted', 2);
            $query = $this->db->where("kode", $id)->get('m_program');
            return $query->row_array();
        } else {
            $this->db->where('is_deleted', 2);
            $this->db->order_by('kode', 'ASC');
            $query = $this->db->get("m_program");
            return $query->result_array();
        }
    }

    public function addProgram($data) {

        $this->db->insert('m_program', $data);
        //return $this->db->insert_id();
        return $data['kode'];
    }
    
    public function editProgram($data, $kode) {

        $this->db->where('kode', $kode);
        $this->db->update('m_program', $data);
        
        return $kode;
    }

    function deleteProgram($kode) {
        $userinput = $this->customlib->getSessionUsername();
        $data = array( 
            'is_deleted' => 1,
            'deleted_by' => $userinput,
            'deleted_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('kode', $kode);
        $this->db->update('m_program', $data);
        //$this->db->where("kode", $kode)->delete("m_program");
    }

}

?>