<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Perubahan_dana_tahun extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('report_model');
    }
    
    function index() {
        if(!$this->rbac->hasPrivilege('report_perubahan_dana_tahun', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/perubahan_dana_tahun');


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
        
        $data["cbx_dana"] = $this->report_model->getJenisDana();
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $data["src_tahun"] = $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
        $data["src_jenis"] = $src_jenis = $this->input->post('src_jenis') ? $this->input->post('src_jenis') : '';
        
        
        $data["data_jenis_dana"] = $this->report_model->getDataJenisDana($src_jenis);
        $data["data_report"] = $this->report_model->getDataPerubahanDanaTahun($src_cabangx, $src_tahun, $src_jenis);
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_dana"] = $this->report_model->getDanaByID($src_jenis);
        //$data["data_saldo_awal"] = $this->report_model->getSaldoAwalPerubahanDana($src_cabangx, $src_tahun);

        $data["title"] = 'Laporan Perubahan Dana Tahunan'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/perubahan_dana_tahun", $data);
        $this->load->view("layout/footer");

    }
    
}

?>