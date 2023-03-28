<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Tipe_jurnal extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('tipe_jurnal_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/tipe_jurnal');
        $userinput = $this->customlib->getSessionUsername();
        
        $data["title"] = "Master Tipe Jurnal";
        $data["title_add"] = "Tambah Tipe Jurnal";
        $data["act"] = 'add';
        $data["data_tipe_jurnal"] = $this->tipe_jurnal_model->getTipe_jurnal();
        
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('singkatan', 'Singkatan', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode', array('required',
                    array('check_exists', array($this->tipe_jurnal_model, 'valid_tipe_jurnal'))
                )
        );
        
        if ($this->form_validation->run()) {

            $act = $this->input->post("act");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            $singkatan = $this->input->post("singkatan");
            if ($act == 'add') {

                if (!$this->rbac->hasPrivilege('master_tipe_jurnal', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_tipe_jurnal', 'can_edit')) {
                    access_denied();
                }
            }
            
            $data = array(
                'nama' => $nama, 
                'singkatan' => $singkatan
            );
            
            if ($act == 'add') {
                $data['kode'] = $kode;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->tipe_jurnal_model->addTipe_jurnal($data);
                
                $msg = "Tambah data berhasil";
                
            } else {
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                $this->tipe_jurnal_model->editTipe_jurnal($data, $kode);
                
                $msg = "Update data berhasil";
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/tipe_jurnal");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/tipe_jurnal", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($kode) {
        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/tipe_jurnal');
        
        $data["title"] = "Master Tipe Jurnal";
        $data["title_add"] = "Edit Tipe Jurnal";
        $data["act"] = 'edit';
        $data["result"] = $this->tipe_jurnal_model->getTipe_jurnal($kode);
        $data["data_tipe_jurnal"] = $this->tipe_jurnal_model->getTipe_jurnal();
        
        $this->load->view("layout/header");
        $this->load->view("master/tipe_jurnal", $data);
        $this->load->view("layout/footer");
    }

    function delete($kode) {
        if (!$this->rbac->hasPrivilege('master_tipe_jurnal', 'can_delete')) {
            access_denied();
        }
        $this->tipe_jurnal_model->deleteTipe_jurnal($kode);
        redirect('master/tipe_jurnal');
    }

}

?>