<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Tipe_jurnal_model extends CI_model {

    public function valid_tipe_jurnal($str) {
        $kode = $this->input->post('kode');
        $act = $this->input->post('act');
        
        if ($act == 'add' && $this->check_tipe_jurnal_exists($kode)) {
            $this->form_validation->set_message('check_exists', 'Record '.$kode.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_tipe_jurnal_exists($kode) {

        if ($kode != 0) {
            $data = array('kode' => $kode,'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_tipe_jurnal');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } 
        
        return FALSE;
    }

    function getTipe_jurnal($id = null) {

        if (!empty($id)) {
            $this->db->where('is_deleted', 2);
            $query = $this->db->where("kode", $id)->get('m_tipe_jurnal');
            return $query->row_array();
        } else {
            $this->db->where('is_deleted', 2);
            $this->db->order_by('kode', 'ASC');
            $query = $this->db->get("m_tipe_jurnal");
            return $query->result_array();
        }
    }

    public function addTipe_jurnal($data) {

        $this->db->insert('m_tipe_jurnal', $data);
        //return $this->db->insert_id();
        return $data['kode'];
    }
    
    public function editTipe_jurnal($data, $kode) {

        $this->db->where('kode', $kode);
        $this->db->update('m_tipe_jurnal', $data);
        
        return $kode;
    }

    function deleteTipe_jurnal($kode) {
        $userinput = $this->customlib->getSessionUsername();
        $data = array( 
            'is_deleted' => 1,
            'deleted_by' => $userinput,
            'deleted_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('kode', $kode);
        $this->db->update('m_tipe_jurnal', $data);
        //$this->db->where("kode", $kode)->delete("m_tipe_jurnal");
    }

}

?>