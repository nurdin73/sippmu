<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Mutasi_kas_model extends CI_model {

    function getAll() {
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        $src_status = $this->input->post('src_status') ? $this->input->post('src_status') : '';
        $src_date1 = $this->input->post('src_date1') ? date('Y-m-d', strtotime($this->input->post('src_date1'))).' 00:00:00' : date('Y-m-d');
        $src_date2 = $this->input->post('src_date2') ? date('Y-m-d', strtotime($this->input->post('src_date2'))).' 23:59:59' : date('Y-m-d');
        
        $this->datatables->select("a.id as id, '' as nomor, a.no_transaksi, TO_CHAR(a.tanggal :: DATE, 'dd/mm/yyyy') as tanggal, a.jumlah, a.status, a.keterangan, 
            b.nama as cabang_nama, e.deskripsi as akun_mutasi_masuk, c.deskripsi as desc_mutasi_masuk, f.deskripsi as akun_mutasi_keluar, d.deskripsi as desc_mutasi_keluar", false);
        $this->datatables->from('mutasi_kas_bank a');
        $this->datatables->join('m_cabang b','a.cabang = b.id');
        $this->datatables->join('m_kode_transaksi c','a.kode_transaksi_masuk = c.id','left');
        $this->datatables->join('m_kode_transaksi d','a.kode_transaksi_keluar = d.id','left');
        $this->datatables->join('m_akun_kas e','a.akun_kas_masuk = e.id','left');
        $this->datatables->join('m_akun_kas f','a.akun_kas_keluar = f.id','left');
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

    function getData($id = null) {

        if (isset($id)) {
            $this->db->select("a.*, to_char( tanggal, 'DD-MM-YYYY') as tanggalx, b.nama as cabang_nama", false);
            $this->db->join('m_cabang b','a.cabang = b.id');
            $this->db->where("a.id", $id);
            $query = $this->db->get('mutasi_kas_bank a');
            return $query->row_array();
        } else {

            $query = $this->db->get("mutasi_kas_bank");
            return $query->result_array();
        }
    }

    public function addData($data) {

        //trans_start
        $this->db->trans_start();
        $this->db->trans_strict(FALSE);

        $this->db->insert('mutasi_kas_bank', $data);
        $mutasi_id = $this->db->insert_id();
        
        if($mutasi_id){
            //==IN==
            //insert ke tabel penerimaan_det
            $data_penerimaan_det = array(
                'cabang'          => $data['cabang'],
                //'tanggal'         =>  $data['tanggal'],
                'mutasi_kas_bank' =>  $mutasi_id,
                'kode_transaksi'  =>  $data['kode_transaksi_masuk'],
                'akun_kas'        =>  $data['akun_kas_masuk'],
                'jumlah'          =>  $data['jumlah'],
                'keterangan'      =>  $data['keterangan'],
                'created_by'      =>  $data['created_by'],
                'created_date'    =>  $data['created_date'],
            );
            $this->db->insert('penerimaan_det', $data_penerimaan_det);
            $penerimaan_det_id = $this->db->insert_id();
                
            //===insert ke tabel rekap_transaksi (masuk)
            $data_rekap = array(
                'cabang'          => $data['cabang'],
                'tipe'            =>  3,
                'penerimaan_det'  =>  $penerimaan_det_id,
                'akun_transaksi'  =>  $data['akun_kas_masuk'],
                'tanggal'         =>  $data['tanggal'],
                'masuk'           =>  $data['jumlah'],
                'created_by'      =>  $data['created_by'],
                'created_date'    =>  $data['created_date'],
            );
            $this->db->insert('rekap_transaksi', $data_rekap);

            //===update tabel akun kas
            //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi masuk sudah termasuk transaksi baru
            $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_masuk'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("m_akun_kas");
            $dt_3 = $query3->row_array();

            $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_masuk'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("rekap_transaksi");
            $dt_4 = $query4->row_array();

            $data_total = array();
            $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
            $this->db->where('id', $data['akun_kas_masuk']);
            $this->db->where('cabang', $data['cabang']);
            $this->db->update('m_akun_kas', $data_total);

            //==OUT==
            //insert ke tabel pengeluaran_det
            $data_pengeluaran_det = array(
                'cabang'          => $data['cabang'],
                //'tanggal'         =>  $data['tanggal'],
                'mutasi_kas_bank' =>  $mutasi_id,
                'kode_transaksi'  =>  $data['kode_transaksi_keluar'],
                'akun_kas'        =>  $data['akun_kas_keluar'],
                'jumlah'          =>  $data['jumlah'],
                'keterangan'      =>  $data['keterangan'],
                'created_by'      =>  $data['created_by'],
                'created_date'    =>  $data['created_date'],
            );
            $this->db->insert('pengeluaran_det', $data_pengeluaran_det);
            $pengeluaran_det_id = $this->db->insert_id();
                
            //===insert ke tabel rekap_transaksi (masuk)
            $data_rekap = array(
                'cabang'          => $data['cabang'],
                'tipe'            =>  4,
                'pengeluaran_det'  =>  $pengeluaran_det_id,
                'akun_transaksi'  =>  $data['akun_kas_keluar'],
                'tanggal'         =>  $data['tanggal'],
                'keluar'           =>  $data['jumlah'],
                'created_by'      =>  $data['created_by'],
                'created_date'    =>  $data['created_date'],
            );
            $this->db->insert('rekap_transaksi', $data_rekap);

            //===update tabel akun kas
            //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi keluar sudah termasuk transaksi baru
            $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_keluar'], 'cabang'=>$data['cabang'], 'is_deleted'=>2))->get("m_akun_kas");
            $dt_3 = $query3->row_array();

            $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_keluar'], 'is_deleted'=>2))->get("rekap_transaksi");
            $dt_4 = $query4->row_array();

            $data_total = array();
            $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
            $this->db->where('id', $data['akun_kas_keluar']);
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
            return $mutasi_id;
        }
        
    }

    public function editData($data, $mutasi_id, $cabang) {
        //print_r($data);
        
        if (isset($mutasi_id)) {
            
            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            $this->db->where('id', $mutasi_id);
            $this->db->update('mutasi_kas_bank', $data);
            
            //==IN==
            $query = $this->db->select('id')->where(array('mutasi_kas_bank'=>$mutasi_id, 'cabang'=>$cabang, 'is_deleted'=>2))->get("penerimaan_det");
            $dt_det = $query->row();
            $penerimaan_det_id = $dt_det->id;
                
            if($penerimaan_det_id){
                //update ke tabel penerimaan_det
                $data_penerimaan_det = array(
                    //'cabang'          => $cabang,
                    //'tanggal'         =>  $data['tanggal'],
                    //'mutasi_kas_bank' =>  $mutasi_id,
                    'kode_transaksi'  =>  $data['kode_transaksi_masuk'],
                    'akun_kas'        =>  $data['akun_kas_masuk'],
                    'jumlah'          =>  $data['jumlah'],
                    'keterangan'      =>  $data['keterangan'],
                    'modified_by'      =>  $data['modified_by'],
                    'modified_date'    =>  $data['modified_date'],
                );
                $this->db->where('id', $penerimaan_det_id);
                $this->db->update('penerimaan_det', $data_penerimaan_det);

                //===update ke tabel rekap_transaksi (masuk)
                $data_rekap = array(
                    'akun_transaksi'  =>  $data['akun_kas_masuk'],
                    'tanggal'         =>  $data['tanggal'],
                    'masuk'           =>  $data['jumlah'],
                    'modified_by'      =>  $data['modified_by'],
                    'modified_date'    =>  $data['modified_date'],
                );
                $this->db->where('tipe', 3);
                $this->db->where('cabang', $cabang);
                $this->db->where('penerimaan_det', $penerimaan_det_id);
                $this->db->update('rekap_transaksi', $data_rekap);

                //===update tabel akun kas
                //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi masuk sudah termasuk transaksi baru
                $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_masuk'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("m_akun_kas");
                $dt_3 = $query3->row_array();

                $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_masuk'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("rekap_transaksi");
                $dt_4 = $query4->row_array();

                $data_total = array();
                $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
                $this->db->where('id', $data['akun_kas_masuk']);
                $this->db->where('cabang', $cabang);
                $this->db->update('m_akun_kas', $data_total);
            }
            
            //==OUT==
            $query2 = $this->db->select('id')->where(array('mutasi_kas_bank'=>$mutasi_id, 'cabang'=>$cabang, 'is_deleted'=>2))->get("pengeluaran_det");
            $dt_det2 = $query2->row();
            $pengeluaran_det_id = $dt_det2->id;
                
            if($pengeluaran_det_id){
                //update ke tabel pengeluaran_det
                $data_pengeluaran_det = array(
                    //'cabang'          => $cabang,
                    //'tanggal'         =>  $data['tanggal'],
                    //'mutasi_kas_bank' =>  $mutasi_id,
                    'kode_transaksi'  =>  $data['kode_transaksi_keluar'],
                    'akun_kas'        =>  $data['akun_kas_keluar'],
                    'jumlah'          =>  $data['jumlah'],
                    'keterangan'      =>  $data['keterangan'],
                    'modified_by'      =>  $data['modified_by'],
                    'modified_date'    =>  $data['modified_date'],
                );
                $this->db->where('id', $pengeluaran_det_id);
                $this->db->update('pengeluaran_det', $data_pengeluaran_det);

                //===update ke tabel rekap_transaksi (masuk)
                $data_rekap = array(
                    'akun_transaksi'  =>  $data['akun_kas_keluar'],
                    'tanggal'         =>  $data['tanggal'],
                    'keluar'           =>  $data['jumlah'],
                    'modified_by'      =>  $data['modified_by'],
                    'modified_date'    =>  $data['modified_date'],
                );

                $this->db->where('tipe', 4);
                $this->db->where('cabang', $cabang);
                $this->db->where('pengeluaran_det', $pengeluaran_det_id);
                $this->db->update('rekap_transaksi', $data_rekap);


                //===update tabel akun kas
                //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi keluar sudah termasuk transaksi baru
                $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_keluar'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("m_akun_kas");
                $dt_3 = $query3->row_array();

                $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_keluar'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("rekap_transaksi");
                $dt_4 = $query4->row_array();

                $data_total = array();
                $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
                $this->db->where('id', $data['akun_kas_keluar']);
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
                return $mutasi_id;
            }
        } 
        
        return false;
    }

    function deleteData($mutasi_id, $cabang) {
        
        //validasi : data bisa dihapus dengan kondisi status=1 dan di detail transaksi kosong
        
        if (isset($mutasi_id)) {
            $this->db->select('a.*, b.id as penerimaan_det_id, c.id as pengeluaran_det_id');
            $this->db->where('a.id', $mutasi_id);
            $this->db->join('penerimaan_det b', 'a.id = b.mutasi_kas_bank AND b.is_deleted=2 AND b.cabang = '.$cabang);
            $this->db->join('pengeluaran_det c', 'a.id = c.mutasi_kas_bank AND c.is_deleted=2 AND c.cabang = '.$cabang);
            $query = $this->db->get("mutasi_kas_bank a");

            $n = $query->num_rows();
            if($n > 0){
        
                $userinput = $this->customlib->getSessionUsername();
                
                $data = $query->row_array();
                $penerimaan_det_id = $data['penerimaan_det_id'];
                $pengeluaran_det_id = $data['pengeluaran_det_id'];
                    
                //trans_start
                $this->db->trans_start();
                $this->db->trans_strict(FALSE);

                $data_mutasi = array( 
                    'is_deleted' => 1,
                    'deleted_by' => $userinput,
                    'deleted_date' => date('Y-m-d H:i:s')
                );

                $this->db->where('id', $mutasi_id);
                $dodelete = $this->db->update('mutasi_kas_bank', $data_mutasi);
                
                if($dodelete){
                    
                    if($penerimaan_det_id){
                        //delete ke tabel penerimaan_det
                        $data_penerimaan_det = array(
                            'is_deleted' => 1,
                            'deleted_by' => $userinput,
                            'deleted_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('id', $penerimaan_det_id);
                        $this->db->update('penerimaan_det', $data_penerimaan_det);

                        //=== delete ke tabel rekap_transaksi
                        $data_rekap = array(
                            'is_deleted' => 1,
                            'deleted_by' => $userinput,
                            'deleted_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('penerimaan_det', $penerimaan_det_id);
                        $this->db->where('cabang', $cabang);
                        $this->db->update('rekap_transaksi', $data_rekap);


                        //===update tabel akun kas
                        //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi masuk sudah termasuk transaksi baru
                        $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_masuk'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("m_akun_kas");
                        $dt_3 = $query3->row_array();

                        $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_masuk'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("rekap_transaksi");
                        $dt_4 = $query4->row_array();

                        $data_total = array();
                        $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
                        $this->db->where('id', $data['akun_kas_masuk']);
                        $this->db->where('cabang', $cabang);
                        $this->db->update('m_akun_kas', $data_total);
                    }
                    
                    //------------------
                    
                    if($pengeluaran_det_id){
                        //delete ke tabel pengeluaran_det
                        $data_pengeluaran_det = array(
                            'is_deleted' => 1,
                            'deleted_by' => $userinput,
                            'deleted_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('id', $pengeluaran_det_id);
                        $this->db->update('pengeluaran_det', $data_pengeluaran_det);

                        //=== delete ke tabel rekap_transaksi
                        $data_rekap = array(
                            'is_deleted' => 1,
                            'deleted_by' => $userinput,
                            'deleted_date' => date('Y-m-d H:i:s')
                        );
                        $this->db->where('pengeluaran_det', $pengeluaran_det_id);
                        $this->db->where('cabang', $cabang);
                        $this->db->update('rekap_transaksi', $data_rekap);


                        //===update tabel akun kas
                        //saldo_akhir = saldo_awal + sum(transaksi_masuk) - sum(transaksi_keluar) -> transaksi masuk sudah termasuk transaksi baru
                        $query3 = $this->db->select('saldo_awal')->where(array('id'=>$data['akun_kas_keluar'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("m_akun_kas");
                        $dt_3 = $query3->row_array();

                        $query4 = $this->db->select('sum(masuk) as masuk, sum(keluar) as keluar')->where(array('akun_transaksi'=>$data['akun_kas_keluar'], 'cabang'=>$cabang, 'is_deleted'=>2))->get("rekap_transaksi");
                        $dt_4 = $query4->row_array();

                        $data_total = array();
                        $data_total['saldo_akhir'] = $dt_3['saldo_awal'] + $dt_4['masuk'] - $dt_4['keluar'];
                        $this->db->where('id', $data['akun_kas_keluar']);
                        $this->db->where('cabang', $cabang);
                        $this->db->update('m_akun_kas', $data_total);
                    }
                
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
                return 'Data detail tidak ditemukan!';
            }
            
        }
        
        return false;
        
    }
    
    function checkSaldoAkhir($akun_kas){
        
        $query3 = $this->db->select('saldo_akhir')->where(array('id'=>$akun_kas, 'is_deleted'=>2))->get("m_akun_kas");
        $dt_3 = $query3->row_array();

        //$result = array('success' => 'true','saldo_akhir'=>$dt_3['saldo_akhir']);
        return $dt_3['saldo_akhir'];
    } 
    
    function closingTransaksi($mutasi_id=null, $cabang=null) {
        
        if(!empty($mutasi_id) && !empty($cabang)){
            
            $userinput = $this->customlib->getSessionUsername();
            
            //cek setup periode laporan
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            $data_setup = $query->row_array();
            $closing_saldo_awal = $data_setup['closing_saldo_awal'];
            
            if(empty($closing_saldo_awal) || $closing_saldo_awal == 'f'){
                $result = array('success' => 'false','message'=>'Closing Transaksi gagal, Saldo Awal belum dilakukan Closing Saldo Awal..!');
            }else{
                
                //cek
                $this->db->select('a.*, b.id as penerimaan_det_id, c.id as pengeluaran_det_id, b.jumlah as jumlah_d, c.jumlah as jumlah_k, 
                    d.akun as akun_kas_masukx, e.akun as akun_kas_keluarx, d.deskripsi as desc_akun_kas_masuk, e.deskripsi as desc_akun_kas_keluar, 
                    f.deskripsi as desc_mutasi_masuk, g.deskripsi as desc_mutasi_keluar');
                $this->db->where('a.id', $mutasi_id);
                $this->db->where('a.cabang', $cabang);
                $this->db->where('a.status', 1);
                $this->db->join('penerimaan_det b', 'a.id = b.mutasi_kas_bank AND b.is_deleted=2 AND b.cabang = '.$cabang);
                $this->db->join('pengeluaran_det c', 'a.id = c.mutasi_kas_bank AND c.is_deleted=2 AND c.cabang = '.$cabang);
                $this->db->join('m_akun_kas d', 'a.akun_kas_masuk = d.id');
                $this->db->join('m_akun_kas e', 'a.akun_kas_keluar = e.id');
                $this->db->join('m_kode_transaksi f','a.kode_transaksi_masuk = f.id','left');
                $this->db->join('m_kode_transaksi g','a.kode_transaksi_keluar = g.id','left');
                $query = $this->db->get("mutasi_kas_bank a");
                $data = $query->row_array();

                $penerimaan_det_id = $data['penerimaan_det_id'];
                $pengeluaran_det_id = $data['pengeluaran_det_id'];
                $jumlah_mutasi = $data['jumlah'];
                $jumlah_mutasi_d = $data['jumlah_d'];
                $jumlah_mutasi_k = $data['jumlah_k'];
                $tanggal = $data['tanggal'];
                $akun_kas_masuk = $data['akun_kas_masukx'];
                $akun_kas_keluar = $data['akun_kas_keluarx'];
                $tipe_jurnal = $this->getTipeJurnal('JMR');
                
                $keterangan = $data['keterangan'];
                $keterangan_d = $data['desc_mutasi_masuk'].' dari bank '.$data['desc_akun_kas_keluar'];
                $keterangan_k = $data['desc_mutasi_keluar'].' ke bank '.$data['desc_akun_kas_masuk'];


                if($jumlah_mutasi_d != $jumlah_mutasi_k){
                    $result = array('success' => 'false','message'=>'Jumlah Mutasi tidak balance!');
                    return $result;
                    die();
                }

                if($jumlah_mutasi == $jumlah_mutasi_k){

                    $this->db->trans_start();
                    $this->db->trans_strict(FALSE);

                    //update status mutasi
                    $data_mutasi = array( 
                        'status' => 2
                    );
                    $this->db->where('id', $mutasi_id);
                    $doupdate = $this->db->update('mutasi_kas_bank', $data_mutasi);

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
                            'total'         => $jumlah_mutasi,
                            'mutasi_kas_bank'   => $mutasi_id,
                            'keterangan'    => $keterangan,
                            'is_deleted'    => 2,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('jurnal_header', $data_jurnal_header);
                        $jurnal_id = $this->db->insert_id();

                        //insert tabel jurnal_detail D
                        $data_jurnal_detail_d = array( 
                            'jurnal_header' => $jurnal_id,
                            'cabang'        => $cabang,
                            'akun'          => $akun_kas_masuk,
                            'penerimaan_det'   => $penerimaan_det_id,
                            'debet'         => $jumlah_mutasi,
                            'kredit'        => 0,
                            'keterangan'    => $keterangan_d,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('jurnal_detail', $data_jurnal_detail_d);

                        //insert tabel jurnal_detail K
                        $data_jurnal_detail_k = array( 
                            'jurnal_header' => $jurnal_id,
                            'cabang'        => $cabang,
                            'akun'          => $akun_kas_keluar,
                            'pengeluaran_det'   => $pengeluaran_det_id,
                            'debet'         => 0,
                            'kredit'        => $jumlah_mutasi,
                            'keterangan'    => $keterangan_k,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('jurnal_detail', $data_jurnal_detail_k);

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

    function reopenTransaksi($mutasi_id=null, $cabang=null) {
        
        if(!empty($mutasi_id) && !empty($cabang)){

            //cek
            $query = $this->db->where("mutasi_kas_bank", $mutasi_id)->get('jurnal_header');
            $data_jurnal = $query->row_array();
            $jurnal_header_id = $data_jurnal['id'];

            //trans_start
            $this->db->trans_start();
            $this->db->trans_strict(FALSE);

            //update status mutasi_kas_bank jd pengakuan
            $this->db->where('id', $mutasi_id);
            $doupdate = $this->db->update('mutasi_kas_bank', array('status' => 1));

            if($doupdate){

                $this->db->where('mutasi_kas_bank', $mutasi_id);
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
            $result = array('success' => 'false','message'=>'Penerimaan kas tidak ditemukan!');
        }
        
        return $result;
        die();
    }

    function generateNoJurnal($cabang, $tgl){
        
        $dcabang = $this->cabang_model->getData($cabang);
        $kode_cabang = $dcabang['kode'];
        $prefix = 'JMR';
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
    
    function getKodeTransaksiMasuk($id=null) {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        $this->db->where('tipe', '3');
        $this->db->where('level', '3');
        $query = $this->db->select('*')->get('m_kode_transaksi');
        return $query->result_array();
    }

    function getKodeTransaksiKeluar($id=null) {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_deleted', 2);
        $this->db->where('tipe', '4');
        $this->db->where('level', '3');
        $query = $this->db->select('*')->get('m_kode_transaksi');
        return $query->result_array();
    }

    function getAkunKas($cabang='') {

        $this->db->order_by('id', 'ASC');
        $this->db->where('cabang', $cabang);
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_akun_kas');
        return $query->result_array();
    }

}

?>