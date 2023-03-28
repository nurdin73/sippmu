<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Mutasi_jurnal_model extends CI_model {

    function getAll() {
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        $src_status = $this->input->post('src_status') ? $this->input->post('src_status') : '';
        $src_tipe_jurnal = $this->input->post('src_tipe_jurnal') ? $this->input->post('src_tipe_jurnal') : '';
        $src_date1 = $this->input->post('src_date1') ? date('Y-m-d', strtotime($this->input->post('src_date1'))).' 00:00:00' : date('Y-m-0');
        $src_date2 = $this->input->post('src_date2') ? date('Y-m-d', strtotime($this->input->post('src_date2'))).' 23:59:59' : date('Y-m-t');
        
        $this->datatables->select("a.id as id, '' as nomor, a.no_jurnal, TO_CHAR(a.tanggal :: DATE, 'dd/mm/yyyy') as tanggal, TO_CHAR(a.tanggal_posting :: DATE, 'dd/mm/yyyy') as tanggal_posting, c.nama as tipe_jurnal, a.status, a.total, a.keterangan, a.penerimaan, a.pengeluaran, a.mutasi_kas_bank, b.nama as cabang_nama", false);
        $this->datatables->from('jurnal_header a');
        $this->datatables->join('m_cabang b','a.cabang = b.id');
        $this->datatables->join('m_tipe_jurnal c','a.tipe_jurnal = c.kode');
        
        if($src_status == 3){ ///if batal
            $this->datatables->where('a.is_deleted', 1);
        }else{
            $this->datatables->where('a.is_deleted', 2);
        }
        
        if (!empty($src_cabangx)) {
            $this->datatables->where('a.cabang', $src_cabangx);
        }else{
            $this->datatables->where('a.cabang', $src_cabang);
        }

        if (!empty($src_status)) {
            $this->datatables->where('a.status', $src_status);
        }
        if (!empty($src_tipe_jurnal)) {
            $this->datatables->where('a.tipe_jurnal', $src_tipe_jurnal);
        }
        if (!empty($src_date1)) {
            $this->datatables->where("(a.tanggal >= '". $src_date1 . "' AND a.tanggal <= '". $src_date2 ."')");
        }
        /*
        $this->datatables->add_column('view', '<a onclick="getEdit(this)" data-id="$1" data-status="$2" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleteData(this)" data-id="$1" data-status="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id, status');
        */
        return $this->datatables->generate();
    }

    function getAllDetail() {
        $jurnal_id = $this->input->post('jurnal_id') ? $this->input->post('jurnal_id') : '';
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        
        if(!empty($jurnal_id)){
        
            $this->datatables->select("a.id as id, '' as nomor, a.debet, a.kredit, a.keterangan, c.kode as kode_rek, c.nama as nama_rek, b.status, b.penerimaan, b.pengeluaran, b.mutasi_kas_bank, '' as view", false);
            $this->datatables->from('jurnal_detail a');
            $this->datatables->join('jurnal_header b','a.jurnal_header = b.id');
            $this->datatables->join('m_akun c','a.akun = c.id');
            $this->datatables->where('a.is_deleted', 2);
            $this->datatables->where('a.jurnal_header', $jurnal_id);
            if (!empty($src_cabangx)) {
                $this->datatables->where('a.cabang', $src_cabangx);
            }else{
                $this->datatables->where('a.cabang', $src_cabang);
            }
            
            /*
            $this->datatables->add_column('view', '<a onclick="getEditDetail(this)" data-id="$1" data-status="$2" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                        <i class="fa fa-pencil"></i></a>
                                                    <a  class="btn btn-default btn-xs" onclick="deleteDataDetail(this)" data-id="$1" data-status="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                            <i class="fa fa-trash"></i></a>', 'id, status');
             */
            return $this->datatables->generate();
            
        }else{
            
            return '{"draw":1,"recordsTotal":0,"recordsFiltered":0,"data":[]}';
        }
    }

    function getData($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, to_char( a.tanggal, 'DD-MM-YYYY') as tanggalx, c.nama as tipe_jurnal, b.nama as cabang_nama", false);
            $this->db->from('jurnal_header a');
            $this->db->join('m_cabang b','a.cabang = b.id');
            $this->db->join('m_tipe_jurnal c','a.tipe_jurnal = c.kode');
            $this->db->where('a.is_deleted', 2);
            $this->db->where("a.id", $id);
            $query = $this->db->get();
            return $query->row_array();
        } else {

            $query = $this->db->get("jurnal_header");
            return $query->result_array();
        }
    }

    function getDataDetail($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, b.kode as kode_trx, b.nama as transaksi_nama, e.nama as cabang_nama, b.posisi_akun", false);
            $this->db->join('m_akun b','a.akun = b.id');
            $this->db->join('m_cabang e','a.cabang = e.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('jurnal_detail a');
            return $query->row_array();
        } else {

            $query = $this->db->get("jurnal_detail");
            return $query->result_array();
        }
    }

    public function addData($data) {

        $this->db->insert('jurnal_header', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $doupdate = $this->db->update('jurnal_header', $data);
            /*
            if($doupdate){
                //cek data jika
                $this->db->where('id', $id);
                $this->db->where('status', 1);
                $query2 = $this->db->select('*')->get("jurnal_header");
                $dt_header = $query2->row_array();
                if(!empty($dt_header['penerimaan'])){
                    $data2 = array();
                    $data2['tanggal'] = $dt_header['tanggal']; 
                    $data2['keterangan'] = $dt_header['keterangan']; 
                    $this->db->where('id', $dt_header['penerimaan']);
                    $this->db->update('penerimaan', $data2);
                }
                if(!empty($dt_header['pengeluaran'])){
                    $data2 = array();
                    $data2['tanggal'] = $dt_header['tanggal']; 
                    $data2['keterangan'] = $dt_header['keterangan']; 
                    $this->db->where('id', $dt_header['pengeluaran']);
                    $this->db->update('pengeluaran', $data2);
                }
                if(!empty($dt_header['mutasi_kas_bank'])){
                    $data2 = array();
                    $data2['tanggal'] = $dt_header['tanggal']; 
                    $data2['keterangan'] = $dt_header['keterangan']; 
                    $this->db->where('id', $dt_header['mutasi_kas_bank']);
                    $this->db->update('mutasi_kas_bank', $data2);
                }
            }
            */
            
        } 
        return $id;
    }

    public function addDataDetail($data, $tanggal) {
        
        //===insert ke tabel jurnal_detail
        $this->db->insert('jurnal_detail', $data);
        $jurnal_detail_id = $this->db->insert_id();
        
        if($jurnal_detail_id){
            

            //===update tabel jurnal header
            $query2 = $this->db->select('sum(debet) as total')->where(array('jurnal_header'=>$data['jurnal_header'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("jurnal_detail");
            $dt_2 = $query2->row_array();

            $data_total = array();
            $data_total['total'] = $dt_2['total'];
            $this->db->where('id', $data['jurnal_header']);
            $this->db->update('jurnal_header', $data_total);


        }
        
        return $jurnal_detail_id;
    }

    public function editDataDetail($data, $tanggal, $jurnal_detail_id) {
        
        
        if (isset($jurnal_detail_id)) {
            //=== update ke tabel jurnal_detail
            $this->db->where('id', $jurnal_detail_id);
            $this->db->update('jurnal_detail', $data);

            //===update tabel jurnal_header
            $query2 = $this->db->select('sum(debet) as total')->where(array('jurnal_header'=>$data['jurnal_header'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("jurnal_detail");
            $dt_2 = $query2->row_array();

            $data_total = array();
            $data_total['total'] = $dt_2['total'];
            
            $this->db->where('is_deleted', 2);
            $this->db->where('cabang', $data['cabang']);
            $this->db->where('id', $data['jurnal_header']);
            $this->db->update('jurnal_header', $data_total);

        }
        
        return $jurnal_detail_id;
    }

    function deleteData($id) {
        //validasi : data bisa dihapus dengan kondisi status=1 dan di detail transaksi kosong
        if (isset($id)) {
            $this->db->where('jurnal_header', $id);
            $this->db->where('is_deleted', 2);
            $query = $this->db->select('*')->get("jurnal_detail");
            $n = $query->num_rows();
            if($n == 0){
                
                //trans_start
                $this->db->trans_start();
                $this->db->trans_strict(FALSE);

                $userinput = $this->customlib->getSessionUsername();
                $data = array( 
                    'status' => 3,
                    'is_deleted' => 1,
                    'deleted_by' => $userinput,
                    'deleted_date' => date('Y-m-d H:i:s')
                );

                $this->db->where('id', $id);
                $this->db->update('jurnal_header', $data);

                //cek data 
                $this->db->where('id', $id);
                $this->db->where('status', 3);
                $query2 = $this->db->select('*')->get("jurnal_header");
                $dt_header = $query2->row_array();
                if(!empty($dt_header['penerimaan'])){
                    $data2 = array();
                    $data2['status'] = 1; //ubah status jd pengakuan jika dihapus
                    $this->db->where('id', $dt_header['penerimaan']);
                    $this->db->update('penerimaan', $data2);
                }
                if(!empty($dt_header['pengeluaran'])){
                    $data2 = array();
                    $data2['status'] = 1; //ubah status jd pengakuan jika dihapus
                    $this->db->where('id', $dt_header['pengeluaran']);
                    $this->db->update('pengeluaran', $data2);
                }
                if(!empty($dt_header['mutasi_kas_bank'])){
                    $data2 = array();
                    $data2['status'] = 1; //ubah status jd pengakuan jika dihapus
                    $this->db->where('id', $dt_header['mutasi_kas_bank']);
                    $this->db->update('mutasi_kas_bank', $data2);
                }
                
                
                //trans_complete
                $this->db->trans_complete();
                if ($this->db->trans_status() === FALSE) {
                    $this->db->trans_rollback();
                    return false;
                } else {
                    $this->db->trans_commit();
                    return 'success';
                }
                
            }else{
                return 'Data jurnal detail transaksi sudah terisi!';
            }
        }
        
        return false;
        
    }

    function deleteDataDetail($id, $jurnal_id, $akun, $cabang) {
        //validasi : data bisa dihapus dengan kondisi status=1 
        if (isset($id)) {

            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            $userinput = $this->customlib->getSessionUsername();
            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $doupdate = $this->db->update('jurnal_detail', $data);
            
            if($doupdate){
                

                //===update tabel jurnal_header
                $query2 = $this->db->select('sum(debet) as total')->where(array('jurnal_header'=>$jurnal_id, 'cabang'=>$cabang, 'is_deleted'=>2))->get("jurnal_detail");
                $dt_2 = $query2->row_array();

                $data_total = array();
                $data_total['total'] = $dt_2['total'];
                $this->db->where('id', $jurnal_id);
                $this->db->update('jurnal_header', $data_total);


            }
            
            //trans_complete
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return 'success';
            }

        } 

        return 'error';

        
    }

    function postingJurnal($jurnal_id=null, $cabang=null, $tanggal_jurnal=null) {
        
        if (!empty($cabang)) {
            $userinput = $this->customlib->getSessionUsername();
            
            //cek setup periode laporan
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            $data_setup = $query->row_array();
            
            $closing_saldo_awal = $data_setup['closing_saldo_awal'];
            $bulan_berjalan = $data_setup['bulan_berjalan'];
            $bulan_jurnal = date('m/Y', strtotime($tanggal_jurnal));
            
            if(empty($closing_saldo_awal) || $closing_saldo_awal == 'f'){
                $result = array('success' => 'false','message'=>'Posting Jurnal gagal, Saldo Awal belum dilakukan Closing Saldo Awal..!');
            }else if($bulan_berjalan != $bulan_jurnal){
                $result = array('success' => 'false','message'=>'Posting Jurnal gagal, Periode Jurnal tidak sesuai dengan Periode Bulan Berjalan (Setup Periode laporan)..!');
            }else{
                
                $periode_exp = explode('/',$bulan_berjalan);
                $periode_bln = trim($periode_exp[0]);
                $periode_thn = trim($periode_exp[1]);

                $this->db->select("a.*, b.currency, b.nilai_kurs, b.penerimaan, b.pengeluaran, b.mutasi_kas_bank", false);
                $this->db->join('jurnal_header b','a.jurnal_header = b.id');
                $this->db->where('a.is_deleted', 2);
                $this->db->where('a.jurnal_header', $jurnal_id);
                $this->db->where('a.cabang', $cabang);
                $query = $this->db->get('jurnal_detail a');
                $data_detail = $query->result_array();
                
                
                //trans_start
                $this->db->trans_start();
                $this->db->trans_strict(FALSE);

                foreach($data_detail as $dt_detail){
                    
                    //cek akun di neraca saldo
                    $query3 = $this->db->where(array('akun'=>$dt_detail['akun'], 'cabang'=>$cabang, 'tahun'=>$periode_thn, 'periode'=>$periode_bln))->get("neraca_saldo");
                    $dt_saldo = $query3->row_array();
                    
                    
                    if($dt_saldo['id']){
                        
                        $mutasi_saldo = $dt_detail['debet'] - $dt_detail['kredit'];
                        $saldo = $dt_saldo['saldo'] + $mutasi_saldo;
                        
                        $data_neraca_saldo = array( 
                            'mutasi_debet'  => $dt_saldo['mutasi_debet'] + $dt_detail['debet'],
                            'mutasi_kredit' => $dt_saldo['mutasi_kredit'] + $dt_detail['kredit'],
                            'saldo'         => $saldo,
                            'posisi'        => ($saldo > 0) ? 'D' : 'K',
                            'modified_by'   => $userinput,
                            'modified_date' => date('Y-m-d H:i:s'),
                        );
                        
                        $this->db->where('id', $dt_saldo['id']);
                        $this->db->update('neraca_saldo', $data_neraca_saldo);
                        
                    }else{
                        
                        $saldo = $dt_detail['debet'] - $dt_detail['kredit'];
                        
                        $data_neraca_saldo = array( 
                            'cabang'        => $cabang,
                            'akun'          => $dt_detail['akun'],
                            'currency'      => $dt_detail['currency'],
                            'nilai_kurs'    => $dt_detail['nilai_kurs'],
                            'saldo_bulan_lalu'   => 0,
                            'mutasi_debet'  => $dt_detail['debet'],
                            'mutasi_kredit' => $dt_detail['kredit'],
                            'saldo'         => $saldo,
                            'posisi'        => ($saldo > 0) ? 'D' : 'K',
                            'periode'       => $periode_bln,
                            'tahun'         => $periode_thn,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('neraca_saldo', $data_neraca_saldo);
                    }
                    
                    
                    
                }
                
                
                //update status mutasi jurnal
                $data_jurnal = array( 
                    'status' => 2,
                    'is_posting' => 't',
                    'tanggal_posting' => date('Y-m-d H:i:s'),
                    'modified_by'   => $userinput,
                    'modified_date' => date('Y-m-d H:i:s'),
                );
                $this->db->where('id', $jurnal_id);
                $do_posting = $this->db->update('jurnal_header', $data_jurnal);
                
                if($do_posting){
                    //update status posting transaksi
                    $this->db->select("id, penerimaan, pengeluaran, mutasi_kas_bank", false);
                    $this->db->where('is_posting', 't');
                    $this->db->where('status', 2);
                    $this->db->where('id', $jurnal_id);
                    $this->db->where('cabang', $cabang);
                    $query = $this->db->get('jurnal_header');
                    $data_jheader = $query->result_array();
                    foreach($data_jheader as $row){

                        //update status posting transaksi kas penerimaan
                        if(!empty($row['penerimaan'])){

                            $this->db->where('id', $row['penerimaan']);
                            $this->db->update('penerimaan', array('status' => 3));

                        }
                        //update status posting transaksi kas pengeluaran
                        if(!empty($row['pengeluaran'])){

                            $this->db->where('id', $row['pengeluaran']);
                            $this->db->update('pengeluaran', array('status' => 3));

                        }
                        //update status posting transaksi kas mutasi_kas_bank
                        if(!empty($row['mutasi_kas_bank'])){

                            $this->db->where('id', $row['mutasi_kas_bank']);
                            $this->db->update('mutasi_kas_bank', array('status' => 3));

                        }

                    }
                }
                
                
                
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    $result = array('success' => 'false','message'=>'Posting Jurnal gagal!');
                } else {

                    $this->db->trans_commit();
                    $result = array('success' => 'true','message'=>'Posting Jurnal berhasil..');
                }

                
            }
            
        }else{
            $result = array('success' => 'false','message'=>'Unit Kerja tidak ditemukan!');
        }
        
        return $result;
        die();
    }

    function generateNoJurnal($cabang, $tgl){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'JKK';
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
    
    function getTipeJurnal($kode=null) {
        $this->db->where('singkatan', $kode);
        $query = $this->db->select('*')->get('m_tipe_jurnal');
        $data = $query->row();
        return $data->kode;
    }
    
    function getKodeRekening($id=null) {

        if (isset($id)) {
            
            $this->db->where("id", $id);
            $query = $this->db->get('m_akun');
            return $query->row_array();
            
        } else {
        
            $this->db->order_by('kode', 'ASC');
            $this->db->where('is_deleted', 2);
            $this->db->where('level', '5');
            $query = $this->db->select('*')->get('m_akun');
            return $query->result_array();
        }
    }

    
    function getAkunParent() {

        $query = $this->db->select('*')
            ->where("is_deleted", 2)
            //->where("parent", 0)
            ->get('m_akun');
        return $query->result_array();
    }

    function getKelAkun() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_kel_akun');
        return $query->result_array();
    }

    function getProgram() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_program');
        return $query->result_array();
    }

    function getTipeJurnalAll() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_tipe_jurnal');
        return $query->result_array();
    }

    function getAkunKas($cabang='') {

        $this->db->order_by('id', 'ASC');
        $this->db->where('cabang', $cabang);
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_akun_kas');
        return $query->result_array();
    }

    function getTotalJurnalDetail($id='') {

        $this->db->where('jurnal_header', $id);
        $this->db->where('is_deleted', 2);
        $query = $this->db->select('sum(debet) as total_debet, sum(kredit) as total_kredit')->get("jurnal_detail");
        return $query->row_array();
        
    }

}

?>