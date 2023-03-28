<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Buku_besar extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_buku_besar', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/buku_besar');

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
        
        $data["cbx_rekening"] = $this->report_model->getKodeRekening();
        $data['cbx_bulan'] = $this->customlib->getMonthList();
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $src_bulan = $this->input->post('src_bulan') ? $this->input->post('src_bulan') : '';
        $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : '';
        
        
        $data["title"] = 'Laporan Buku Besar';    
        
        $this->load->view("layout/header");
        $this->load->view("laporan/buku_besar", $data);
        $this->load->view("layout/footer");

    }

}

?>