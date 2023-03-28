<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Akun_kas extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('akun_kas_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('master_akun_kas', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_buku_kas');
        $this->session->set_userdata('sub_menu', 'master/akun_kas');

        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $data["title"] = "Master Akun Kas";

        $data["cbx_kode_rekening"] = $this->akun_kas_model->getKodeRekening();     
        
        $this->load->view("layout/header");
        $this->load->view("master/akun_kas", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->akun_kas_model->getall();
    }

    function get_data($id) {
        $result = $this->akun_kas_model->getData($id);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('master_akun_kas', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun', 'Akun', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('akun', ' Akun', array('required',
//                    array('check_exists', array($this->akun_kas_model, 'valid_akun'))
//                )
//        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'deskripsi' => form_error('deskripsi'),
                'akun' => form_error('akun'),
                'saldo_awal' => form_error('saldo_awal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $saldo_awal = str_replace(',','.', $this->input->post("saldo_awal"));
            $data = array(
                'cabang' => $this->input->post("cabang"), 
                'deskripsi' => $this->input->post("deskripsi"), 
                'saldo_awal' => $saldo_awal, 
                'saldo_akhir' => $saldo_awal, 
                'akun' => $this->input->post("akun"), 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->akun_kas_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil');
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('master_akun_kas', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('deskripsi', 'Deskripsi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('saldo_awal', 'Saldo Awal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun', 'Akun', 'trim|required|xss_clean');
        
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'deskripsi' => form_error('deskripsi'),
                'akun' => form_error('akun'),
                'saldo_awal' => form_error('saldo_awal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $id = $this->input->post("idx");
            $saldo_awal = str_replace(',','.', $this->input->post("saldo_awal"));
            
            $data = array(
                'cabang' => $this->input->post("cabang"), 
                'deskripsi' => $this->input->post("deskripsi"), 
                'saldo_awal' => $saldo_awal, 
                //'saldo_akhir' => $this->input->post("saldo_akhir"), 
                'akun' => $this->input->post("akun"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->akun_kas_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function delete($id) {
        if(!$this->rbac->hasPrivilege('master_akun_kas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->akun_kas_model->deleteData($id);
        }
        redirect('master/akun_kas');
    }

}

?>