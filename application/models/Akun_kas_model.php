<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Akun_kas_model extends CI_model {

    public function valid_akun($str) {
        $kode = $this->input->post('akun');
        $id = $this->input->post('idx');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_akun_exists($kode, $id)) {
            $this->form_validation->set_message('check_exists', 'Record '.$kode.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_akun_exists($kode, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'akun' => $kode, 'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_akun_kas');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('is_deleted', 2);
            $this->db->where('akun', $kode);
            $query = $this->db->get('m_akun_kas');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function getall() {
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        if (!empty($src_cabangx)) {
            $this->datatables->where('a.cabang', $src_cabangx);
        }else{
            $this->datatables->where('a.cabang', $src_cabang);
        }
        $this->datatables->select("a.id as id, '' as nomor, a.deskripsi, b.kode as kode_akun, b.nama as nama_akun, a.saldo_awal, a.saldo_akhir", false);
        $this->datatables->from('m_akun_kas as a');
        $this->datatables->join('m_akun as b','b.id = a.akun', 'LEFT');
        $this->datatables->where('a.is_deleted', 2);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,deskripsi');
        return $this->datatables->generate();
    }

    function getData($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, b.nama as cabang_nama", false);
            $this->db->where("a.id", $id);
            $this->db->join('m_cabang as b','b.id = a.cabang');
            $query = $this->db->get('m_akun_kas a');
            return $query->row_array();
        } else {

            $query = $this->db->get("m_akun_kas");
            return $query->result_array();
        }
    }

    public function addData($data) {

        $this->db->insert('m_akun_kas', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('m_akun_kas', $data);
        } 
        return $id;
    }

    function deleteData($id) {
        //$this->db->where("id", $id)->delete("m_akun");
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $this->db->update('m_akun_kas', $data);
        } 
        return $id;
    }

    
    function getKodeRekening() {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        $this->db->where('level', '5');
        $query = $this->db->select('*')->get('m_akun');
        return $query->result_array();
    }

    function getKelAkun() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_kel_akun');
        return $query->result_array();
    }

}

?>