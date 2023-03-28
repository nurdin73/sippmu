<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Penerimaan extends Admin_Controller {

    function __construct() {

        parent::__construct();
        $this->load->model('report_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('report_penerimaan', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'laporan_kas_bank');
        $this->session->set_userdata('sub_menu', 'laporan/penerimaan');

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
        
        $src_date1 = $this->input->post('src_date1') ? $this->input->post('src_date1') : date('01-m-Y');
        $src_date2 = $this->input->post('src_date2') ? $this->input->post('src_date2') : date('d-m-Y');
        $src_akun_kas = $this->input->post('src_akun_kas') ? $this->input->post('src_akun_kas') : '';
        $src_user = $this->input->post('src_user') ? $this->input->post('src_user') : '';
        $src_text = $this->input->post('src_text') ? $this->input->post('src_text') : '';
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $data["data_penerimaan"] = $this->report_model->getDataPenerimaan($src_cabangx, $src_date1, $src_date2, $src_akun_kas, $src_user, $src_text);

        $data["src_date1"] = $src_date1;
        $data["src_date2"] = $src_date2;
        $data["src_akun_kas"] = $src_akun_kas;
        $data["src_user"] = $src_user;
        $data["src_text"] = $src_text;
        
        $data["title"] = 'Laporan Penerimaan'; 
        
        $this->load->view("layout/header");
        $this->load->view("laporan/penerimaan", $data);
        $this->load->view("layout/footer");

    }

    
    function printView() {
        if (!$this->rbac->hasPrivilege('report_penerimaan', 'can_view')) {
            access_denied();
        }
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["src_cabangx"] = $src_cabangx = $this->input->post('src_cabangx') ? $this->input->post('src_cabangx') : $cabang;
        $data["src_date1"] = $src_date1 = $this->input->post('src_date1') ? $this->input->post('src_date1') : date('01-m-Y');
        $data["src_date2"] = $src_date2 = $this->input->post('src_date2') ? $this->input->post('src_date2') : date('d-m-Y');
        $data["src_akun_kas"] = $src_akun_kas = $this->input->post('src_akun_kas') ? $this->input->post('src_akun_kas') : '';
        $data["src_user"] = $src_user = $this->input->post('src_user') ? $this->input->post('src_user') : '';
        $data["src_text"] = $src_text = $this->input->post('src_text') ? $this->input->post('src_text') : '';
        
        $data["data_cabang"] = $this->report_model->getCabangByID($src_cabangx);
        $result = $this->report_model->getDataPenerimaan($src_cabangx, $src_date1, $src_date2, $src_akun_kas, $src_user, $src_text);
        $data["data_penerimaan"] = $result;
        $data["result"] = $result;
        
        //print_r($result);
        if (!empty($result)) {
            $this->load->view("laporan/penerimaan_view", $data);
        } else {
            echo $this->lang->line('no_record_found');
        }
    }

}

?>