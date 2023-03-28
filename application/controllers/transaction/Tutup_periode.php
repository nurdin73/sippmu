<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Tutup_periode extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->model('tutup_periode_model');
    }

    function index() {

        if(!$this->rbac->hasPrivilege('trx_tutup_periode', 'can_view')){
            access_denied();
        }
        $this->session->set_userdata('top_menu', 'transaksi_akuntansi');
        $this->session->set_userdata('sub_menu', 'transaction/tutup_periode');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["title"] = "Tutup Periode";
        $data["data_setup"] = $this->tutup_periode_model->getData($cabang);
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $month_list = $this->customlib->getMonthList();
        $data['monthList'] = $month_list;
        
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
        $this->form_validation->set_rules('bln_tutup_periode', 'Bulan', 'trim|required|xss_clean');
        $this->form_validation->set_rules('thn_tutup_periode', 'Tahun', 'trim|required|xss_clean');
        
        if ($this->form_validation->run()) {

            if (!$this->rbac->hasPrivilege('trx_tutup_periode', 'can_edit')) {
                access_denied();
            }
            
            if ($this->form_validation->run() == FALSE) {

                $msg = array(
                    'cabang' => form_error('cabang'),
                    'bln_tutup_periode' => form_error('bln_tutup_periode'),
                    'thn_tutup_periode' => form_error('thn_tutup_periode'),
                );

                $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
                echo json_encode($array);
                
            } else {
                $cabang_send = $this->input->post("cabang");
                $bln_tutup_periode = $this->input->post("bln_tutup_periode") .'/'.$this->input->post("thn_tutup_periode");
                
                $this->tutup_periode_model->tutupPeriode($cabang_send, $bln_tutup_periode);

                $msg = "Update data berhasil";
                $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
                redirect("transaction/tutup_periode");
            }
            
        } else {

            $this->load->view("layout/header");
            $this->load->view("transaction/tutup_periode", $data);
            $this->load->view("layout/footer");
        }
    }

    
    function closing_tutup_periode($cabang='', $bulan='', $tahun='') {
              
        $result = $this->tutup_periode_model->closingTutupPeriode($cabang, $bulan, $tahun);
        echo json_encode($result);
    }


}

?>