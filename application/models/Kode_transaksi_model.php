<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Kode_transaksi_model extends CI_model {

    public function valid_kode_transaksi($str) {
        $kode = $this->input->post('kode');
        $id = $this->input->post('idx');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_exists($kode, $id)) {
            $this->form_validation->set_message('check_exists', 'Record '.$kode.' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_exists($kode, $id) {

        if ($id != 0) {
            $data = array('id != ' => $id, 'kode' => $kode, 'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_kode_transaksi');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('is_deleted', 2);
            $this->db->where('kode', $kode);
            $query = $this->db->get('m_kode_transaksi');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function getall() {
        $this->datatables->select("a.id as id, a.kode, a.kode as kode2, a.deskripsi, c.kode as parent_kode, a.level, a.tipe, b.nama as akun_nama", false);
        $this->datatables->from('m_kode_transaksi as a');
        $this->datatables->join('m_akun as b','b.id = a.akun','LEFT');
        $this->datatables->join('m_kode_transaksi as c','c.id = a.parent','LEFT');
        $this->datatables->where('a.is_deleted', 2);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,kode');
        return $this->datatables->generate();
    }

    function getData($id = null) {

        if (isset($id)) {

            $query = $this->db->where("id", $id)->get('m_kode_transaksi');
            return $query->row_array();
        } else {

            $query = $this->db->get("m_kode_transaksi");
            return $query->result_array();
        }
    }

    function getKodeParent($id = null) {

        if (isset($id)) {
            $query = $this->db->where("id", $id)->get('m_kode_transaksi');
            $data = $query->row_array();
            $parent = $data['kode'];
            $level = $data['level'];
            
            $this->db->where("parent", $id);
            $this->db->select_max('kode');
            $query2 = $this->db->get('m_kode_transaksi');
            $data2 = $query2->row_array();
            $max_kode = $data2['kode'];
            
            $next_kode = $max_kode ? $max_kode+1 : $parent.'01';
            
            $result = array(
                'next_kode' => $next_kode,
                'next_level'     => $level+1
            );
            return $result;
        } else {
            return false;
        }
    }

    public function addData($data) {

        $this->db->insert('m_kode_transaksi', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('m_kode_transaksi', $data);
        } 
        return $id;
    }

    function deleteData($id) {
        //$this->db->where("id", $id)->delete("m_kode_transaksi");
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $this->db->update('m_kode_transaksi', $data);
        } 
        return $id;
    }

    
    function getAkunParent() {

        $query = $this->db->select('*')
            ->where("is_deleted", 2)
            //->where("parent", 0)
            ->get('m_kode_transaksi');
        return $query->result_array();
    }

    function getAkun() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_akun');
        return $query->result_array();
    }

}

?>