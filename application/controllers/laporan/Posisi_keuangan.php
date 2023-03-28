<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Posisi_keuangan extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_posisi_keuangan', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_akuntansi');
        $this->session->set_userdata('sub_menu', 'laporan/posisi_keuangan');

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
        
        $data['cbx_bulan'] = $this->customlib->getMonthList();
        $data["cbx_tahun"] = $this->report_model->getTahun($src_cabangx);
        
        $data["src_bulan"] = $src_bulan = $this->input->post('src_bulan') ? $this->input->post('src_bulan') : date('m');
        $data["src_tahun"] = $src_tahun = $this->input->post('src_tahun') ? $this->input->post('src_tahun') : date('Y');
        
        $tanggal1 = $src_tahun.'-'.$src_bulan.'-01';
        $tanggal2 = $this->customlib->lastDayOfTheMonth($tanggal1);
        $tanggal3 = $this->customlib->lastDayOfTheMonthYearBefore($tanggal2); 
        $data["header_date_src"] = date('d M Y', strtotime($tanggal2));
        $data["header_date_before"] = date('d M Y', strtotime($tanggal3));
        
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        
        $data["data_report"] = $this->report_model->getDataPosisiKeuangan($src_cabangx, $src_tahun, $src_bulan);

        $data["title"] = 'Laporan Posisi Keuangan';//'Laporan Neraca'; 
        $data["laporan_name"] = 'LAPORAN POSISI KEUANGAN';//'LAPORAN NERACA'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/posisi_keuangan", $data);
        $this->load->view("layout/footer");

    }

}

?>