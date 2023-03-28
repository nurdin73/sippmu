<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Kode_transaksi extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('kode_transaksi_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('master_kode_transaksi', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_buku_kas');
        $this->session->set_userdata('sub_menu', 'master/kode_transaksi');
        $userinput = $this->customlib->getSessionUsername();

        $data["title"] = "Master Kode Transaksi";

        $data["cbx_akun"] = $this->kode_transaksi_model->getAkun();
        $data["cbx_parent"] = $this->kode_transaksi_model->getAkunParent();     
        
        $this->load->view("layout/header");
        $this->load->view("master/kode_transaksi", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->kode_transaksi_model->getall();
    }

    function get_data($id) {
        $result = $this->kode_transaksi_model->getData($id);
        echo json_encode($result);
    }

    function get_kode_parent($id) {
        $result = $this->kode_transaksi_model->getKodeParent($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('master_kode_transaksi', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('deskripsi', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('level', 'Level Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tipe', 'Tipe Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun', 'Kode Rekening', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Akun', array('required',
                    array('check_exists', array($this->kode_transaksi_model, 'valid_kode_transaksi'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'deskripsi' => form_error('deskripsi'),
                'kode' => form_error('kode'),
                'level' => form_error('level'),
                'tipe' => form_error('tipe'),
                'akun' => form_error('akun'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $data = array(
                'parent'    => $this->input->post("parent"), 
                'kode'      => $this->input->post("kode"), 
                'deskripsi' => $this->input->post("deskripsi"), 
                'level'     => $this->input->post("level"), 
                'tipe'      => $this->input->post("tipe"), 
                'akun'      => $this->input->post("akun"), 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->kode_transaksi_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil');
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('master_kode_transaksi', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('deskripsi', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('level', 'Level Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tipe', 'Tipe Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun', 'Kode Rekening', 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'deskripsi' => form_error('deskripsi'),
                'level' => form_error('level'),
                'tipe' => form_error('tipe'),
                'akun' => form_error('akun'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $id = $this->input->post("idx");
            
            $data = array(
                //'parent' => $this->input->post("parent"), 
                //'kode' => $this->input->post("kode"), 
                'deskripsi' => $this->input->post("deskripsi"), 
                'level'     => $this->input->post("level"), 
                'tipe'      => $this->input->post("tipe"), 
                'akun'      => $this->input->post("akun"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->kode_transaksi_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function delete($id) {
        if(!$this->rbac->hasPrivilege('master_kode_transaksi', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->kode_transaksi_model->deleteData($id);
        }
        redirect('master/kode_transaksi');
    }

}

?>