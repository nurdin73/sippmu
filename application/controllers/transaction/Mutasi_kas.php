<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mutasi_kas extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('mutasi_kas_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_kas', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'transaksi_kasir');
        $this->session->set_userdata('sub_menu', 'transaction/mutasi_kas');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["cbx_kode_transaksi_masuk"] = $this->mutasi_kas_model->getKodeTransaksiMasuk();
        $data["cbx_kode_transaksi_keluar"] = $this->mutasi_kas_model->getKodeTransaksiKeluar();
        $data["cbx_akun_kas"] = $this->mutasi_kas_model->getAkunKas($cabang);
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $this->load->view("layout/header");
        $this->load->view("transaction/mutasi_kas", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mutasi_kas_model->getAll();
    }
    
    function get_data($id) {
        $result = $this->mutasi_kas_model->getData($id);
        echo json_encode($result);
    }

    function closing_transaksi($id, $cabang){
        $result = $this->mutasi_kas_model->closingTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    function reopen_transaksi($id, $cabang){
        $result = $this->mutasi_kas_model->reopenTransaksi($id, $cabang);
        echo json_encode($result);
    }
    
    function checkSaldoAkhirAkun($jumlah){
        if ($jumlah) {
            $akun_kas = $this->input->post('akun_kas_keluar');
            $saldo_akhir = $this->mutasi_kas_model->checkSaldoAkhir($akun_kas);
            if($jumlah > $saldo_akhir){
                return false;
            }else{
                return true;
            }
        }
        return false;
    }
    
    public function add() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_kas', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas_masuk', 'Akun kas masuk', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas_keluar', 'Akun kas keluar', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode_transaksi_masuk', 'Kode transaksi masuk', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode_transaksi_keluar', 'Kode transaksi keluar', 'trim|required|xss_clean');
        $this->form_validation->set_rules('jumlah', 'Jumlah Kas', 'trim|required|xss_clean|callback_checkSaldoAkhirAkun');
        $this->form_validation->set_message('checkSaldoAkhirAkun', 'Jumlah melebihi Saldo Akhir Akun Kas Keluar!');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'cabang' => form_error('cabang'),
                'tanggal' => form_error('tanggal'),
                'akun_kas_masuk' => form_error('akun_kas_masuk'),
                'akun_kas_keluar' => form_error('akun_kas_keluar'),
                'kode_transaksi_masuk' => form_error('kode_transaksi_masuk'),
                'kode_transaksi_keluar' => form_error('kode_transaksi_keluar'),
                'jumlah' => form_error('jumlah'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
           
            $data = array(
                'no_transaksi' => $this->generateNoTransaksiMutasiKas($cabang, $tanggal),
                'cabang' => $cabang, 
                'tanggal' => date('Y-m-d', strtotime($tanggal)).' '.date('H:i:s'), 
                'kode_transaksi_masuk' => $this->input->post("kode_transaksi_masuk"), 
                'kode_transaksi_keluar' => $this->input->post("kode_transaksi_keluar"), 
                'akun_kas_masuk' => $this->input->post("akun_kas_masuk"), 
                'akun_kas_keluar' => $this->input->post("akun_kas_keluar"), 
                'jumlah' => $this->input->post("jumlah"), 
                'keterangan' => $this->input->post("keterangan"), 
                'status' => 1, 
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );
            
            $insert_id = $this->mutasi_kas_model->addData($data);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','mutasi_kas_idx' => $insert_id);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_kas', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas_masuk', 'Akun kas masuk', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun_kas_keluar', 'Akun kas keluar', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode_transaksi_masuk', 'Kode transaksi masuk', 'trim|required|xss_clean');
        $this->form_validation->set_rules('kode_transaksi_keluar', 'Kode transaksi keluar', 'trim|required|xss_clean');
        $this->form_validation->set_rules('mutasi_kas_id', 'ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('jumlah', 'Jumlah Kas', 'trim|required|xss_clean|callback_checkSaldoAkhirAkun');
        $this->form_validation->set_message('checkSaldoAkhirAkun', 'Jumlah melebihi Saldo Akhir Akun Kas Keluar!');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'cabang' => form_error('cabang'),
                'tanggal' => form_error('tanggal'),
                'akun_kas_masuk' => form_error('akun_kas_masuk'),
                'akun_kas_keluar' => form_error('akun_kas_keluar'),
                'kode_transaksi_masuk' => form_error('kode_transaksi_masuk'),
                'kode_transaksi_keluar' => form_error('kode_transaksi_keluar'),
                'jumlah' => form_error('jumlah'),
                'mutasi_kas_id' => form_error('mutasi_kas_id'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("mutasi_kas_id");
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            
            $data = array(
                'tanggal' => date('Y-m-d', strtotime($tanggal)).' '.date('H:i:s'), 
                'kode_transaksi_masuk' => $this->input->post("kode_transaksi_masuk"), 
                'kode_transaksi_keluar' => $this->input->post("kode_transaksi_keluar"), 
                'akun_kas_masuk' => $this->input->post("akun_kas_masuk"), 
                'akun_kas_keluar' => $this->input->post("akun_kas_keluar"), 
                'jumlah' => $this->input->post("jumlah"), 
                'keterangan' => $this->input->post("keterangan"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->mutasi_kas_model->editData($data, $id, $cabang);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    function delete($id, $cabang) {
        if(!$this->rbac->hasPrivilege('trx_mutasi_kas', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->mutasi_kas_model->deleteData($id, $cabang);
            
            if($dodeleted == 'success'){
                $array = array('status' => 'success', 'message' => 'Data berhasil dihapus');
            }else{
                $array = array('status' => 'fail', 'message' => $dodeleted);
            }
            
            echo json_encode($array);
        }
        
    }

    function generateNoTransaksiMutasiKas($cabang, $tgl){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'MKB';
        $datel = date('Y-m', strtotime($tgl));
        $dates = date('ym', strtotime($tgl));
        $prefix_cabang = $prefix . $kode_cabang . $dates;
        
        $this->db->where('cabang', $cabang);
        $this->db->like('tanggal::text', $datel, 'both', false);
        $this->db->order_by('id', 'desc')->limit (1);
        $q=$this->db->get('mutasi_kas_bank');
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