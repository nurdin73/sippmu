<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Setup_periode_laporan_model extends CI_model {

    
    function getData($cabang = null) {

        if (!empty($cabang)) {
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            return $query->row_array();
        }
    }

    
    public function editData($data, $cabang) {

        if (!empty($cabang)) {
            $this->db->where('cabang', $cabang);
            $this->db->update('setup_periode_laporan', $data);
        }
        return $cabang;
    }

    
    function getCurrency() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_currency');
        return $query->result_array();
    }


    
}

?>