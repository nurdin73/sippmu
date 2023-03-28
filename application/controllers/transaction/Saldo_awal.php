<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Saldo_awal extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('saldo_awal_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('trx_saldo_awal', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'transaksi_akuntansi');
        $this->session->set_userdata('sub_menu', 'transaction/saldo_awal');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data_setup = $this->saldo_awal_model->getSetupPeriodeSaldoAwal($cabang);
        $data["closing_saldo_awal"] = $data_setup['closing_saldo_awal'];
        $data["periode_saldo_awal"] = $data_setup['periode_saldo_awal'];
        $periode_saldo_awal_exp = explode('/', $data["periode_saldo_awal"]);
        $periode_bulan = $this->customlib->getMonthByCode($periode_saldo_awal_exp[0]);
        if($periode_bulan){
            $data["periode_saldo_awal_txt"] = $periode_bulan.' '.$periode_saldo_awal_exp[1];
        }else{
            $data["periode_saldo_awal_txt"] = '';
        }
            
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $this->load->view("layout/header");
        $this->load->view("transaction/saldo_awal", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->saldo_awal_model->getall();
    }

    function get_data($id, $cabang='') {
        if(empty($cabang)){
            $data_session = $this->session->userdata('admin');
            $cabang = $data_session['cabang'];
        }
        $result = $this->saldo_awal_model->getData($id,$cabang);
        echo json_encode($result);
    }

    function closing_saldo_awal($cabang='') {
        $result = $this->saldo_awal_model->closingSaldoAwal($cabang);
        echo json_encode($result);
    }

    function checkTotal($cabang) {
        $result = $this->saldo_awal_model->getTotalSaldoAwal($cabang);
        echo json_encode($result);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('trx_saldo_awal', 'can_edit')){
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
            
            $idx = $this->input->post("idx");
            $saldo_id = $this->input->post("saldo_id");
            $cabang = $this->input->post("cabang");
            if(empty($cabang)){
                $data_session = $this->session->userdata('admin');
                $cabang = $data_session['cabang'];
            }
            
            $jumlah_debet = str_replace(',','.', $this->input->post("jumlah_debet"));
            $jumlah_kredit = str_replace(',','.', $this->input->post("jumlah_kredit"));
            
            $data = array(
                'cabang' => $cabang, 
                'akun' => $this->input->post("akun"), 
                'jumlah_debet' => $jumlah_debet, 
                'jumlah_kredit' => $jumlah_kredit, 
                'keterangan' => $this->input->post("keterangan"),  
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            if(empty($saldo_id)){
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->saldo_awal_model->addData($data);
            }else{
                $this->saldo_awal_model->editData($data, $saldo_id);
            }
            
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
}

?>