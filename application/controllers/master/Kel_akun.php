<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Kel_akun extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('kel_akun_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/kel_akun');
        $userinput = $this->customlib->getSessionUsername();
        
        $data["title"] = "Master Kelompok Akun";
        $data["title_add"] = "Tambah Kelompok Akun";
        $data["act"] = 'add';
        $data["data_kel_akun"] = $this->kel_akun_model->getKel_akun();
        
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode', array('required',
                    array('check_exists', array($this->kel_akun_model, 'valid_kel_akun'))
                )
        );
        
        if ($this->form_validation->run()) {

            $act = $this->input->post("act");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            if ($act == 'add') {

                if (!$this->rbac->hasPrivilege('master_kel_akun', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_kel_akun', 'can_edit')) {
                    access_denied();
                }
            }
            
            $data = array();
            
            if ($act == 'add') {
                $data['kode'] = $kode;
                $data['nama'] = $nama;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->kel_akun_model->addKel_akun($data);
                
                $msg = "Tambah data berhasil";
                
            } else {
                
                $data['nama'] = $nama;
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                $this->kel_akun_model->editKel_akun($data, $kode);
                
                $msg = "Update data berhasil";
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/kel_akun");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/kel_akun", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($kode) {
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/kel_akun');
        
        $data["title"] = "Master Kelompok Akun";
        $data["title_add"] = "Edit Kelompok Akun";
        $data["act"] = 'edit';
        $data["result"] = $this->kel_akun_model->getKel_akun($kode);
        $data["data_kel_akun"] = $this->kel_akun_model->getKel_akun();
        
        $this->load->view("layout/header");
        $this->load->view("master/kel_akun", $data);
        $this->load->view("layout/footer");
    }

    function delete($kode) {
        if (!$this->rbac->hasPrivilege('master_kel_akun', 'can_delete')) {
            access_denied();
        }
        $this->kel_akun_model->deleteKel_akun($kode);
        redirect('master/kel_akun');
    }

}

?>