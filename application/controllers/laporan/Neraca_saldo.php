<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Neraca_saldo extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_neraca_saldo', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/neraca_saldo');

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
        
        $data["cbx_level"] = $this->report_model->getLevel();
        $data['cbx_bulan'] = $this->customlib->getMonthList();
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $data["src_bulan"] = $src_bulan = $this->input->post('src_bulan') ? $this->input->post('src_bulan') : date('m');
        $data["src_tahun"] = $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
        $data["src_level"] = $src_level = $this->input->post('src_level') ? $this->input->post('src_level') : '';
        
        
        $data["data_neracasaldo"] = $this->report_model->getDataNeracaSaldo($src_cabangx, $src_bulan, $src_tahun, $src_level);
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);

        $data["title"] = 'Laporan Neraca Saldo'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/neraca_saldo", $data);
        $this->load->view("layout/footer");

    }

}

?>