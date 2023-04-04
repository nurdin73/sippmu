<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}
/**
 * 
 */
class Cabang extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('cabang_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('master_cabang', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'master_data');
        $this->session->set_userdata('sub_menu', 'master/cabang');
        $userinput = $this->customlib->getSessionUsername();

        $data["title"] = "Master Unit Kerja";

        $data["klasifikasis"] = $this->cabang_model->getCabangklasifikasi();
        $data["parents"] = $this->cabang_model->getCabangParent();     
        
        $this->load->view("layout/header");
        $this->load->view("master/cabang", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->cabang_model->getall();
    }

    function get_data($id) {
        $result = $this->cabang_model->getData($id);
        echo json_encode($result);
    }

    function get_all_data()
    {
        $filter = $this->input->get("search");
        $result = $this->cabang_model->getData(null, $filter);
        echo json_encode($result);
    }

    public function add() {
        if(!$this->rbac->hasPrivilege('master_cabang', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('klasifikasi', 'Klasifikasi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('no_telp', 'Telepon', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Cabang', array('required', 'max_length[25]',
                    array('check_exists', array($this->cabang_model, 'valid_cabang'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'klasifikasi' => form_error('klasifikasi'),
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'no_telp' => form_error('no_telp'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {

            $klasifikasi = $this->input->post("klasifikasi");
            $parent = $this->input->post("parent");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            $alamat = $this->input->post("alamat");
            $no_telp = $this->input->post("no_telp");
            $is_active = $this->input->post("is_active");
            $fax = $this->input->post("fax");
            $website = $this->input->post("website");
            $is_pusat = $this->input->post("is_pusat");
            
            $data = array(
                'klasifikasi' => $klasifikasi, 
                'parent' => $parent, 
                'kode' => $kode, 
                'nama' => $nama, 
                'alamat' => $alamat, 
                'no_telp' => $no_telp, 
                'fax' => $fax, 
                'website' => $website, 
                'is_pusat' => $is_pusat, 
                'is_active' => $is_active,
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->cabang_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil');
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('master_cabang', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('nama', 'Nama', 'trim|required|xss_clean');
        $this->form_validation->set_rules('klasifikasi', 'Klasifikasi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('no_telp', 'Telepon', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode', 'Kode Cabang', array('required', 'max_length[25]',
                    array('check_exists', array($this->cabang_model, 'valid_cabang'))
                )
        );
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'klasifikasi' => form_error('klasifikasi'),
                'kode' => form_error('kode'),
                'nama' => form_error('nama'),
                'no_telp' => form_error('no_telp'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
        } else {
            
            $id = $this->input->post("idx");
            $klasifikasi = $this->input->post("klasifikasi");
            $parent = $this->input->post("parent");
            $kode = $this->input->post("kode");
            $nama = $this->input->post("nama");
            $alamat = $this->input->post("alamat");
            $no_telp = $this->input->post("no_telp");
            $is_active = $this->input->post("is_active");
            $fax = $this->input->post("fax");
            $website = $this->input->post("website");
            $is_pusat = $this->input->post("is_pusat");
            
            $data = array(
                'klasifikasi' => $klasifikasi, 
                'parent' => $parent, 
                'kode' => $kode, 
                'nama' => $nama, 
                'alamat' => $alamat, 
                'no_telp' => $no_telp, 
                'fax' => $fax, 
                'website' => $website, 
                'is_pusat' => $is_pusat, 
                'is_active' => $is_active,
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->cabang_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function delete($id) {
        if(!$this->rbac->hasPrivilege('master_cabang', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->cabang_model->deleteData($id);
        }
        redirect('master/cabang');
    }

}

?>