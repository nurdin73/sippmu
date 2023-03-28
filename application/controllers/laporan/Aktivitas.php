<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Aktivitas extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('report_model');
    }
    
    function index() {
        if(!$this->rbac->hasPrivilege('report_aktivitas', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/aktivitas');


        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $cabang_name = $data_session['cabang_name'];
        $is_pusat = $data_session['is_pusat'];
        
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
            $src_cabangx = $this->input->post('src_cabangx') ? $this->input->post('src_cabangx') : '';
        }else{
            $data['is_disabled'] = 'disabled="disabled"';
            $src_cabangx = $this->input->post('src_cabangx') ? $this->input->post('src_cabangx') : $cabang;
        }

            
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $src_cabangx;
        $data['cabang_name'] = $cabang_name;
        
        $data['cbx_bulan'] = $this->customlib->getMonthList();
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $data["src_bulan"] = $src_bulan = $this->input->post('src_bulan') ? $this->input->post('src_bulan') : date('m');
        $data["src_tahun"] = $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
        
        $data["data_report"] = $this->report_model->getDataAktivitas($src_cabangx, $src_tahun);
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
       
        $data["title"] = 'Laporan Aktivitas'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/aktivitas", $data);
        $this->load->view("layout/footer");

    }
    
}

?>