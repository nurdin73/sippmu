<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mutasi_kas_bank extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_mutasi_kas_bank', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_kas_bank');
        $this->session->set_userdata('sub_menu', 'laporan/mutasi_kas_bank');

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
        $src_akun_kas = $this->input->post('src_akun_kas') ? $this->input->post('src_akun_kas') : '';
        
        $data["cbx_akun_kas"] = $this->report_model->getAkunKas($src_cabangx);
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_report"] = $this->report_model->getDataMutasiKasBank($src_cabangx, $src_date1, $src_date2, $src_akun_kas);
        $data["saldo_awal"] = $this->report_model->getSaldoAwal($src_cabangx, $src_date1, $src_date2, $src_akun_kas);

        $data["src_date1"] = $src_date1;
        $data["src_date2"] = $src_date2;
        $data["src_akun_kas"] = $src_akun_kas;
        
        $data["title"] = 'Laporan Mutasi Kas Bank'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/mutasi_kas_bank", $data);
        $this->load->view("layout/footer");

    }

}

?>