<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mapping_posisi_keuangan extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('mapping_posisi_keuangan_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/mapping_posisi_keuangan');
        
        $data["cbx_kode_rekening"] = $this->mapping_posisi_keuangan_model->getKodeRekening();     
        $data["cbx_parent"] = $this->mapping_posisi_keuangan_model->getParent();     
        
        $data['title'] = 'Mapping Laporan Posisi Keuangan';
        
        $this->load->view("layout/header");
        $this->load->view("master/mapping_posisi_keuangan", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mapping_posisi_keuangan_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mapping_posisi_keuangan_model->getAllDetail();
    }

    function get_kode_parent($id) {
        $result = $this->mapping_posisi_keuangan_model->getKodeParent($id);
        echo json_encode($result);
    }

    function get_data($id) {
        $result = $this->mapping_posisi_keuangan_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->mapping_posisi_keuangan_model->getDataDetail($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('kode', 'Kode', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');
        $this->form_validation->set_rules('urutan', 'Urutan', 'trim|required|xss_clean');
        $this->form_validation->set_rules('is_posisi', 'Posisi', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'level' => form_error('level'),
                'urutan' => form_error('urutan'),
                'is_posisi' => form_error('is_posisi'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $parent = $this->input->post("parent") ? $this->input->post("parent") : 0;
            $level = $this->input->post("level") ? $this->input->post("level") : 1;
            $is_sub_total = $this->input->post("is_sub_total") ? $this->input->post("is_sub_total") : 0;
            $is_space_after = $this->input->post("is_space_after") ? $this->input->post("is_space_after") : 0;
            $is_show_detail = 0;
            if($level == 3){
                $is_show_detail = 1;
            }
            $data = array(
                'parent' => $parent, 
                'kode' => $this->input->post("kode"), 
                'nama' => $this->input->post("nama"), 
                'level' => $level, 
                'urutan'    => $this->input->post("urutan"), 
                'keterangan'    => $this->input->post("keterangan"), 
                'is_posisi'    => $this->input->post("is_posisi"), 
                'is_sub_total' => $is_sub_total,
                'is_space_after' => $is_space_after,
                'is_show_detail' => $is_show_detail,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->mapping_posisi_keuangan_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','posisi_keuangan_idx' => $insert_id, 'levelx' => $level);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('posisi_keuangan_id', 'ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');
        $this->form_validation->set_rules('urutan', 'Urutan', 'trim|required|xss_clean');
        $this->form_validation->set_rules('is_posisi', 'Posisi', 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'posisi_keuangan_id' => form_error('posisi_keuangan_id'),
                'nama' => form_error('nama'),
                'level' => form_error('level'),
                'urutan' => form_error('urutan'),
                'is_posisi' => form_error('is_posisi'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("posisi_keuangan_id");
            $parent = $this->input->post("parent") ? $this->input->post("parent") : 0;
            $level = $this->input->post("level") ? $this->input->post("level") : 0;
            $is_sub_total = $this->input->post("is_sub_total") ? $this->input->post("is_sub_total") : 0;
            $is_space_after = $this->input->post("is_space_after") ? $this->input->post("is_space_after") : 0;
            $is_show_detail = $this->input->post("is_show_detail") ? $this->input->post("is_show_detail") : 0;

            if($level == 3){
                $is_show_detail = 1;
            }
            $data = array(
                'parent' => $parent, 
                'nama' => $this->input->post("nama"), 
                'level' => $level, 
                'urutan'    => $this->input->post("urutan"), 
                'keterangan'    => $this->input->post("keterangan"), 
                'is_posisi'    => $this->input->post("is_posisi"), 
                'is_sub_total' => $is_sub_total, 
                'is_space_after' => $is_space_after,
                'is_show_detail' => $is_show_detail,
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->mapping_posisi_keuangan_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_add') || !$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        
        $this->form_validation->set_rules('id_rek', 'Rekening', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'id_rek' => form_error('id_rek'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $act = $this->input->post("act");
            $detail_idx = '';
            $detail_id = $this->input->post("posisi_keuangan_det_id");
            $posisi_keuangan_id = $this->input->post("posisi_keuangan_id");
            
            $data = array(
                'report_posisi_keuangan' => $posisi_keuangan_id, 
                'id_rek' => $this->input->post("id_rek"), 
            );
            
            if($act == 'add'){
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_add')){
                    $detail_idx = $this->mapping_posisi_keuangan_model->addDataDetail($data);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_edit')){
                    $detail_idx = $this->mapping_posisi_keuangan_model->editDataDetail($data, $detail_id);
                }
                $msg = 'Edit data ';
            }
            
            if($detail_idx){
                $array = array('status' => 'success', 'error' => '', 'message' => $msg.' berhasil','detail_idx' => $detail_idx);
            }else{
                $array = array('status' => 'fail', 'error' => '', 'message' => $msg.' gagal','detail_idx' => $detail_idx);
            }
            
        }

        echo json_encode($array);
    }

    function delete($id) {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->mapping_posisi_keuangan_model->deleteData($id);
            if($dodeleted == 'success'){
                
                redirect('master/mapping_posisi_keuangan');
            }else{
                $array = array('status' => 'fail', 'error' => $dodeleted, 'message' => '');
            }
        }
        
    }

    function deleteDetail($id, $posisi_keuangan_id) {
        if(!$this->rbac->hasPrivilege('mapping_posisi_keuangan', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->mapping_posisi_keuangan_model->deleteDataDetail($id, $posisi_keuangan_id);
        }
        return 'success';
    }
    
}

?>