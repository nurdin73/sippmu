<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Akun extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('akun_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('master_akun', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/akun');
        $userinput = $this->customlib->getSessionUsername();

        $data["cbx_kel_akun"] = $this->akun_model->getKelAkun();
        $data["cbx_parent"] = $this->akun_model->getAkunParent();     
        
        $data["title"] = "Master Kode Rekening";
        $this->load->view("layout/header");
        $this->load->view("master/akun", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->akun_model->getall();
    }

    function get_data($id) {
        $result = $this->akun_model->getData($id);
        echo json_encode($result);
    }

    function get_kode_parent($id) {
        $result = $this->akun_model->getKodeParent($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('master_akun', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kel_akun', 'Kelompok Akun', 'trim|required|xss_clean');
        $this->form_validation->set_rules('posisi_akun', 'Posisi Akun', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Akun', array('required',
                    array('check_exists', array($this->akun_model, 'valid_akun'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'kel_akun' => form_error('kel_akun'),
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'posisi_akun' => form_error('posisi_akun'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $data = array(
                'kel_akun' => $this->input->post("kel_akun"), 
                'parent' => $this->input->post("parent"), 
                'kode' => $this->input->post("kode"), 
                'nama' => $this->input->post("nama"), 
                'level' => $this->input->post("level"), 
                'posisi_akun' => $this->input->post("posisi_akun"), 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->akun_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil');
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('master_akun', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kel_akun', 'Kelompok Akun', 'trim|required|xss_clean');
        $this->form_validation->set_rules('posisi_akun', 'Posisi Akun', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Akun', array('required',
                    array('check_exists', array($this->akun_model, 'valid_akun'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'kel_akun' => form_error('kel_akun'),
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'posisi_akun' => form_error('posisi_akun'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $id = $this->input->post("idx");
            
            $data = array(
                'kel_akun' => $this->input->post("kel_akun"), 
                'parent' => $this->input->post("parent"), 
                'kode' => $this->input->post("kode"), 
                'nama' => $this->input->post("nama"), 
                'level' => $this->input->post("level"), 
                'posisi_akun' => $this->input->post("posisi_akun"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->akun_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function delete($id) {
        if(!$this->rbac->hasPrivilege('master_akun', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->akun_model->deleteData($id);
        }
        redirect('master/akun');
    }

}

?>