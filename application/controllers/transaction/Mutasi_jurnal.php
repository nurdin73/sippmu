<?php
if (!defined('BASEPATH')) {
    exit('No direct script access allowed');
}

class Mutasi_jurnal extends Admin_Controller {

    function __construct() {

        parent::__construct();

        $this->load->helper('file');
        $this->load->model('mutasi_jurnal_model');
    }

    function index() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_view')){
            access_denied();
        }
        
        $this->session->set_userdata('top_menu', 'transaksi_akuntansi');
        $this->session->set_userdata('sub_menu', 'transaction/mutasi_jurnal');
        
        $data_session = $this->session->userdata('admin');
        $userinput = $data_session['username'];
        $cabang = $data_session['cabang'];
        $is_pusat = $data_session['is_pusat'];
        
        $data["cbx_kode_rekening"] = $this->mutasi_jurnal_model->getKodeRekening();
        $data["cbx_tipe_jurnal"] = $this->mutasi_jurnal_model->getTipeJurnalAll();
        
        $data["cbx_cabang"] = $this->cabang_model->getCabang();
        $data['cabangx'] = $cabang;
        $data['is_disabled'] = 'disabled="disabled"';
        if($is_pusat == 't'){
            $data['is_disabled'] = '';
        }
        
        $this->load->view("layout/header");
        $this->load->view("transaction/mutasi_jurnal", $data);
        $this->load->view("layout/footer");

    }

    function get_ajax() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mutasi_jurnal_model->getAll();
    }
    
    function get_ajax_detail() { //get data and encode to be JSON object
        header('Content-Type: application/json');
        echo $this->mutasi_jurnal_model->getAllDetail();
    }

    function get_data($id) {
        $result = $this->mutasi_jurnal_model->getData($id);
        echo json_encode($result);
    }

    function get_data_detail($id) {
        $result = $this->mutasi_jurnal_model->getDataDetail($id);
        echo json_encode($result);
    }

    function get_kode_rek($id) {
        $result = $this->mutasi_jurnal_model->getKodeRekening($id);
        echo json_encode($result);
    }

    function checkTotal($jurnal_id) {
        $result = $this->mutasi_jurnal_model->getTotalJurnalDetail($jurnal_id);
        echo json_encode($result);
    }

    function posting_jurnal($id, $cabang, $tanggal_jurnal=''){
        $result = $this->mutasi_jurnal_model->postingJurnal($id, $cabang, $tanggal_jurnal);
        echo json_encode($result);
    }
    
    public function add() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_add')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('cabang', 'Cabang', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tipe_jurnal', 'Tipe Jurnal', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'cabang' => form_error('cabang'),
                'tanggal' => form_error('tanggal'),
                'tipe_jurnal' => form_error('tipe_jurnal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            $tanggal = date('Y-m-d', strtotime($tanggal));
            $tipe_jurnal = $this->input->post("tipe_jurnal");
            $keterangan = $this->input->post("keterangan");
           
            $data_jurnal_header = array( 
                'cabang' => $cabang,
                'no_jurnal' => $this->generateNoJurnal($cabang, $tanggal, $tipe_jurnal),
                'tanggal'   => $tanggal.' '.date('H:i:s'), 
                'tipe_jurnal'   => $tipe_jurnal,
                'keterangan'   => $keterangan,
                'total'        => 0,
                'currency'   => 'IDR',
                'nilai_kurs'   => '1',
                'status'   => 1,
                'is_deleted' => 2,
                'created_by' => $userinput,
                'created_date' => date('Y-m-d H:i:s'),
            );

            $insert_id = $this->mutasi_jurnal_model->addData($data_jurnal_header);

            $array = array('status' => 'success', 'error' => '', 'message' => 'Tambah data berhasil','jurnal_idx' => $insert_id);
        }

        echo json_encode($array);
    }

    function edit() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        $this->form_validation->set_rules('jurnal_id', 'ID', 'trim|required|xss_clean');
        $this->form_validation->set_rules('tanggal', 'Tanggal', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'jurnal_id' => form_error('jurnal_id'),
                'tanggal' => form_error('tanggal'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $id = $this->input->post("jurnal_id");
            $tanggal = $this->input->post("tanggal");
            $tanggal = date('Y-m-d', strtotime($tanggal));
            
            $data = array(
                'tanggal' => $tanggal.' '.date('H:i:s'), 
                'keterangan' => $this->input->post("keterangan"), 
                'modified_by' => $userinput,
                'modified_date' => date('Y-m-d H:i:s'),
            );
            
            $this->mutasi_jurnal_model->editData($data, $id);
            $array = array('status' => 'success', 'error' => '', 'message' => $this->lang->line('update_message'));
        }
        
        echo json_encode($array);
    }
    
    public function do_detail() {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_add') || !$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_edit')){
            access_denied();
        }
        $userinput = $this->customlib->getSessionUsername();
        
        $this->form_validation->set_rules('akun_trx', 'Kode Rekening', 'trim|required|xss_clean');
        $this->form_validation->set_rules('akun', 'Kode Rekening', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('debet', 'Debet', 'trim|required|xss_clean');
        //$this->form_validation->set_rules('kredit', 'Kredit', 'trim|required|xss_clean');
        if ($this->form_validation->run() == FALSE) {

            $msg = array(
                'akun_trx' => form_error('akun_trx'),
                'akun' => form_error('akun'),
                //'debet' => form_error('debet'),
                //'kredit' => form_error('kredit'),
            );

            $array = array('status' => 'fail', 'error' => $msg, 'message' => '');
            
        } else {
            
            $act = $this->input->post("act");
            $detail_idx = '';
            $jurnal_det_id = $this->input->post("jurnal_det_id");
            $jurnal_id = $this->input->post("jurnal_id");
            $cabang = $this->input->post("cabang");
            $tanggal = $this->input->post("tanggal");
            $debet = $this->input->post("debet");
            $kredit = $this->input->post("kredit");
            
            $data = array(
                'cabang' => $cabang, 
                'jurnal_header' => $jurnal_id, 
                'akun' => $this->input->post("akun"), 
                'debet' => $debet ? $debet : 0, 
                'kredit' => $kredit ? $kredit : 0, 
                'keterangan' => $this->input->post("keterangan"), 
            );
            
            if($act == 'add'){
                $data['is_deleted'] = 2;
                $data['created_by'] = $userinput;
                $data['created_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_add')){
                    $detail_idx = $this->mutasi_jurnal_model->addDataDetail($data, $tanggal);
                }
                $msg = 'Tambah data ';
                
            }else{
                
                $data['modified_by'] = $userinput;
                $data['modified_date'] = date('Y-m-d H:i:s');
                
                if($this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_edit')){
                    $detail_idx = $this->mutasi_jurnal_model->editDataDetail($data, $tanggal, $jurnal_det_id);
                }
                $msg = 'Edit data ';
            }
            
            if($detail_idx){

                $array = array('status' => 'success', 'error' => '', 'message' => $msg.' berhasil','detail_idx' => $detail_idx);
            }else{
                $array = array('status' => 'fail', 'error' => '', 'message' => $msg.' gagal','detail_idx' => $detail_idx);
            }
            
        }

        echo json_encode($array);
    }

    function delete($id) {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $dodeleted = $this->mutasi_jurnal_model->deleteData($id);
            
            if($dodeleted == 'success'){
                $array = array('status' => 'success', 'message' => 'Data berhasil dihapus');
            }else{
                $array = array('status' => 'fail', 'message' => $dodeleted);
            }
            
            echo json_encode($array);
        }
        
    }

    function deleteDetail($id, $jurnal_id, $akun, $cabang) {
        if(!$this->rbac->hasPrivilege('trx_mutasi_jurnal', 'can_delete')){
            access_denied();
        }
        if (isset($id)) {
            $this->mutasi_jurnal_model->deleteDataDetail($id, $jurnal_id, $akun, $cabang);
        }
        return 'success';
    }

    
    function generateNoJurnal($cabang, $tgl, $prefix=''){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'JKM';
        $datel = date('Y-m', strtotime($tgl));
        $dates = date('ym', strtotime($tgl));
        $prefix_cabang = $prefix . $kode_cabang . $dates;
        
        $this->db->where('cabang', $cabang);
        $this->db->like('tanggal::text', $datel, 'both', false);
        $this->db->order_by('id', 'desc')->limit (1);
        $q=$this->db->get('jurnal_header');
        $n=$q->num_rows();
        if(!empty($n)){
            $d=$q->row();
            
            $last_nomor = $d->no_jurnal;
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