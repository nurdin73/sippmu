<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Setup_periode_laporan extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('setup_periode_laporan_model');
    }

    function index() {

        if(!$this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_view')){
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/setup_periode_laporan');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        //$userinput = $this->customlib->getSessionUsername();
        //$cabang = $this->customlib->getSessionCabang();
        //$is_pusat = $this->customlib->getSessionIsPusat();
       
        
        $data["title"] = "Setup Periode Laporan";
        $data["data_setup"] = $this->setup_periode_laporan_model->getData($cabang);
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $this->form_validation->set_rules('cabangx', 'Cabang', 'trim|required|xss_clean');
        //this validation not working because on ajax load file
//        $this->form_validation->set_rules('bln_aktif_sebelumnyax', 'Bulan aktif sebelumnya', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('thn_aktif_sebelumnyax', 'Tahun aktif sebelumnya', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('bln_aktif_saatinix', 'Bulan aktif saat ini', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('thn_aktif_saatinix', 'Tahun aktif saat ini', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('bln_aktif_akandatangx', 'Bulan aktif akan datang', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('thn_aktif_akandatangx', 'Tahun aktif akan datang', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('bln_periode_saldo_awalx', 'Bulan periode saldo awal', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('thn_periode_saldo_awalx', 'Tahun periode saldo awal', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('bln_bulan_berjalanx', 'Bulan berjalan', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('thn_bulan_berjalanx', 'Tahun berjalan', 'trim|required|xss_clean');
//        $this->form_validation->set_rules('currency', 'Mata Uang', 'trim|required|xss_clean');
        
        
        
        if ($this->form_validation->run()) {

            if (!$this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_edit')) {
                access_denied();
            }
            

            if ($this->form_validation->run() == FALSE) {

                $msg = array(
                    'cabangx' => form_error('cabangx'),
//                    'currency' => form_error('currency'),
//                    'bln_aktif_sebelumnyax' => form_error('bln_aktif_sebelumnyax'),
//                    'thn_aktif_sebelumnyax' => form_error('thn_aktif_sebelumnyax'),
//                    'bln_aktif_saatinix' => form_error('bln_aktif_saatinix'),
//                    'thn_aktif_saatinix' => form_error('thn_aktif_saatinix'),
//                    'bln_aktif_akandatangx' => form_error('bln_aktif_akandatangx'),
//                    'thn_aktif_akandatangx' => form_error('thn_aktif_akandatangx'),
//                    'bln_periode_saldo_awalx' => form_error('bln_periode_saldo_awalx'),
//                    'thn_periode_saldo_awalx' => form_error('thn_periode_saldo_awalx'),
//                    'bln_bulan_berjalanx' => form_error('bln_bulan_berjalanx'),
//                    'thn_bulan_berjalanx' => form_error('thn_bulan_berjalanx'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
                echo json_encode($array);
                
            } else {
                
                $bln_aktif_sebelumnya = $this->input->post("bln_aktif_sebelumnyax") .'/'.$this->input->post("thn_aktif_sebelumnyax");
                $bln_aktif_saatini = $this->input->post("bln_aktif_saatinix") .'/'.$this->input->post("thn_aktif_saatinix");
                $bln_aktif_akandatang = $this->input->post("bln_aktif_akandatangx") .'/'.$this->input->post("thn_aktif_akandatangx");
                $periode_saldo_awal = $this->input->post("bln_periode_saldo_awalx") .'/'.$this->input->post("thn_periode_saldo_awalx");
                $bulan_berjalan = $this->input->post("bln_bulan_berjalanx") .'/'.$this->input->post("thn_bulan_berjalanx");
                $currency = $this->input->post("currency") ? $this->input->post("currency") : 'IDR';
                
                $closing_saldo_awal = $this->input->post("closing_saldo_awal") ? $this->input->post("closing_saldo_awal") : '';
                $cabangx = $this->input->post("cabangx");
                $cabang = $this->input->post("cabang");
                
                if($cabangx == $cabang){
                    $data = array();
                    //$data['cabang'] = $cabangx;
                    $data['bln_aktif_sebelumnya'] = $bln_aktif_sebelumnya;
                    $data['bln_aktif_saatini'] = $bln_aktif_saatini;
                    $data['bln_aktif_akandatang'] = $bln_aktif_akandatang;
                    $data['bulan_berjalan'] = $bulan_berjalan;
                    $data['tutup_bulan_lap'] = $this->input->post("tutup_bulan_lap");
                    $data['tutup_bulan_lapinterim'] = $this->input->post("tutup_bulan_lapinterim");
                    $data['tutup_bulan_laptahunan'] = $this->input->post("tutup_bulan_laptahunan");
                    $data['tutup_bulan_lapkap'] = $this->input->post("tutup_bulan_lapkap");
                    $data['currency'] = $currency;
                    $data['modified_by'] = $userinput;
                    $data['modified_date'] = date('Y-m-d H:i:s');

                    if(empty($closing_saldo_awal) || $closing_saldo_awal == 'f'){
                        $data['periode_saldo_awal'] = $periode_saldo_awal;
                        $data['tutup_periode_saldoawal'] = $this->input->post("tutup_periode_saldoawal"); //diset di saldo awal
                    }

                    $this->setup_periode_laporan_model->editData($data, $cabangx);
                    $msg = "Update data berhasil";
                    $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
                }else{
                    $msg = "Update data gagal";
                    $this->session->set_flashdata('msg', '<div class="alert alert-error">'.$msg.'</div>');
                }
                
                
                redirect("master/setup_periode_laporan");
            }
            
        }else{
            
            $this->load->view("layout/header");
            $this->load->view("master/setup_periode_laporan", $data);
            $this->load->view("layout/footer");

        }
        
    }

    
    function cab($cabang2='') {
        
        if(!$this->rbac->hasPrivilege('master_setup_periode_laporan', 'can_view')){
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/setup_periode_laporan');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        //$userinput = $this->customlib->getSessionUsername();
        //$cabang = $this->customlib->getSessionCabang();
        //$is_pusat = $this->customlib->getSessionIsPusat();
        if($is_pusat){
            $cabangx = $cabang2 ? $cabang2 : $cabang;
        }else{
            $cabangx = $cabang;
        }
        
        $data["title"] = "Setup Periode Laporan";
        $data["data_setup"] = $this->setup_periode_laporan_model->getData($cabangx);
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabangx;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $month_list = $this->customlib->getMonthList();
        $data['monthList'] = $month_list;
        
        $data["cbx_currency"] = $this->setup_periode_laporan_model->getCurrency();
        

        //$this->load->view("layout/header");
        $this->load->view("master/setup_periode_data", $data);
        //$this->load->view("layout/footer");

    }

}

?>