<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mapping_aktivitas extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('mapping_aktivitas_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/mapping_aktivitas');
        
        $data["cbx_kode_rekening"] = $this->mapping_aktivitas_model->getKodeRekening();     
        $data["cbx_parent"] = $this->mapping_aktivitas_model->getParent();     
        
        $data['title'] = 'Mapping Laporan Aktivitas';
        
        $this->load->view("layout/header");
        $this->load->view("master/mapping_aktivitas", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mapping_aktivitas_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mapping_aktivitas_model->getAllDetail();
    }

    function get_kode_parent($id) {
        $result = $this->mapping_aktivitas_model->getKodeParent($id);
        echo json_encode($result);
    }

    function get_data($id) {
        $result = $this->mapping_aktivitas_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->mapping_aktivitas_model->getDataDetail($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('kode', 'Kode', 'trim|required|xss_clean');
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('level', 'Level', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'level' => form_error('level'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $parent = $this->input->post("parent") ? $this->input->post("parent") : 0;
            $level = $this->input->post("level") ? $this->input->post("level") : 1;
            $is_sub_total = $this->input->post("is_sub_total") ? $this->input->post("is_sub_total") : 0;
            
            $data = array(
                'parent' => $parent, 
                'kode' => $this->input->post("kode"), 
                'nama' => $this->input->post("nama"), 
                'level' => $level, 
                'urutan'    => $this->input->post("urutan"), 
                'keterangan'    => $this->input->post("keterangan"), 
                'is_sub_total' => $is_sub_total,
                'is_space_after' => '0',
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->mapping_aktivitas_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','aktivitas_idx' => $insert_id, 'levelx' => $level);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('aktivitas_id', 'ID', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'aktivitas_id' => form_error('aktivitas_id')
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("aktivitas_id");
            $is_sub_total = $this->input->post("is_sub_total") ? $this->input->post("is_sub_total") : 0;
            $is_space_after = $this->input->post("is_space_after") ? $this->input->post("is_space_after") : 0;

            $data = array(
                'nama' => $this->input->post("nama"), 
                'level' => $this->input->post("level"), 
                'urutan'    => $this->input->post("urutan"), 
                'keterangan'    => $this->input->post("keterangan"), 
                'is_sub_total' => $is_sub_total, 
                'is_space_after' => $is_space_after,
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->mapping_aktivitas_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_add') || !$this->rbac->hasPrivilege('mapping_aktivitas', 'can_edit')){
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
            $detail_id = $this->input->post("aktivitas_det_id");
            $aktivitas_id = $this->input->post("aktivitas_id");
            
            $data = array(
                'report_aktivitas' => $aktivitas_id, 
                'id_rek' => $this->input->post("id_rek"), 
            );
            
            if($act == 'add'){
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('mapping_aktivitas', 'can_add')){
                    $detail_idx = $this->mapping_aktivitas_model->addDataDetail($data);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('mapping_aktivitas', 'can_edit')){
                    $detail_idx = $this->mapping_aktivitas_model->editDataDetail($data, $detail_id);
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
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->mapping_aktivitas_model->deleteData($id);
            if($dodeleted == 'success'){
                
                redirect('master/mapping_aktivitas');
            }else{
                $array = array('status' => 'fail', 'error' => $dodeleted, 'message' => '');
            }
        }
        
    }

    function deleteDetail($id, $aktivitas_id) {
        if(!$this->rbac->hasPrivilege('mapping_aktivitas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->mapping_aktivitas_model->deleteDataDetail($id, $aktivitas_id);
        }
        return 'success';
    }
    
}

?>