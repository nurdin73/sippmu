<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Penerimaan extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('penerimaan_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'transaksi_kasir');
        $this->session->set_userdata('sub_menu', 'transaction/penerimaan');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $data["cbx_kode_transaksi"] = $this->penerimaan_model->getKodeTransaksi();
        $data["cbx_akun_kas"] = $this->penerimaan_model->getAkunKas($cabang);
        
        $this->load->view("layout/header");
        $this->load->view("transaction/penerimaan", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->penerimaan_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->penerimaan_model->getAllDetail();
    }

    function get_data($id) {
        $result = $this->penerimaan_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->penerimaan_model->getDataDetail($id);
        echo json_encode($result);
    }

    function get_kode_trx($id) {
        $result = $this->penerimaan_model->getKodeTransaksi($id);
        echo json_encode($result);
    }

    function closing_transaksi($id, $cabang){
        $result = $this->penerimaan_model->closingTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    function reopen_transaksi($id, $cabang){
        $result = $this->penerimaan_model->reopenTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    public function add() {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'cabang' => form_error('cabang'),
                'tanggal' => form_error('tanggal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            //print_r($_POST);die();
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            
            $data = array(
                'no_transaksi' => $this->generateNoTransaksiPenerimaan($cabang, $tanggal),
                'cabang' => $cabang, 
                'tanggal' => date('Y-m-d', strtotime($tanggal)).' '.date('H:i:s'), 
                'keterangan' => $this->input->post("keterangan"), 
                'status' => 1, 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->penerimaan_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','penerimaan_idx' => $insert_id);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('penerimaan_id', 'ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'penerimaan_id' => form_error('penerimaan_id'),
                'tanggal' => form_error('tanggal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("penerimaan_id");
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            $tanggal_before = $this->input->post("tanggal_before");
            
            $is_edit_rekap = false;
            if($tanggal != $tanggal_before){
                $is_edit_rekap = true;
            }
            
            $data = array(
                'tanggal' => date('Y-m-d', strtotime($tanggal)).' '.date('H:i:s'), 
                'keterangan' => $this->input->post("keterangan"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->penerimaan_model->editData($data, $id, $cabang, $is_edit_rekap);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_add') || !$this->rbac->hasPrivilege('trx_penerimaan', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        
        $this->form_validation->set_rules('kode_trx', 'Kode Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas', 'Akun Kas', 'trim|required|xss_clean');
        $this->form_validation->set_rules('jumlah', 'Jumlah Kas', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'kode_trx' => form_error('kode_trx'),
                'akun_kas' => form_error('akun_kas'),
                'jumlah' => form_error('jumlah'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $act = $this->input->post("act");
            $detail_idx = '';
            $detail_id = $this->input->post("penerimaan_det_id");
            $penerimaan_id = $this->input->post("penerimaan_id");
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            
            $data = array(
                'cabang' => $cabang, 
                'penerimaan' => $penerimaan_id, 
                //'mutasi_kas_bank' => $this->input->post("mutasi_kas_bank"), //input di mutasi
                'kode_transaksi' => $this->input->post("kode_transaksi"), 
                'akun_kas' => $this->input->post("akun_kas"), 
                'jumlah' => $this->input->post("jumlah"), 
                'keterangan' => $this->input->post("keterangan"), 
            );
	   //print_r($data);           
            if($act == 'add'){
                $data['is_deleted'] = 2;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_penerimaan', 'can_add')){
		//print_r($data);die();       
			$detail_idx = $this->penerimaan_model->addDataDetail($data);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_penerimaan', 'can_edit')){
                    $detail_idx = $this->penerimaan_model->editDataDetail($data, $detail_id);
                }
                $msg = 'Edit data ';
            }
            
            if($detail_idx){
                
                //get total penerimaan
                $total = $this->penerimaan_model->getTotalPenerimaan($penerimaan_id);

                $array = array('status' => 'success', 'error' => '', 'message' => $msg.' berhasil','detail_idx' => $detail_idx,'total' => $total);
            }else{
                $array = array('status' => 'fail', 'error' => '', 'message' => $msg.' gagal','detail_idx' => '','total' => 0);
            }
            
        }

        echo json_encode($array);
    }

    function delete($id) {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->penerimaan_model->deleteData($id);
            
            if($dodeleted == 'success'){
                $array = array('status' => 'success', 'message' => 'Data berhasil dihapus');
            }else{
                $array = array('status' => 'fail', 'message' => $dodeleted);
            }
            
            echo json_encode($array);
        }
        
    }

    function deleteDetail($id, $penerimaan_id, $akun_kas, $cabang) {
        if(!$this->rbac->hasPrivilege('trx_penerimaan', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->penerimaan_model->deleteDataDetail($id, $penerimaan_id, $akun_kas, $cabang);
        }
        return 'success';
    }

    function generateNoTransaksiPenerimaan($cabang, $tgl){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'MKM';
        $datel = date('Y-m', strtotime($tgl));
        $dates = date('ym', strtotime($tgl));
        $prefix_cabang = $prefix . $kode_cabang . $dates;
        
        $this->db->where('cabang', $cabang);
        $this->db->like('tanggal::text', $datel, 'both', false);
        $this->db->order_by('id', 'desc')->limit (1);
        $q=$this->db->get('penerimaan');
        $n=$q->num_rows();
        if(!empty($n)){
            $d=$q->row();
            
            $last_nomor = $d->no_transaksi;
            $nomor_urut = str_replace($prefix_cabang, '', $last_nomor);
            $cur_no=(int)$nomor_urut;
            $next=$cur_no+1;
            $number = $prefix_cabang.sprintf("%04d",$next);
        }
        else{
            $number = $prefix_cabang."0001";
        }
        
        return $number;
    }
    
}

?>
