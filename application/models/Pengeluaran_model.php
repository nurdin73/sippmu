<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Pengeluaran_model extends CI_model {

    function getAll() {
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        $src_status = $this->input->post('src_status') ? $this->input->post('src_status') : '';
        $src_date1 = $this->input->post('src_date1') ? date('Y-m-d', strtotime($this->input->post('src_date1'))).' 00:00:00' : date('Y-m-d');
        $src_date2 = $this->input->post('src_date2') ? date('Y-m-d', strtotime($this->input->post('src_date2'))).' 23:59:59' : date('Y-m-d');
        
        $this->datatables->select("a.id as id, '' as nomor, a.no_transaksi, TO_CHAR(a.tanggal :: DATE, 'dd/mm/yyyy') as tanggal, a.total, a.status, a.keterangan, b.nama as cabang_nama", false);
        $this->datatables->from('pengeluaran a');
        $this->datatables->join('m_cabang b','a.cabang = b.id');
        $this->datatables->where('a.is_deleted', 2);
        
        if (!empty($src_cabangx)) {
            $this->datatables->where('a.cabang', $src_cabangx);
        }else{
            $this->datatables->where('a.cabang', $src_cabang);
        }

        if (!empty($src_status)) {
            $this->datatables->where('a.status', $src_status);
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
        $pengeluaran_id = $this->input->post('pengeluaran_id') ? $this->input->post('pengeluaran_id') : '';
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        
        if(!empty($pengeluaran_id)){
        
            $this->datatables->select("a.id as id, '' as nomor, a.jumlah, a.keterangan, a.akun_kas, b.kode as kode_trx, b.deskripsi as transaksi_nama, c.deskripsi as akun_kas_nama, a2.status,'' as view", false);
            $this->datatables->from('pengeluaran_det a');
            $this->datatables->join('pengeluaran a2','a.pengeluaran = a2.id');
            $this->datatables->join('m_kode_transaksi b','a.kode_transaksi = b.id');
            $this->datatables->join('m_akun_kas c','a.akun_kas = c.id');
            $this->datatables->where('a.is_deleted', 2);
            $this->datatables->where('a.pengeluaran', $pengeluaran_id);
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
            $this->db->select("a.*, to_char( tanggal, 'DD-MM-YYYY') as tanggalx, b.nama as cabang_nama", false);
            $this->db->join('m_cabang b','a.cabang = b.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('pengeluaran a');
            return $query->row_array();
        } else {

            $query = $this->db->get("pengeluaran");
            return $query->result_array();
        }
    }

    function getDataDetail($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, b.kode as kode_trx, b.deskripsi as transaksi_nama, c.deskripsi as akun_kas_nama, e.nama as cabang_nama", false);
            $this->db->join('m_kode_transaksi b','a.kode_transaksi = b.id');
            $this->db->join('m_akun_kas c','a.akun_kas = c.id');
            $this->db->join('m_cabang e','a.cabang = e.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('pengeluaran_det a');
            return $query->row_array();
        } else {

            $query = $this->db->get("pengeluaran_det");
            return $query->result_array();
        }
    }

    public function addData($data) {

        $this->db->insert('pengeluaran', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id, $cabang, $is_edit_rekap) {

        if (isset($id)) {
            
            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            $this->db->where('id', $id);
            $do_update = $this->db->update('pengeluaran', $data);
            
            if($do_update){
                if($is_edit_rekap){
                    //get penerimaan detail
                    $query2 = $this->db->select('id')->where(array('pengeluaran'=>$id, 'is_deleted'=>2))->get("pengeluaran_det");
                    $dt_detail = $query2->result_array();

                    foreach($dt_detail as $row){
                        //update tanggal rekap transaksi
                        $data_rekap = array(
                            'tanggal'  =>  $data['tanggal']
                        );
                        $this->db->where('cabang', $cabang);
                        $this->db->where('pengeluaran_det', $row['id']);
                        $this->db->update('rekap_transaksi', $data_rekap);
                    }
                }
            }
            
            //trans_complete
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return $id;
            }
            
        } 
        return false;
    }

    public function addDataDetail($data) {
        
        //trans_start
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        //===insert ke tabel pengeluaran_det
        $this->db->insert('pengeluaran_det', $data);
        $pengeluaran_det_id = $this->db->insert_id();
        
        if($pengeluaran_det_id){
            
            //get tanggal from header
            $q_header = $this->db->select('tanggal')->where('id', $data['pengeluaran'])->get("pengeluaran");
            $dt_header = $q_header->row_array();
            $tanggalx = $dt_header['tanggal'];

            //===insert ke tabel rekap_transaksi
            $data_rekap = array(
                'cabang'          => $data['cabang'],
                'tipe'            =>  2,
                'pengeluaran_det' =>  $pengeluaran_det_id,
                'akun_transaksi'  =>  $data['akun_kas'],
                'tanggal'         =>  $tanggalx,
                'keluar'          =>  $data['jumlah'],
                'created_by'      =>  $data['created_by'],
                'created_date'    =>  $data['created_date'],
            );
            $this->db->insert('rekap_transaksi', $data_rekap);


            //===update tabel pengeluaran
            $query2 = $this->db->select('sum(jumlah) as total')->where(array('pengeluaran'=>$data['pengeluaran'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("pengeluaran_det");
            $dt_2 = $query2->row_array();

            $data_total = array();
            $data_total['total'] = $dt_2['total'];
            $this->db->where('id', $data['pengeluaran']);
            $this->db->update('pengeluaran', $data_total);


            //===update tabel akun kas
            //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi keluar sudah termasuk transaksi baru
            $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("m_akun_kas");
            $dt_3 = $query3->row_array();

            
            $this->db->where('is_deleted', 2);
            $this->db->where('cabang', $data['cabang']);
            $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("rekap_transaksi");
            $dt_4 = $query4->row_array();

            $data_total = array();
            $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
            $this->db->where('id', $data['akun_kas']);
            $this->db->where('cabang', $data['cabang']);
            $this->db->update('m_akun_kas', $data_total);

        }
        
        //trans_complete
        $this->db->trans_complete();
        if ($this->db->trans_status() === FALSE) {
            $this->db->trans_rollback();
            return false;
        } else {
            $this->db->trans_commit();
            return $pengeluaran_det_id;
        }

    }

    public function editDataDetail($data, $tanggal, $pengeluaran_det_id) {
        
        //get tanggal from header
        $q_header = $this->db->select('tanggal')->where('id', $data['pengeluaran'])->get("pengeluaran");
        $dt_header = $q_header->row_array();
        $tanggalx = $dt_header['tanggal'];

        if (isset($pengeluaran_det_id)) {
            
            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);


            //=== update ke tabel pengeluaran_det
            $this->db->where('id', $pengeluaran_det_id);
            $this->db->update('pengeluaran_det', $data);

            //=== update ke tabel rekap_transaksi
            $data_rekap = array(
                'akun_transaksi'  =>  $data['akun_kas'],
                'tanggal'         =>  $tanggalx,
                'keluar'           =>  $data['jumlah'],
                'modified_by'      =>  $data['modified_by'],
                'modified_date'    =>  $data['modified_date'],
            );
            $this->db->where('tipe', 2);
            $this->db->where('cabang', $data['cabang']);
            $this->db->where('pengeluaran_det', $pengeluaran_det_id);
            $this->db->update('rekap_transaksi', $data_rekap);


            //===update tabel pengeluaran
            $query2 = $this->db->select('sum(jumlah) as total')->where(array('pengeluaran'=>$data['pengeluaran'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("pengeluaran_det");
            $dt_2 = $query2->row_array();

            $data_total = array();
            $data_total['total'] = $dt_2['total'];
            
            $this->db->where('is_deleted', 2);
            $this->db->where('cabang', $data['cabang']);
            $this->db->where('id', $data['pengeluaran']);
            $this->db->update('pengeluaran', $data_total);


            //===update tabel akun kas
            //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi keluar sudah termasuk transaksi baru
            $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("m_akun_kas");
            $dt_3 = $query3->row_array();

            $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("rekap_transaksi");
            $dt_4 = $query4->row_array();

            $data_total = array();
            $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
            $this->db->where('id', $data['akun_kas']);
            $this->db->where('cabang', $data['cabang']);
            $this->db->update('m_akun_kas', $data_total);

            //trans_complete
            $this->db->trans_complete();
            if ($this->db->trans_status() === FALSE) {
                $this->db->trans_rollback();
                return false;
            } else {
                $this->db->trans_commit();
                return $pengeluaran_det_id;
            }

        }
        
        return false;
    }

    function deleteData($id) {
        
        //validasi : data bisa dihapus dengan kondisi status=1 dan di detail transaksi kosong
        
        if (isset($id)) {
            $this->db->where('pengeluaran', $id);
            $this->db->where('is_deleted', 2);
            $query = $this->db->select('*')->get("pengeluaran_det");
            $n = $query->num_rows();
            if($n == 0){

                $userinput = $this->customlib->getSessionUsername();
                $data = array( 
                    'is_deleted' => 1,
                    'deleted_by' => $userinput,
                    'deleted_date' => date('Y-m-d H:i:s')
                );

                $this->db->where('id', $id);
                $this->db->update('pengeluaran', $data);
                
                return 'success';
            }else{
                return 'Data detail transaksi sudah terisi!';
            }
            
        }
        return false;
    }

    function deleteDataDetail($id, $pengeluaran_id, $akun_kas, $cabang) {
        
        //validasi : data bisa dihapus dengan kondisi status=1 
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            
            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            $data = array( 
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $doupdate = $this->db->update('pengeluaran_det', $data);
            
            if($doupdate){
                //=== update ke tabel rekap_transaksi
                $data_rekap = array(
                    'is_deleted' => 1,
                    'deleted_by' => $userinput,
                    'deleted_date' => date('Y-m-d H:i:s')
                );
                $this->db->where('pengeluaran_det', $id);
                $this->db->where('cabang', $cabang);
                $this->db->update('rekap_transaksi', $data_rekap);


                //===update tabel pengeluaran
                $query2 = $this->db->select('sum(jumlah) as total')->where(array('pengeluaran'=>$pengeluaran_id, 'cabang'=>$cabang, 'is_deleted'=>2))->get("pengeluaran_det");
                $dt_2 = $query2->row_array();

                $data_total = array();
                $data_total['total'] = $dt_2['total'];
                $this->db->where('id', $pengeluaran_id);
                $this->db->update('pengeluaran', $data_total);


                //===update tabel akun kas
                //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi keluar sudah termasuk transaksi baru
                $query3 = $this->db->select('saldo_awal')->where(array('id'=>$akun_kas, 'cabang'=>$cabang, 'is_deleted'=>2))->get("m_akun_kas");
                $dt_3 = $query3->row_array();

                $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$akun_kas, 'cabang'=>$cabang, 'is_deleted'=>2))->get("rekap_transaksi");
                $dt_4 = $query4->row_array();

                $data_total = array();
                $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
                $this->db->where('id', $akun_kas);
                $this->db->where('cabang', $cabang);
                $this->db->update('m_akun_kas', $data_total);
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

        return false;

        
    }

    function closingTransaksi($pengeluaran_id=null, $cabang=null) {
        
        if(!empty($pengeluaran_id) && !empty($cabang)){
            
            //cek setup periode laporan
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            $data_setup = $query->row_array();
            $closing_saldo_awal = $data_setup['closing_saldo_awal'];
            
            if(empty($closing_saldo_awal) || $closing_saldo_awal == 'f'){
                $result = array('success' => 'false','message'=>'Closing Transaksi gagal, Saldo Awal belum dilakukan Closing Saldo Awal..!');
            }else{
            
                //cek
                $this->db->select('sum(a.jumlah) as jumlah, b.total, b.tanggal, b.keterangan');
                $this->db->where('a.pengeluaran', $pengeluaran_id);
                $this->db->where('a.cabang', $cabang);
                $this->db->where('a.is_deleted', 2);
                $this->db->where('b.status', 1);
                $this->db->from('pengeluaran_det a');
                $this->db->join('pengeluaran b', 'a.pengeluaran = b.id');
                $this->db->group_by('b.total, b.tanggal, b.keterangan');
                $query = $this->db->get();
                $data = $query->row_array();

                $total = $data['total'];
                $total_det = $data['jumlah'];
                $tanggal = $data['tanggal'];
                $keterangan = $data['keterangan'];

                $tipe_jurnal = $this->getTipeJurnal('JKK');
                $userinput = $this->customlib->getSessionUsername();

                if($total == $total_det){

                    //detail
                    $this->db->select('a.id, a.kode_transaksi, a.jumlah, c.akun as akun_kredit, b.akun as akun_debet, b.deskripsi');
                    $this->db->from('pengeluaran_det a');
                    $this->db->join('m_kode_transaksi b', 'a.kode_transaksi = b.id');
                    $this->db->join('m_akun_kas c', 'a.akun_kas = c.id');
                    $this->db->where('a.pengeluaran', $pengeluaran_id);
                    $this->db->where('a.cabang', $cabang);
                    $this->db->where('a.is_deleted', 2);
                    $query = $this->db->get();
                    $data_detail = $query->result_array();

                    //trans_start
                    $this->db->trans_start();
                    $this->db->trans_strict(FALSE);

                    //update status pengeluaran
                    $data_pengeluaran = array( 
                        'status' => 2
                    );
                    $this->db->where('id', $pengeluaran_id);
                    $doupdate = $this->db->update('pengeluaran', $data_pengeluaran);

                    if($doupdate){
                        //insert tabel jurnal_header
                        $data_jurnal_header = array( 
                            'cabang'        => $cabang,
                            'no_jurnal'     => $this->generateNoJurnal($cabang, $tanggal),
                            'tanggal'       => $tanggal,
                            'tipe_jurnal'   => $tipe_jurnal,
                            'currency'      => 'IDR',
                            'nilai_kurs'    => '1',
                            'status'        => 1,
                            'total'         => $total,
                            'pengeluaran'   => $pengeluaran_id,
                            'keterangan'    => $keterangan,
                            'is_deleted'    => 2,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('jurnal_header', $data_jurnal_header);
                        $jurnal_id = $this->db->insert_id();

                        foreach($data_detail as $det){

                            $data_jurnal_detail_d = array(
                                'jurnal_header' => $jurnal_id,
                                'cabang'        => $cabang,
                                'akun'          => $det['akun_debet'],
                                'pengeluaran_det'   => $det['id'],
                                'debet'         => $det['jumlah'],
                                'kredit'        => 0,
                                'keterangan'    => $det['deskripsi'],
                                'created_by'    => $userinput,
                                'created_date'  => date('Y-m-d H:i:s'),
                            );
                            $this->db->insert('jurnal_detail', $data_jurnal_detail_d);

                            $data_jurnal_detail_k = array(
                                'jurnal_header' => $jurnal_id,
                                'cabang'        => $cabang,
                                'akun'          => $det['akun_kredit'],
                                'pengeluaran_det'   => $det['id'],
                                'debet'         => 0,
                                'kredit'        => $det['jumlah'],
                                'keterangan'    => $det['deskripsi'],
                                'created_by'    => $userinput,
                                'created_date'  => date('Y-m-d H:i:s'),
                            );
                            $this->db->insert('jurnal_detail', $data_jurnal_detail_k);

                        }

                    }

                    $this->db->trans_complete();

                    if ($this->db->trans_status() === FALSE) {

                        $this->db->trans_rollback();
                        $result = array('success' => 'false','message'=>'Closing Transaksi gagal!');
                    } else {

                        $this->db->trans_commit();
                        $result = array('success' => 'true','message'=>'Closing Transaksi berhasil..');
                    }
                }else{
                    $result = array('success' => 'false','message'=>'Jumlah Mutasi tidak balance!');
                }
            }
            
        }else{
            $result = array('success' => 'false','message'=>'Mutasi kas tidak ditemukan!');
        }
        
        return $result;
        die();
    }

    function reopenTransaksi($pengeluaran_id=null, $cabang=null) {
        
        if(!empty($pengeluaran_id) && !empty($cabang)){

            //cek
            $query = $this->db->where("pengeluaran", $pengeluaran_id)->get('jurnal_header');
            $data_jurnal = $query->row_array();
            $jurnal_header_id = $data_jurnal['id'];

            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            //update status pengeluaran jd pengakuan
            $this->db->where('id', $pengeluaran_id);
            $doupdate = $this->db->update('pengeluaran', array('status' => 1));

            if($doupdate){

                $this->db->where('pengeluaran', $pengeluaran_id);
                $dodelete = $this->db->delete('jurnal_header');
                if ($dodelete) {
                    $this->db->where('jurnal_header', $jurnal_header_id);
                    $this->db->delete('jurnal_detail');
                }

            }

            $this->db->trans_complete();

            if ($this->db->trans_status() === FALSE) {

                $this->db->trans_rollback();
                $result = array('success' => 'false','message'=>'Re-Open Transaksi gagal!');
            } else {

                $this->db->trans_commit();
                $result = array('success' => 'true','message'=>'Re-Open Transaksi berhasil..');
            }
            
        }else{
            $result = array('success' => 'false','message'=>'Pengeluaran kas tidak ditemukan!');
        }
        
        return $result;
        die();
    }

    function checkSaldoAkhir($akun_kas){
        
        $query3 = $this->db->select('saldo_akhir')->where(array('id'=>$akun_kas, 'is_deleted'=>2))->get("m_akun_kas");
        $dt_3 = $query3->row_array();

        //$result = array('success' => 'true','saldo_akhir'=>$dt_3['saldo_akhir']);
        return $dt_3['saldo_akhir'];
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

    function getKodeTransaksi($id=null) {

        if (isset($id)) {
            
            $this->db->where("id", $id);
            $query = $this->db->get('m_kode_transaksi');
            return $query->row_array();
            
        } else {
        
            $this->db->order_by('kode', 'ASC');
            $this->db->where('is_deleted', 2);
            $this->db->where('tipe', '2');
            $this->db->where('level', '3');
            $query = $this->db->select('*')->get('m_kode_transaksi');
            return $query->result_array();
        }
    }

    function getAkunKas($cabang='') {

        $this->db->order_by('id', 'ASC');
        $this->db->where('cabang', $cabang);
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_akun_kas');
        return $query->result_array();
    }

    function getTotalPengeluaran($id='') {

        $this->db->where('pengeluaran', $id);
        $this->db->where('is_deleted', 2);
        $query = $this->db->select('sum(jumlah) as total')->get("pengeluaran_det");
        $dt = $query->row();

        return $dt->total;
    }

}

?>