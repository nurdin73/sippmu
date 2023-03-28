<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Arus_kas_tahun extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_arus_kas_tahun', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/arus_kas_tahun');

        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
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
        
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $data["src_tahun"] = $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
        $data["src_jenis"] = $src_jenis = $this->input->post('src_jenis') ? $this->input->post('src_jenis') : '';
        
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_dana"] = $this->report_model->getDanaByID($src_jenis);
        $data["data_arus_kas"] = $this->report_model->getDataArusKas();
        $data["data_saldo_awal"] = $this->report_model->getSaldoAwalArusKasTahun($src_cabangx, $src_tahun);
        $data["data_report"] = $this->report_model->getReportArusKasTahun($src_cabangx, $src_tahun);

        $data["title"] = 'Laporan Arus Kas Tahunan'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/arus_kas_tahun", $data);
        $this->load->view("layout/footer");

    }
    

}

?>