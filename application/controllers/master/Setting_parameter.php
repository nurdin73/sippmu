<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Setting_parameter extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('setting_parameter_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_akuntansi');
        $this->session->set_userdata('sub_menu', 'master/setting_parameter');
        $userinput = $this->customlib->getSessionUsername();
        
        $data["title"] = "Setting Parameter";
        $data["act"] = 'add';
        $data["data_parameter"] = $this->setting_parameter_model->getSetting_parameter();
        $data["cbx_kode_rekening"] = $this->setting_parameter_model->getKodeRekening();
        
        $this->form_validation->set_rules('param_name', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('param_type', 'Tipe', 'trim|required|xss_clean');
        $this->form_validation->set_rules('param_code', 'Kode', array('required',
                    array('check_exists', array($this->setting_parameter_model, 'valid_data'))
                )
        );
        
        if ($this->form_validation->run()) {

            $act = $this->input->post("act");
            $param_code = $this->input->post("param_code");
            $param_name = $this->input->post("param_name");
            $param_type = $this->input->post("param_type");
            if ($act == 'add') {

                if (!$this->rbac->hasPrivilege('master_setting_parameter', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_setting_parameter', 'can_edit')) {
                    access_denied();
                }
            }
            
            if($param_type == 'rekening'){
                $param_value = $this->input->post("param_value2");
            }else{
                $param_value = $this->input->post("param_value");
            }
            
            $data = array(
                'param_name' => $param_name, 
                'param_type' => $param_type,
                'param_value' => $param_value
            );
            
            if ($act == 'add') {
                $data['param_code'] = $param_code;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                $this->setting_parameter_model->addSetting_parameter($data);
                
                $msg = "Tambah data berhasil";
                
            } else {
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                $this->setting_parameter_model->editSetting_parameter($data, $param_code);
                
                $msg = "Update data berhasil";
            }
            
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/setting_parameter");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/setting_parameter", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($param_code) {

        $data["title"] = "Edit Setting Parameter";
        $data["act"] = 'edit';
        $data["result"] = $this->setting_parameter_model->getSetting_parameter($param_code);
        $data["data_parameter"] = $this->setting_parameter_model->getSetting_parameter();
        $data["cbx_kode_rekening"] = $this->setting_parameter_model->getKodeRekening();
        
        $this->load->view("layout/header");
        $this->load->view("master/setting_parameter", $data);
        $this->load->view("layout/footer");
    }

    function delete($param_code) {

        $this->setting_parameter_model->deleteSetting_parameter($param_code);
        redirect('master/setting_parameter');
    }

}

?>