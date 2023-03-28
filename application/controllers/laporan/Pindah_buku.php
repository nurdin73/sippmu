<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pindah_buku extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_pindah_buku', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_kas_bank');
        $this->session->set_userdata('sub_menu', 'laporan/pindah_buku');

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
        
        $data["cbx_akun_kas"] = $this->report_model->getAkunKas($src_cabangx);
        $data["cbx_user"] = $this->report_model->getUserCabang($src_cabangx);
        
        $data["src_date1"] = $src_date1 = $this->input->post('src_date1') ? $this->input->post('src_date1') : date('01-m-Y');
        $data["src_date2"] = $src_date2 = $this->input->post('src_date2') ? $this->input->post('src_date2') : date('d-m-Y');
        $data["src_akun_kas"] = $src_akun_kas = $this->input->post('src_akun_kas') ? $this->input->post('src_akun_kas') : '';
        $data["src_akun_kas2"] = $src_akun_kas2 = $this->input->post('src_akun_kas2') ? $this->input->post('src_akun_kas2') : '';
        $data["src_user"] = $src_user = $this->input->post('src_user') ? $this->input->post('src_user') : '';
        $data["src_text"] = $src_text = $this->input->post('src_text') ? $this->input->post('src_text') : '';
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_pindah_buku"] = $this->report_model->getDataPindahBuku($src_cabangx, $src_date1, $src_date2, $src_akun_kas, $src_akun_kas2, $src_user, $src_text);

        $data["title"] = 'Laporan Pindah Buku'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/pindah_buku", $data);
        $this->load->view("layout/footer");

    }

}

?>