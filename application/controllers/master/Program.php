<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Program extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('program_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_data');
        $this->session->set_userdata('sub_menu', 'master/program');
        $userinput = $this->customlib->getSessionUsername();
        
        $data["title"] = "Tambah Program";
        $data["act"] = 'add';
        $data["data_program"] = $this->program_model->getProgram();
        
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode', array('required',
                    array('check_exists', array($this->program_model, 'valid_program'))
                )
        );
        
        if ($this->form_validation->run()) {

            $act = $this->input->post("act");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            if ($act == 'add') {

                if (!$this->rbac->hasPrivilege('master_program', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_program', 'can_edit')) {
                    access_denied();
                }
            }
            
            $data = array();
            
            if ($act == 'add') {
                $data['kode'] = $kode;
                $data['deskripsi'] = $nama;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->program_model->addProgram($data);
                
                $msg = "Tambah data berhasil";
                
            } else {
                
                $data['deskripsi'] = $nama;
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                $this->program_model->editProgram($data, $kode);
                
                $msg = "Update data berhasil";
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/program");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/program", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($kode) {

        $data["title"] = "Edit Program";
        $data["act"] = 'edit';
        $data["result"] = $this->program_model->getProgram($kode);
        $data["data_program"] = $this->program_model->getProgram();
        
        $this->load->view("layout/header");
        $this->load->view("master/program", $data);
        $this->load->view("layout/footer");
    }

    function delete($kode) {

        $this->program_model->deleteProgram($kode);
        redirect('master/program');
    }

}

?>