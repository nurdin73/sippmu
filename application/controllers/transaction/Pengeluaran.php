<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Pengeluaran extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('pengeluaran_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'transaksi_kasir');
        $this->session->set_userdata('sub_menu', 'transaction/pengeluaran');
        
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
        
        $data["cbx_akun_kas"] = $this->pengeluaran_model->getAkunKas($cabang);
        $data["cbx_kode_transaksi"] = $this->pengeluaran_model->getKodeTransaksi();
        
        $this->load->view("layout/header");
        $this->load->view("transaction/pengeluaran", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->pengeluaran_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->pengeluaran_model->getAllDetail();
    }

    function get_data($id) {
        $result = $this->pengeluaran_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->pengeluaran_model->getDataDetail($id);
        echo json_encode($result);
    }

    function get_kode_trx($id) {
        $result = $this->pengeluaran_model->getKodeTransaksi($id);
        echo json_encode($result);
    }

    function closing_transaksi($id, $cabang){
        $result = $this->pengeluaran_model->closingTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    function reopen_transaksi($id, $cabang){
        $result = $this->pengeluaran_model->reopenTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    public function add() {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_add')){
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
            
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
           
            $data = array(
                'no_transaksi' => $this->generateNoTransaksiPengeluaran($cabang, $tanggal),
                'cabang' => $cabang, 
                'tanggal' => date('Y-m-d', strtotime($tanggal)).' '.date('H:i:s'), 
                'keterangan' => $this->input->post("keterangan"), 
                'status' => 1, 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->pengeluaran_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','pengeluaran_idx' => $insert_id);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('pengeluaran_id', 'ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'pengeluaran_id' => form_error('pengeluaran_id'),
                'tanggal' => form_error('tanggal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("pengeluaran_id");
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
            
            $this->pengeluaran_model->editData($data, $id, $cabang, $is_edit_rekap);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function checkSaldoAkhirAkun($jumlah){
        if ($jumlah) {
            $akun_kas = $this->input->post('akun_kas');
            $saldo_akhir = $this->pengeluaran_model->checkSaldoAkhir($akun_kas);
            if($jumlah > $saldo_akhir){
                return false;
            }else{
                return true;
            }
        }
        return false;
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_add') || !$this->rbac->hasPrivilege('trx_pengeluaran', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        
        $this->form_validation->set_rules('kode_trx', 'Kode Transaksi', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas', 'Akun Kas', 'trim|required|xss_clean');
        $this->form_validation->set_rules('jumlah', 'Jumlah Kas', 'trim|required|xss_clean|callback_checkSaldoAkhirAkun');
        $this->form_validation->set_message('checkSaldoAkhirAkun', 'Jumlah melebihi Saldo Akhir Akun Kas!');
        
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
            $detail_id = $this->input->post("pengeluaran_det_id");
            $pengeluaran_id = $this->input->post("pengeluaran_id");
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            
            $data = array(
                'cabang' => $cabang, 
                'pengeluaran' => $pengeluaran_id, 
                //'mutasi_kas_bank' => $this->input->post("mutasi_kas_bank"), //input di mutasi
                'kode_transaksi' => $this->input->post("kode_transaksi"), 
                'akun_kas' => $this->input->post("akun_kas"), 
                'jumlah' => $this->input->post("jumlah"), 
                'keterangan' => $this->input->post("keterangan"), 
            );
            
            if($act == 'add'){
                $data['is_deleted'] = 2;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_pengeluaran', 'can_add')){
                    $detail_idx = $this->pengeluaran_model->addDataDetail($data);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_pengeluaran', 'can_edit')){
                    $detail_idx = $this->pengeluaran_model->editDataDetail($data, $detail_id);
                }
                $msg = 'Edit data ';
            }
            
            if($detail_idx){

                //get total pengeluaran
                $total = $this->pengeluaran_model->getTotalPengeluaran($pengeluaran_id);

                $array = array('status' => 'success', 'error' => '', 'message' => $msg.' berhasil','detail_idx' => $detail_idx,'total' => $total);
            }else{
                $array = array('status' => 'fail', 'error' => '', 'message' => $msg.' gagal','detail_idx' => $detail_idx,'total' => $total);
            }
            
        }

        echo json_encode($array);
    }

    function delete($id) {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->pengeluaran_model->deleteData($id);
            
            if($dodeleted == 'success'){
                $array = array('status' => 'success', 'message' => 'Data berhasil dihapus');
            }else{
                $array = array('status' => 'fail', 'message' => $dodeleted);
            }
            
            echo json_encode($array);
        }
        
    }

    function deleteDetail($id, $pengeluaran_id, $akun_kas, $cabang) {
        if(!$this->rbac->hasPrivilege('trx_pengeluaran', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->pengeluaran_model->deleteDataDetail($id, $pengeluaran_id, $akun_kas, $cabang);
        }
        return 'success';
    }

    function generateNoTransaksiPengeluaran($cabang, $tgl){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'MKK';
        $datel = date('Y-m', strtotime($tgl));
        $dates = date('ym', strtotime($tgl));
        $prefix_cabang = $prefix . $kode_cabang . $dates;
        
        $this->db->where('cabang', $cabang);
        $this->db->like('tanggal::text', $datel, 'both', false);
        $this->db->order_by('id', 'desc')->limit (1);
        $q=$this->db->get('pengeluaran');
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