<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Mapping_posisi_keuangan_model extends CI_model {

    function getAll() {
        
        $this->datatables->select("a.id as id, '' as nomor, a.nama, a.kode, a.parent, a.level, a.urutan, '' as view", false);
        $this->datatables->from('report_posisi_keuangan a');
//        $this->datatables->add_column('view', '<a onclick="getEdit(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
//                                                    <i class="fa fa-pencil"></i></a>
//                                                <a  class="btn btn-default btn-xs" onclick="deleteData(this)" data-id="$1" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
//                                                        <i class="fa fa-trash"></i></a>', 'id');
      
        
        return $this->datatables->generate();
    }

    function getAllDetail() {
        $posisi_keuangan_id = $this->input->post('posisi_keuangan_id') ? $this->input->post('posisi_keuangan_id') : '';
       
        if(!empty($posisi_keuangan_id)){
        
            $this->datatables->select("a.id as id, '' as nomor, b.kode as kode_rek, b.nama as nama_rek", false);
            $this->datatables->from('report_posisi_keuangan_det a');
            $this->datatables->join('m_akun b','a.id_rek = b.id');
            $this->datatables->where('a.report_posisi_keuangan', $posisi_keuangan_id);
            
            $this->datatables->add_column('view', '<a onclick="getEditDetail(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#myModalDetail" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a  class="btn btn-default btn-xs" onclick="deleteDataDetail(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                            <i class="fa fa-trash"></i></a>', 'id,kode_rek');
             
            return $this->datatables->generate();
            
        }else{
            
            return '{"draw":1,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
        }
    }

    function getData($id = null) {

        if (isset($id)) {
            
            $this->db->where("a.id", $id);
            $query = $this->db->get('report_posisi_keuangan a');

            return $query->row_array();
        } else {
            $this->db->order_by('urutan', 'ASC');
            $query = $this->db->get("report_posisi_keuangan");
            return $query->result_array();
        }
    }

    function getDataDetail($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, b.kode as kode_rek, b.nama as nama_rek", false);
            $this->db->join('m_akun b','a.id_rek = b.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('report_posisi_keuangan_det a');
            return $query->row_array();
        } else {

            $query = $this->db->get("report_posisi_keuangan_det");
            return $query->result_array();
        }
    }

    public function addData($data) {

        $this->db->insert('report_posisi_keuangan', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('report_posisi_keuangan', $data);
        } 
        return $id;
    }

    public function addDataDetail($data) {
        
        //===insert ke tabel report_posisi_keuangan_det
        $this->db->insert('report_posisi_keuangan_det', $data);
        $posisi_keuangan_det_id = $this->db->insert_id();
        
        return $posisi_keuangan_det_id;
    }

    public function editDataDetail($data, $posisi_keuangan_det_id) {
        
        if (isset($posisi_keuangan_det_id)) {
            //=== update ke tabel report_posisi_keuangan_det
            $this->db->where('id', $posisi_keuangan_det_id);
            $this->db->update('report_posisi_keuangan_det', $data);
        }
        
        return $posisi_keuangan_det_id;
    }

    function deleteData($id) {
        if (isset($id)) {
            
            $this->db->where('id', $id);
            $dodelete = $this->db->delete('report_posisi_keuangan');

            if($dodelete){
                $this->db->where('report_posisi_keuangan', $id);
                $this->db->delete('report_posisi_keuangan_det');
            }
        } 

        return 'success';
        
    }

    function deleteDataDetail($id, $posisi_keuangan_id) {
        //validasi : data bisa dihapus dengan kondisi status=1 
        if (isset($id)) {
            
            $this->db->where('id', $id);
            $this->db->where('report_posisi_keuangan', $posisi_keuangan_id);
            $dodelete = $this->db->delete('report_posisi_keuangan_det');
            
            if($dodelete){
                return 'success';
            }
            
        } 

        return false;

    }

    function getKodeMutasi() {
        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        //$this->db->where('level', '3');
        $query = $this->db->select('*')->get('m_akun');
        return $query->result_array();
    }

    function getKodeRekening() {
        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        //$this->db->where('level', '5');
        $query = $this->db->select('*')->get('m_akun');
        return $query->result_array();
    }

    function getParent() {

        $this->db->order_by('urutan', 'ASC');
        //$this->db->where('level <', 4);
        $query = $this->db->select('id,kode, nama')->get('report_posisi_keuangan');
        return $query->result_array();
    }

    function getKodeParent($id = null) {

        if (isset($id)) {
            
            $this->db->order_by('urutan', 'ASC');
            $query = $this->db->where("id", $id)->get('report_posisi_keuangan');
            $data = $query->row_array();
            $parent = $data['id'];
            $kode_parent = $data['kode'];
            $level_parent = $data['level'];
            if(empty($parent)){
                $parent = 0;
            }
            
            $this->db->order_by('urutan', 'ASC');
            $this->db->where("parent", $parent);
            $this->db->select_max('kode');
            $query2 = $this->db->get('report_posisi_keuangan');
            $data2 = $query2->row_array();
            $max_kode = $data2['kode'];
            
            if(empty($max_kode)){
                $max_kode = $kode_parent;
            }
            
            $expl_kode = explode('.',$max_kode);
            //print_r($expl_kode);
            
            if(empty($level_parent)){
               // echo $expl_kode[0];
                $next1 = isset($expl_kode[0]) ? $expl_kode[0]+1 : '1';
                $next2 = '';
                $next3 = '';
            }
            
            if($level_parent == 1){
                $next1 = $expl_kode[0].'.';
                $next2 = isset($expl_kode[1]) ? $expl_kode[1]+1 : '1';
                $next3 = '';
            }
            
            if($level_parent == 2){
                
                $sub2_1 = substr($expl_kode[1],0,1);
                //echo $sub2_1.'<br>';
                if(strlen($expl_kode[1]) == 3){
                    $sub2_2 = substr($expl_kode[1],1,2);
                }else{
                    $sub2_2 = 0;
                }
                //echo $sub2_2.'<br>';
                $next1 = $expl_kode[0].'.';
                $next2 = $sub2_1.sprintf("%02d",$sub2_2+1);
                $next3 = '';
            }
            
            if($level_parent == 3){
                $next1 = $expl_kode[0].'.';
                $next2 = $expl_kode[1].'.';
                if(isset($expl_kode[2])){
                    $next3 = sprintf("%02d",($expl_kode[2]+1));
                }else{
                    $next3 = '01';
                }
            }
            
            $next_kode = $max_kode ? $next1.$next2.$next3 : ($expl_kode[0]+1);
            
            $result = array(
                'next_kode' => $next_kode,
                'next_level'=> $level_parent+1
            );
            return $result;
        } else {
            return false;
        }
    }

}

?>