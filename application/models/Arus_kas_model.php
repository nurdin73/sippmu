<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Arus_kas_model extends CI_model {

    function getAll() {
        
        $this->datatables->select("a.id as id, '' as nomor, a.nama, a.kode, a.parent, a.level, a.is_deleted as status, '' as view", false);
        $this->datatables->from('m_arus_kas a');
        $this->datatables->where('a.is_deleted', 2);
        $this->datatables->where('a.is_sub_total', 'f');
//        $this->datatables->add_column('view', '<a onclick="getEdit(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
//                                                    <i class="fa fa-pencil"></i></a>
//                                                <a  class="btn btn-default btn-xs" onclick="deleteData(this)" data-id="$1" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
//                                                        <i class="fa fa-trash"></i></a>', 'id');
      
        
        return $this->datatables->generate();
    }

    function getAllDetail() {
        $arus_kas_id = $this->input->post('arus_kas_id') ? $this->input->post('arus_kas_id') : '';
       
        if(!empty($arus_kas_id)){
        
            $this->datatables->select("a.id as id, '' as nomor, b.kode as mutasi_kode, b.deskripsi as mutasi_nama", false);
            $this->datatables->from('m_arus_kas_det a');
            $this->datatables->join('m_kode_transaksi b','a.akun = b.id');
            $this->datatables->where('a.is_deleted', 2);
            $this->datatables->where('a.arus_kas', $arus_kas_id);
            
            $this->datatables->add_column('view', '<a onclick="getEditDetail(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#myModalDetail" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a  class="btn btn-default btn-xs" onclick="deleteDataDetail(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                            <i class="fa fa-trash"></i></a>', 'id,mutasi_kode');
             
            return $this->datatables->generate();
            
        }else{
            
            return '{"draw":1,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
        }
    }

    function getData($id = null) {

        if (isset($id)) {
            //level 4
            // $this->db->select("a.id, a.nama as nama_lv4, b.nama as nama_lv3, c.nama as nama_lv2, d.nama as nama_lv1", false);
            // $this->db->where("a.id", $id);
            // $this->db->join('m_arus_kas b','a.parent = b.id');
            // $this->db->join('m_arus_kas c','b.parent = c.id');
            // $this->db->join('m_arus_kas d','c.parent = d.id');
            // $query = $this->db->get('m_arus_kas a');

            //level 3
            $this->db->select("a.id, a.nama as nama_lv3, b.nama as nama_lv2, c.nama as nama_lv1", false);
            $this->db->where("a.id", $id);
            $this->db->join('m_arus_kas b','a.parent = b.id');
            $this->db->join('m_arus_kas c','b.parent = c.id');
            $query = $this->db->get('m_arus_kas a');

            return $query->row_array();
        } else {
            $this->db->where('is_deleted', 2);
            $this->db->where('is_sub_total', 'f');
            $query = $this->db->get("m_arus_kas");
            return $query->result_array();
        }
    }

    function getDataDetail($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, b.kode as kode_trx, b.deskripsi as transaksi_nama", false);
            $this->db->join('m_kode_transaksi b','a.akun = b.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('m_arus_kas_det a');
            return $query->row_array();
        } else {

            $query = $this->db->get("m_arus_kas_det");
            return $query->result_array();
        }
    }

    public function addData($data) {

        $this->db->insert('m_arus_kas', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('m_arus_kas', $data);
        } 
        return $id;
    }

    public function addDataDetail($data) {
        
        //===insert ke tabel m_arus_kas_det
        $this->db->insert('m_arus_kas_det', $data);
        $dana_det_id = $this->db->insert_id();
        
        return $dana_det_id;
    }

    public function editDataDetail($data, $dana_det_id) {
        
        if (isset($dana_det_id)) {
            //=== update ke tabel m_arus_kas_det
            $this->db->where('id', $dana_det_id);
            $this->db->update('m_arus_kas_det', $data);
        }
        
        return $dana_det_id;
    }

    function deleteData($id) {
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $doupdate = $this->db->update('m_arus_kas', $data);
        } 

        return 'success';
        
    }

    function deleteDataDetail($id, $arus_kas_id) {
        //validasi : data bisa dihapus dengan kondisi status=1 
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $this->db->where('arus_kas', $arus_kas_id);
            $doupdate = $this->db->update('m_arus_kas_det', $data);
            
            if($doupdate){
                return 'success';
            }
            
        } 

        return false;

    }

    function getKodeMutasi() {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        //$this->db->where('level', '3');
        $query = $this->db->select('*')->get('m_kode_transaksi');
        return $query->result_array();
    }

    // function getKodeRekening() {
    //     $this->db->order_by('kode', 'ASC');
    //     $this->db->where('is_deleted', 2);
    //     $this->db->where('level', '5');
    //     $query = $this->db->select('*')->get('m_akun');
    //     return $query->result_array();
    // }

    function getParentArusKas() {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        $this->db->where('is_sub_total', 'f');
        $this->db->where('level <', 4);
        $query = $this->db->select('*')->get('m_arus_kas');
        return $query->result_array();
    }

    function getKodeParent($id = null) {

        if (isset($id)) {
            
            $this->db->where('is_deleted', 2);
            $this->db->where('is_sub_total', 'f');
            $query = $this->db->where("id", $id)->get('m_arus_kas');
            $data = $query->row_array();
            $parent = $data['id'];
            $kode_parent = $data['kode'];
            $level_parent = $data['level'];
            if(empty($parent)){
                $parent = 0;
            }
            
            $this->db->where('is_deleted', 2);
            $this->db->where('is_sub_total', 'f');
            $this->db->where("parent", $parent);
            $this->db->select_max('kode');
            $query2 = $this->db->get('m_arus_kas');
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