<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Saldo_awal_model extends CI_model {

    function getall() {
        
        $closing_saldo_awal = $this->input->post('closing_saldo_awal') ? $this->input->post('closing_saldo_awal') : 'f';
        $src_cabang = $this->input->post('cabang') ? $this->input->post('cabang') : '';
        $src_cabangx = $this->input->post('cabangx') ? $this->input->post('cabangx') : '';
        if (!empty($src_cabangx)) {
            $addwhere = ' AND b.cabang = '.$src_cabangx;
        }else{
            $addwhere = ' AND b.cabang = '.$src_cabang;
        }

        $this->datatables->select("a.id as id, b.id as id2, b.cabang, a.kode as kodex, a.kode, a.nama, a.level, a.posisi_akun, b.jumlah_debet, b.jumlah_kredit, b.keterangan", false);
        $this->datatables->from('m_akun as a');
        $this->datatables->join('saldo_awal as b','a.id = b.akun '.$addwhere,'LEFT');
        $this->datatables->where('a.is_deleted', 2);
        if($closing_saldo_awal == 't'){
            $this->datatables->add_column('view', '<a disabled="disabled" data-id="$1" data-cabang="$2" class="btn btn-icon btn-outline-primary" title="" data-original-title="Saldo awal sudah closing"> 
                                                    <i class="feather icon-edit"></i></a>', 'id, cabang');
        }else{
            $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" data-cabang="$2" class="btn btn-icon btn-outline-primary" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="feather icon-edit"></i></a>', 'id, cabang');
        }
            
        return $this->datatables->generate();
    }

    function getData($id = null, $cabang = null) {

        if (isset($id)) {
            $addwhere = '';
            if (!empty($cabang)) {
                $addwhere = ' AND b.cabang = '.$cabang;
            }

            $this->db->select("a.id as id, b.id as saldo_id, b.cabang, a.kode as kodex, a.kode, a.nama, a.level, a.posisi_akun, b.jumlah_debet, b.jumlah_kredit, b.keterangan", false);
            $this->db->from('m_akun as a');
            $this->db->join('saldo_awal as b','a.id = b.akun '.$addwhere,'LEFT');
            $this->db->where('a.is_deleted', 2);
            $this->db->where('a.id', $id);
            $query = $this->db->get();
            return $query->row_array();
        } 
    }

    public function addData($data) {

        $this->db->insert('saldo_awal', $data);
        return $this->db->insert_id();
    }

    public function editData($data, $id) {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('saldo_awal', $data);
        } 
        return $id;
    }

    public function getSetupPeriodeSaldoAwal($cabang) {

        $this->db->where('cabang', $cabang);
        $query = $this->db->get('setup_periode_laporan');
        
        return $query->row_array();
    }

    
    public function closingSaldoAwal($cabang) {
        //Closing Saldo awal —> insert semua akun di neraca saldo berdasarkan periode saldo awal
         if (!empty($cabang)) {
            $userinput = $this->customlib->getSessionUsername();
            
            //cek closing saldo awal 
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            $data_setup = $query->row_array();
            
            $closing_saldo_awal = $data_setup['closing_saldo_awal'];
            $closing_saldo_awal_by = $data_setup['closing_saldo_awal_by'];
            $closing_saldo_awal_date = $data_setup['closing_saldo_awal_date'];
            $currency = $data_setup['currency'];
             
            $periode_saldo_awal = $data_setup['periode_saldo_awal'];
        
             
            if(empty($periode_saldo_awal)){
                $result = array('success' => 'false','message'=>'Setup Periode Saldo Awal belum diisi, silahkan setup terlebih dahulu! (Setup Periode laporan)');
            }else if($closing_saldo_awal == 't'){
                $result = array('success' => 'false','message'=>'Closing Saldo Awal sudah dilakukan pada tanggal '.$closing_saldo_awal_date.' oleh '.$closing_saldo_awal_by);
            }else{
                
                $periode_exp = explode('/',$periode_saldo_awal);
                $periode_bln = trim($periode_exp[0]);
                $periode_thn = trim($periode_exp[1]);
                
                $this->db->select("a.*", false);
                $this->db->where('a.is_deleted', 2);
                $this->db->where('a.cabang', $cabang);
                $query = $this->db->get('saldo_awal a');
                $data_detail = $query->result_array();
                
                
                
                //trans_start
                $this->db->trans_start();
                $this->db->trans_strict(FALSE);

                foreach($data_detail as $dt_detail){
                    
                    //cek akun di neraca saldo
                    $query3 = $this->db->where(array('akun'=>$dt_detail['akun'], 'cabang'=>$cabang, 'tahun'=>$periode_thn, 'periode'=>$periode_bln))->get("neraca_saldo");
                    $dt_saldo = $query3->row_array();
                    
                    
                    if($dt_saldo['id']){
                        
                        $mutasi_saldo = $dt_detail['jumlah_debet'] - $dt_detail['jumlah_kredit'];
                        $saldo = $dt_saldo['saldo'] + $mutasi_saldo;
                        
                        $data_neraca_saldo = array( 
                            'saldo_bulan_lalu'  => $saldo,
                            'saldo'         => $saldo,
                            'modified_by'   => $userinput,
                            'modified_date' => date('Y-m-d H:i:s'),
                        );
                        
                        $this->db->where('id', $dt_saldo['id']);
                        $this->db->update('neraca_saldo', $data_neraca_saldo);
                        
                    }else{
                        
                        $saldo = $dt_detail['jumlah_debet'] - $dt_detail['jumlah_kredit'];
                        
                        $data_neraca_saldo = array( 
                            'cabang'        => $cabang,
                            'akun'          => $dt_detail['akun'],
                            'currency'      => $currency,
                            'nilai_kurs'    => 1,
                            'mutasi_debet'  => 0,
                            'mutasi_kredit' => 0,
                            'saldo_bulan_lalu'   => $saldo,
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
                $data_setup = array( 
                    'tutup_periode_saldoawal'    => 't',
                    'closing_saldo_awal'    => 't',
                    'closing_saldo_awal_by' => $userinput,
                    'closing_saldo_awal_date'  => date('Y-m-d H:i:s'),
                    'modified_by'   => $userinput,
                    'modified_date' => date('Y-m-d H:i:s'),
                );
                $this->db->where('cabang', $cabang);
                $this->db->update('setup_periode_laporan', $data_setup);
                
                
                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    $result = array('success' => 'false','message'=>'Closing Saldo Awal gagal!');
                } else {

                    $this->db->trans_commit();
                    $result = array('success' => 'true','message'=>'Closing Saldo Awal berhasil..');
                }

                
            }
            
        }else{
            $result = array('success' => 'false','message'=>'Unit Kerja tidak ditemukan!');
        }
        
        return $result;
        die();
    }

    function getTotalSaldoAwal($cabang='') {

        $this->db->where('cabang', $cabang);
        $this->db->where('is_deleted', 2);
        $query = $this->db->select('sum(jumlah_debet) as total_debet, sum(jumlah_kredit) as total_kredit')->get("saldo_awal");
        return $query->row_array();
        
    }

    
}

?>