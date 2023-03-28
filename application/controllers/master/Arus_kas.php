<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Arus_kas extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('arus_kas_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/arus_kas');
        
        $data["cbx_kode_mutasi"] = $this->arus_kas_model->getKodeMutasi();     
        $data["cbx_parent_arus_kas"] = $this->arus_kas_model->getParentArusKas();     
        
        $data['title'] = 'Master Arus Kas';
        
        $this->load->view("layout/header");
        $this->load->view("master/arus_kas", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->arus_kas_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->arus_kas_model->getAllDetail();
    }

    function get_kode_parent($id) {
        $result = $this->arus_kas_model->getKodeParent($id);
        echo json_encode($result);
    }

    function get_data($id) {
        $result = $this->arus_kas_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->arus_kas_model->getDataDetail($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_add')){
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
        
            $data = array(
                'parent' => $parent, 
                'kode' => $this->input->post("kode"), 
                'nama' => $this->input->post("nama"), 
                'level' => $level, 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->arus_kas_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','arus_kas_idx' => $insert_id, 'levelx' => $level);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('arus_kas_id', 'ID', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'arus_kas_id' => form_error('arus_kas_id')
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("arus_kas_id");
            $data = array(
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->arus_kas_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_add') || !$this->rbac->hasPrivilege('master_arus_kas', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        
        $this->form_validation->set_rules('akun', 'Akun', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'akun' => form_error('akun'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $act = $this->input->post("act");
            $detail_idx = '';
            $detail_id = $this->input->post("arus_kas_det_id");
            $arus_kas_id = $this->input->post("arus_kas_id");
            
            $data = array(
                'arus_kas' => $arus_kas_id, 
                'akun' => $this->input->post("akun"), 
            );
            
            if($act == 'add'){
                $data['is_deleted'] = 2;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('master_arus_kas', 'can_add')){
                    $detail_idx = $this->arus_kas_model->addDataDetail($data);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('master_arus_kas', 'can_edit')){
                    $detail_idx = $this->arus_kas_model->editDataDetail($data, $detail_id);
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
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->arus_kas_model->deleteData($id);
            if($dodeleted == 'success'){
                
                redirect('master/arus_kas');
            }else{
                $array = array('status' => 'fail', 'error' => $dodeleted, 'message' => '');
            }
        }
        
    }

    function deleteDetail($id, $arus_kas_id) {
        if(!$this->rbac->hasPrivilege('master_arus_kas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->arus_kas_model->deleteDataDetail($id, $arus_kas_id);
        }
        return 'success';
    }
    
}

?>