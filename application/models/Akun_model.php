<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Akun_model extends CI_model {

    public function valid_akun($str) {
        $kode = $this->input->post('kode');
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
            $data = array('id != ' => $id, 'kode' => $kode, 'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_akun');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('is_deleted', 2);
            $this->db->where('kode', $kode);
            $query = $this->db->get('m_akun');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function getall() {
        $this->datatables->select("a.id as id, a.kode as kodex, b.nama as kel_akun, c.kode as parent_kode, a.kode, a.nama, a.level, a.posisi_akun", false);
        $this->datatables->from('m_akun as a');
        $this->datatables->join('m_kel_akun as b','b.kode = a.kel_akun');
        $this->datatables->join('m_akun as c','c.id = a.parent','LEFT');
        $this->datatables->where('a.is_deleted', 2);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
    }

    function getData($id = null) {

        if (isset($id)) {

            $query = $this->db->where("id", $id)->get('m_akun');
            return $query->row_array();
        } else {

            $query = $this->db->get("m_akun");
            return $query->result_array();
        }
    }

    function getKodeParent($id = null) {

        if (isset($id)) {
            $query = $this->db->where("id", $id)->get('m_akun');
            $data = $query->row_array();
            $parent = $data['kode'];
            $level_parent = $data['level'];
            
            $this->db->where("parent", $id);
            $this->db->select_max('kode');
            $query2 = $this->db->get('m_akun');
            $data2 = $query2->row_array();
            $max_kode = $data2['kode'];
            $expl_kode = explode('.',$max_kode);
            
            
            if($level_parent == ''){
                $sub1 = substr($expl_kode[0],0,1);
                $next1 = ($sub1+1).'0000';
                $next2 = $expl_kode[1];
                $next3 = $expl_kode[2];
            }
            
            if($level_parent == 1){
                $sub1 = substr($expl_kode[0],0,2);
                $next1 = ($sub1+1).'000';
                $next2 = $expl_kode[1];
                $next3 = $expl_kode[2];
            }
            
            if($level_parent == 2){
                $sub1 = substr($expl_kode[0],0,3);
                $next1 = ($sub1+1).'00';
                $next2 = $expl_kode[1];
                $next3 = $expl_kode[2];
            }
            
            if($level_parent == 3){
                $next1 = sprintf("%05d",($expl_kode[0]+1));
                $next2 = $expl_kode[1];
                $next3 = $expl_kode[2];
            }
            
            if($level_parent == 4){
                $next1 = $expl_kode[0];
                $next2 = sprintf("%02d",($expl_kode[1]+1));
                $next3 = $expl_kode[2];
            }
            
            if($level_parent == 5){
                $next1 = $expl_kode[0];
                $next2 = $expl_kode[1];
                $next3 = sprintf("%02d",($expl_kode[2]+1));
            }
            
            $next_kode = $max_kode ? $next1.'.'.$next2.'.'.$next3 : $parent.'00';
            
            $result = array(
                'next_kode' => $next_kode,
                'next_level'     => $level_parent+1
            );
            return $result;
        } else {
            return false;
        }
    }

    public function addData($data) {

        $this->db->insert('m_akun', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('m_akun', $data);
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
            $this->db->update('m_akun', $data);
        } 
        return $id;
    }

    
    function getAkunParent() {

        $query = $this->db->select('*')
            ->where("is_deleted", 2)
            //->where("parent", 0)
            ->get('m_akun');
        return $query->result_array();
    }

    function getKelAkun() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_kel_akun');
        return $query->result_array();
    }

}

?>