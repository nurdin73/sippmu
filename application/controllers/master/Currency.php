<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Currency extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('currency_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/currency');
        $userinput = $this->customlib->getSessionUsername();
        
        $data["title"] = "Master Mata Uang";
        $data["title_add"] = "Tambah Mata Uang";
        $data["act"] = 'add';
        $data["data_currency"] = $this->currency_model->getCurrency();
        
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode', array('required',
                    array('check_exists', array($this->currency_model, 'valid_currency'))
                )
        );
        
        if ($this->form_validation->run()) {

            $act = $this->input->post("act");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            if ($act == 'add') {

                if (!$this->rbac->hasPrivilege('master_currency', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_currency', 'can_edit')) {
                    access_denied();
                }
            }
            
            $data = array();
            
            if ($act == 'add') {
                $data['kode'] = $kode;
                $data['deskripsi'] = $nama;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->currency_model->addCurrency($data);
                
                $msg = "Tambah data berhasil";
                
            } else {
                
                $data['deskripsi'] = $nama;
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                $this->currency_model->editCurrency($data, $kode);
                
                $msg = "Update data berhasil";
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/currency");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/currency", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($kode) {
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/currency');
        
        $data["title"] = "Master Mata Uang";
        $data["title_add"] = "Edit Mata Uang";
        $data["act"] = 'edit';
        $data["result"] = $this->currency_model->getCurrency($kode);
        $data["data_currency"] = $this->currency_model->getCurrency();
        
        $this->load->view("layout/header");
        $this->load->view("master/currency", $data);
        $this->load->view("layout/footer");
    }

    function delete($kode) {
        if (!$this->rbac->hasPrivilege('master_currency', 'can_delete')) {
            access_denied();
        }
        $this->currency_model->deleteCurrency($kode);
        redirect('master/currency');
    }

}

?>