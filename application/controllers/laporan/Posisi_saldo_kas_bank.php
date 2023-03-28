<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posisi_saldo_kas_bank extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_posisi_saldo_kas_bank', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_kas_bank');
        $this->session->set_userdata('sub_menu', 'laporan/posisi_saldo_kas_bank');

        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        $src_cabangx = $this->input->post('src_cabangx') ? $this->input->post('src_cabangx') : $cabang;
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $src_cabangx;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $src_date1 = $this->input->post('src_date1') ? $this->input->post('src_date1') : date('01-m-Y');
        $src_date2 = $this->input->post('src_date2') ? $this->input->post('src_date2') : date('d-m-Y');
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_report"] = $this->report_model->getDataPosisiSaldoKasBank($src_cabangx, $src_date1, $src_date2);

        $data["src_date1"] = $src_date1;
        $data["src_date2"] = $src_date2;
        
        $data["title"] = 'Laporan Posisi Saldo Kas Bank'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/posisi_saldo_kas_bank", $data);
        $this->load->view("layout/footer");

    }
    
}

?>