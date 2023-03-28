<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Cabang_klasifikasi extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('cabang_klasifikasi_model');
    }

    function index() {

        $this->session->set_userdata('top_menu', 'master_data');
        $this->session->set_userdata('sub_menu', 'master/cabang_klasifikasi');

        $data["title"] = "Master Group Unit";
        $data["title_add"] = "Tambah Group Unit";
        $data["act"] = 'add';
        $data["data_cabang"] = $this->cabang_klasifikasi_model->getCabangKlasifikasi();
        
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Cabang', array('required',
                    array('check_exists', array($this->cabang_klasifikasi_model, 'valid_cabang'))
                )
        );
        
        if ($this->form_validation->run()) {

            $id = $this->input->post("id");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            $is_active = $this->input->post("is_active");
            if (empty($id)) {

                if (!$this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_add')) {
                    access_denied();
                }
            } else {

                if (!$this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_edit')) {
                    access_denied();
                }
            }
            if (!empty($id)) {
                $data = array('kode' => $kode, 'nama' => $nama, 'is_active' => $is_active, 'id' => $id);
                $msg = "Update data berhasil";
            } else {
                $msg = "Tambah data berhasil";
                $data = array('kode' => $kode, 'nama' => $nama, 'is_active' => $is_active);
            }
            $insert_id = $this->cabang_klasifikasi_model->addCabangKlasifikasi($data);
            $this->session->set_flashdata('msg', '<div class="alert alert-success">'.$msg.'</div>');
            redirect("master/cabang_klasifikasi");
        } else {

            $this->load->view("layout/header");
            $this->load->view("master/cabang_klasifikasi", $data);
            $this->load->view("layout/footer");
        }
    }

    function edit($id) {
        $this->session->set_userdata('top_menu', 'master_data');
        $this->session->set_userdata('sub_menu', 'master/cabang_klasifikasi');
        
        $data["title"] = "Master Group Unit";
        $data["title_add"] = "Edit Group Unit";
        $data["act"] = 'edit';
        $data["result"] = $this->cabang_klasifikasi_model->getCabangKlasifikasi($id);
        $data["data_cabang"] = $this->cabang_klasifikasi_model->getCabangKlasifikasi();
        
        $this->load->view("layout/header");
        $this->load->view("master/cabang_klasifikasi", $data);
        $this->load->view("layout/footer");
    }

    function delete($id) {
        if (!$this->rbac->hasPrivilege('master_cabang_klasifikasi', 'can_delete')) {
            access_denied();
        }
        $this->cabang_klasifikasi_model->deleteCabangKlasifikasi($id);
        redirect('master/cabang_klasifikasi');
    }

}

?>