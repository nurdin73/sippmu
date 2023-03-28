<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Cabang_klasifikasi_model extends CI_model {

    public function valid_cabang($str) {
        $kode = $this->input->post('kode');
        $id = $this->input->post('id');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_cabang_exists($kode, $id)) {
            $this->form_validation->set_message('check_exists', 'Record '.$kode.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_cabang_exists($kode, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'kode' => $kode, 'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_cabang_klasifikasi');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('is_deleted', 2);
            $this->db->where('kode', $kode);
            $query = $this->db->get('m_cabang_klasifikasi');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function getCabangKlasifikasi($id = null) {

        if (!empty($id)) {
            $this->db->where('is_deleted', 2);
            $query = $this->db->where("id", $id)->get('m_cabang_klasifikasi');
            return $query->row_array();
        } else {
            $this->db->where('is_deleted', 2);
            $this->db->order_by('id', 'ASC');
            $query = $this->db->get("m_cabang_klasifikasi");
            return $query->result_array();
        }
    }

    public function addCabangKlasifikasi($data) {

        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('m_cabang_klasifikasi', $data);
        } else {
            $this->db->insert('m_cabang_klasifikasi', $data);
            return $this->db->insert_id();
        }
    }

    function deleteCabangKlasifikasi($id) {

        //$this->db->where("id", $id)->delete("m_cabang_klasifikasi");
        $userinput = $this->customlib->getSessionUsername();
        $data = array( 
            'is_deleted' => 1,
            'deleted_by' => $userinput,
            'deleted_date' => date('Y-m-d H:i:s')
        );
        
        $this->db->where('id', $id);
        $this->db->update('m_cabang_klasifikasi', $data);
    }

}

?>