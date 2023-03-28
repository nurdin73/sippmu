<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Report_model extends CI_model {


    function getDataMutasiKasBank($cabang, $src_date1, $src_date2, $src_akun_kas='') {
        
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        if (!empty($src_akun_kas)) {
            $this->db->select("a.*", false);
            $this->db->from('vw_mutasi_kas_bank a');
            $this->db->where('a.cabang', $cabang, false);
            $this->db->where("a.tanggal::date >= '".$tanggal1."' AND a.tanggal::date <= '".$tanggal2."'", '', false);
            $this->db->where('a.akun_kas', $src_akun_kas);
            $this->db->order_by('a.tanggal', 'ASC');
            $query = $this->db->get();

            $data_result = array(
                'total'  => $query->num_rows(),
                'result'  => $query->result_array(),
                );
        }else{
            $data_result = array(
                'total'  => 0,
                'result'  => array(),
                );
        }
        return $data_result;
        
    }

    function getDataPosisiSaldoKasBank($cabang, $src_date1, $src_date2) {
        
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        $query = $this->db->query("select a.deskripsi, coalesce(a.saldo_akhir,0) - coalesce(b.mutasi_debet,0) + coalesce(b.mutasi_kredit,0) as saldo_awal, coalesce(c.mutasi_debet,0) as masuk, coalesce(c.mutasi_kredit,0) as keluar				
                                from m_akun_kas a						
                                left join (						
                                    select a.cabang,a.akun_transaksi,sum(a.masuk) mutasi_debet,sum(a.keluar) mutasi_kredit 						
                                    from rekap_transaksi a where a.is_deleted=2 and a.tanggal >= '".$tanggal1."'						
                                    group by a.akun_transaksi,a.cabang						
                                ) b on b.akun_transaksi = a.id			
                                left join (						
                                    select a.cabang,a.akun_kas,sum(a.masuk) mutasi_debet,sum(a.keluar) mutasi_kredit 						
                                    from vw_mutasi_kas_bank a where a.tanggal >= '".$tanggal1."' and a.tanggal <= '".$tanggal2."'		
                                    group by a.akun_kas,a.cabang						
                                ) c on c.akun_kas = a.id					
                                where a.is_deleted = 2 and a.cabang = $cabang ORDER By a.id ASC");
        $q_result = $query->result_array();
        
        return $q_result;
        
    }

    function getDataPenerimaan($cabang, $src_date1, $src_date2, $src_akun_kas='', $src_user='', $src_text='') {
        
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        $this->db->select("a.id as idx, a.kode_transaksi, a.akun_kas, a.mutasi_kas_bank, a.jumlah, a.penerimaan, 
                        a2.no_transaksi, a2.tanggal, a2.total, a2.created_by, a2.keterangan,
                        b.kode as kode_trx, b.deskripsi as transaksi_nama, c.deskripsi as akun_kas_nama, d.no_jurnal", false);
        $this->db->from('penerimaan_det a');
        $this->db->join('penerimaan a2','a.penerimaan = a2.id');
        $this->db->join('m_kode_transaksi b','a.kode_transaksi = b.id');
        $this->db->join('m_akun_kas c','a.akun_kas = c.id');
        $this->db->join('jurnal_header d','a.penerimaan = d.penerimaan');
        $this->db->where('a.is_deleted', 2);
        $this->db->where('a2.is_deleted', 2);
        $this->db->where('a2.status >', 1 , false);
        $this->db->where('a2.cabang', $cabang, false);
        $this->db->where("a2.tanggal::date >= '".$tanggal1."' AND a2.tanggal::date <= '".$tanggal2."'", '', false);

        if (!empty($src_akun_kas)) {
            $this->db->where('a.akun_kas', $src_akun_kas);
        }
        if (!empty($src_user)) {
            $this->db->where('a2.created_by', $src_user);
        }
        
        if (!empty($src_text)) {
            $this->db->like('upper(a2.no_transaksi)', strtoupper($src_text),'both');
            $this->db->or_like('upper(d.no_jurnal)', strtoupper($src_text),'both');
        }
        
        $this->db->order_by('a2.id', 'ASC');
        $this->db->order_by('a.id', 'ASC');
        $query = $this->db->get();
        
        $data_result = array(
            'total'  => $query->num_rows(),
            'result'  => $query->result_array(),
            );
        
        return $data_result;
        
    }

    function getDataPengeluaran($cabang, $src_date1, $src_date2, $src_akun_kas='', $src_user='', $src_text='') {
        
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        $this->db->select("a.id as idx, a.kode_transaksi, a.akun_kas, a.mutasi_kas_bank, a.jumlah, a.pengeluaran, 
                        a2.no_transaksi, a2.tanggal, a2.total, a2.created_by, a2.keterangan,
                        b.kode as kode_trx, b.deskripsi as transaksi_nama, c.deskripsi as akun_kas_nama, d.no_jurnal", false);
        $this->db->from('pengeluaran_det a');
        $this->db->join('pengeluaran a2','a.pengeluaran = a2.id');
        $this->db->join('m_kode_transaksi b','a.kode_transaksi = b.id');
        $this->db->join('m_akun_kas c','a.akun_kas = c.id');
        $this->db->join('jurnal_header d','a.pengeluaran = d.pengeluaran');
        $this->db->where('a.is_deleted', 2);
        $this->db->where('a2.is_deleted', 2);
        $this->db->where('a2.status >', 1 , false);
        $this->db->where('a2.cabang', $cabang, false);
        $this->db->where("a2.tanggal::date >= '".$tanggal1."' AND a2.tanggal::date <= '".$tanggal2."'", '', false);

        if (!empty($src_akun_kas)) {
            $this->db->where('a.akun_kas', $src_akun_kas);
        }
        if (!empty($src_user)) {
            $this->db->where('a2.created_by', $src_user);
        }
        
        if (!empty($src_text)) {
            $this->db->like('upper(a2.no_transaksi)', strtoupper($src_text),'both');
            $this->db->or_like('upper(d.no_jurnal)', strtoupper($src_text),'both');
        }
        
        $this->db->order_by('a2.id', 'ASC');
        $this->db->order_by('a.id', 'ASC');
        $query = $this->db->get();
        
        $data_result = array(
            'total'  => $query->num_rows(),
            'result'  => $query->result_array(),
            );
        
        return $data_result;
    }

    function getDataPindahBuku($cabang, $src_date1, $src_date2, $src_akun_kas='', $src_akun_kas2='', $src_user='', $src_text='') {
        
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        $this->db->select("a.id as id, '' as nomor, a.no_transaksi, a.tanggal, a.jumlah, a.status, a.keterangan, a.created_by, g.no_jurnal, 
            b.nama as cabang_nama, e.deskripsi as akun_mutasi_masuk, c.deskripsi as desc_mutasi_masuk, f.deskripsi as akun_mutasi_keluar, d.deskripsi as desc_mutasi_keluar", false);
        $this->db->from('mutasi_kas_bank a');
        $this->db->join('m_cabang b','a.cabang = b.id');
        $this->db->join('m_kode_transaksi c','a.kode_transaksi_masuk = c.id','left');
        $this->db->join('m_kode_transaksi d','a.kode_transaksi_keluar = d.id','left');
        $this->db->join('m_akun_kas e','a.akun_kas_masuk = e.id','left');
        $this->db->join('m_akun_kas f','a.akun_kas_keluar = f.id','left');
        $this->db->join('jurnal_header g','a.id = g.mutasi_kas_bank');
        $this->db->where('a.is_deleted', 2);
        $this->db->where('a.status >', 1 , false);
        $this->db->where('a.cabang', $cabang);
        
        if (!empty($src_date1)) {
            $this->db->where("(a.tanggal >= '". $tanggal1 . " 00:00:00' AND a.tanggal <= '". $tanggal2 ." 23:59:59')");
        }
        
        if (!empty($src_akun_kas)) {
            $this->db->where('a.akun_kas_masuk', $src_akun_kas);
        }
        if (!empty($src_akun_kas2)) {
            $this->db->where('a.kode_transaksi_keluar', $src_akun_kas2);
        }
        if (!empty($src_user)) {
            $this->db->where('a.created_by', $src_user);
        }
        
        if (!empty($src_text)) {
            $this->db->like('upper(a.no_transaksi)', strtoupper($src_text),'both');
            $this->db->or_like('upper(g.no_jurnal)', strtoupper($src_text),'both');
        }
        
        $this->db->order_by('a.id', 'ASC');
        $query = $this->db->get();
        
        $data_result = array(
            'total'  => $query->num_rows(),
            'result'  => $query->result_array(),
            );
        
        return $data_result;
    }

    function getDataBukuBesar($cabang=null, $akun=null, $bulan=null, $tahun=null) {
        
        $tanggal1 = $tahun.'-'.$bulan.'-01';
        $tanggal2 = $this->customlib->lastDayOfTheMonth($tanggal1); //$tahun.'-'.$bulan.'-31';
        
        $this->db->select("a.*, a2.no_jurnal, a2.tanggal, b.kode as kode_trx, b.nama as transaksi_nama, e.nama as cabang_nama, b.posisi_akun", false);
        $this->db->from('jurnal_detail a');
        $this->db->join('jurnal_header a2','a2.id = a.jurnal_header');
        $this->db->join('m_akun b','a.akun = b.id');
        $this->db->join('m_cabang e','a.cabang = e.id');
        $this->db->where('a2.cabang', $cabang, false);
        $this->db->where("a2.tanggal::date >= '".$tanggal1."' AND a2.tanggal::date <= '".$tanggal2."'", '', false);
        
        if (!empty($akun)) {
            $this->db->where('a.akun', $akun);
        }
        
        $this->db->order_by('a.id', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
        
    }

	function getDataAktivitas($cabang=null,$tahun=null, $bulan=null) {
        
        /*
        ---laporan aktivitas bulanan
        select a.kode,a.nama,coalesce(sum(c.saldo),0) saldo from report_aktivitas a
        left join report_aktivitas_det b on b.report_aktivitas = a.id
        left join neraca_saldo c on c.akun = b.id_rek and c.periode='01' and c.tahun=2021
        group by a.kode,a.nama,a.urutan
        order by a.urutan

        ---laporan aktivitas tahunan
        select a.kode,a.nama,coalesce(sum(c.saldo),0) saldo from report_aktivitas a
        left join report_aktivitas_det b on b.report_aktivitas = a.id
        left join neraca_saldo c on c.akun = b.id_rek and c.periode='12' and c.tahun=2021
        group by a.kode,a.nama,a.urutan
        order by a.urutan
        */
        if(empty($bulan)){
            $bulan = '12'; //bulan_akhir_periode
        }

        if($cabang){

            $sql1 = "select a.kode, a.nama, a.level, a.parent, a.is_space_after, a.is_sub_total, a.keterangan, coalesce(sum(c.saldo),0) saldo
                from report_aktivitas a
                left join report_aktivitas_det b on b.report_aktivitas = a.id
                left join neraca_saldo c on c.akun = b.id_rek and c.periode='".$bulan."' and c.tahun='".$tahun."' -- and c.cabang = ".$cabang."
                group by a.kode, a.nama, a.level, a.parent, a.is_space_after, a.is_sub_total, a.keterangan, a.urutan, c.cabang
                order by a.urutan";
    
            $q_report1 = $this->db->query($sql1);
            $q_report_aktivitas = $q_report1->result_array();
            
            return $q_report_aktivitas;
            
        }else{
            $sql1 = "select a.kode, a.nama, a.level, a.parent, a.is_space_after, a.is_sub_total, a.keterangan, 0 as saldo
                from report_aktivitas a
                left join report_aktivitas_det b on b.report_aktivitas = a.id
                group by a.kode, a.nama, a.level, a.parent, a.is_space_after, a.is_sub_total, a.keterangan, a.urutan
                order by a.urutan";
    
            $q_report1 = $this->db->query($sql1);
            $q_report_aktivitas = $q_report1->result_array();
            
            return $q_report_aktivitas;
        }
        
	}

	function getDataPosisiKeuangan($cabang=null, $tahun=null, $bulan=null) {
        
        /*
        ---laporan posisi keuangan bulanan
        select a.kode,a.nama,coalesce(sum(c.saldo),0) saldo from report_posisi_keuangan a
        left join report_posisi_keuangan_det b on b.report_posisi_keuangan = a.id
        left join neraca_saldo c on c.akun = b.id_rek and c.periode='01' and c.tahun=2021
        group by a.kode,a.nama,a.urutan
        order by a.urutan

        ---laporan posisi keuangan tahunan
        select a.kode,a.nama,coalesce(sum(c.saldo),0) saldo from report_posisi_keuangan a
        left join report_posisi_keuangan_det b on b.report_posisi_keuangan = a.id
        left join neraca_saldo c on c.akun = b.id_rek and c.periode='12' and c.tahun=2021
        group by a.kode,a.nama,a.urutan
        order by a.urutan
        */

        if(empty($bulan)){
            $bulan = '12'; //bulan_akhir_periode
        }
        if($cabang){
            $sql1 = "select a.kode,a.nama,coalesce(sum(c.saldo),0) saldo, a.level, a.is_space_after, a.is_posisi, a.is_sub_total
                    from report_posisi_keuangan a
                    left join report_posisi_keuangan_det b on b.report_posisi_keuangan = a.id
                    left join neraca_saldo c on c.akun = b.id_rek and c.periode='".$bulan."' and c.tahun='".$tahun."' and c.cabang = ".$cabang."
                    group by a.kode,a.nama,a.urutan,a.level,a.is_space_after,a.is_posisi,a.is_sub_total,c.cabang
                    order by a.urutan";
            //echo $sql1;
            $q_report1 = $this->db->query($sql1);
            $q_report_posisi_keuangan = $q_report1->result_array();
            
            return $q_report_posisi_keuangan;
        }else{
            return false;
        }
	}

    function getDataArusKas() {
        
        $this->db->select("*", false);
        $this->db->from('m_arus_kas');
        $this->db->where('is_deleted', 2);
        $this->db->order_by('kode', 'ASC');
        $query = $this->db->get();
        
        return $query->result_array();
        
    }

    
    function getSaldoAwal($src_cabangx, $src_date1, $src_date2, $src_akun_kas=null) {
        $tanggal1 = date('Y-m-d', strtotime($src_date1));
        $tanggal2 = date('Y-m-d', strtotime($src_date2));
        
        if($src_akun_kas){
            
            $q_func = $this->db->query( "SELECT * FROM saldo_awal('".$tanggal1."','".$tanggal2."',".$src_akun_kas.",".$src_cabangx.")");
            $result = $q_func->result_array();
         
            return $result[0]['saldo_awal'];
        }else{
            return 0;
        }
        
    }

    
    function getSaldoAwalArusKas($cabang=null, $bulan=null, $tahun=null) {
        
        $tanggal1 = $tahun.'-'.$bulan.'-01';
        $tanggal2 = $this->customlib->lastDayOfTheMonth($tanggal1); //$tahun.'-'.$bulan.'-31';
        
        if($bulan == '01'){
            $bulan_before = '12';
            $tahun_before = $tahun - 1;
        }else{
            $bulan_before = sprintf("%02d", ($bulan - 1));
            $tahun_before = $tahun;
        }
        
		$where_cabang = '';
		if($cabang){
           $where_cabang = " and a.cabang=".$cabang."";
        }
			
        $q_akun = $this->db->query("select akun from m_akun_kas a
									join m_akun b ON b.id = a.akun
									where a.is_deleted=2 AND b.is_deleted=2 ".$where_cabang." ");
									
									
									
        $q_akun_kas = $q_akun->result_array();
        $akun_arr = array();
        foreach($q_akun_kas as $kas){
            $akun_arr[] = $kas['akun'];
        }
        $akun_impl = implode(',', $akun_arr);
        
        
        //--Saldo Awal Arus Kas
        if($akun_impl){
            $add_where = '';
            if($cabang){
                $add_where = " and a.cabang=".$cabang."";
            }
            $q_saldo_awal = $this->db->query("select COALESCE(sum(a.saldo_bulan_lalu),0) saldo from neraca_saldo a
                                    where a.periode='".$bulan."' and a.tahun=".$tahun." and a.akun IN (".$akun_impl.") ".$add_where."");
            $saldo_awalx = $q_saldo_awal->row_array();
            $saldo_awal = $saldo_awalx['saldo'];
            
        }else{
            
            $saldo_awal = 0;
        }
        
        return $saldo_awal;
        
    }


     function getSaldoAwalArusKasTahun($cabang=null, $tahun=null) {
        
        $where_cabang = '';
        if($cabang){
           $where_cabang = " and a.cabang=".$cabang."";
        }
            
        $q_akun = $this->db->query("select akun from m_akun_kas a
                                    join m_akun b ON b.id = a.akun
                                    where a.is_deleted=2 AND b.is_deleted=2 ".$where_cabang." ");
                                    
                                    
                                    
        $q_akun_kas = $q_akun->result_array();
        $akun_arr = array();
        foreach($q_akun_kas as $kas){
            $akun_arr[] = $kas['akun'];
        }
        $akun_impl = implode(',', $akun_arr);
        
        
        //--Saldo Awal Arus Kas
        if($akun_impl){
            $add_where = '';
            if($cabang){
                $add_where = " and a.cabang=".$cabang."";
            }
            $q_saldo_awal = $this->db->query("select COALESCE(sum(a.saldo_bulan_lalu),0) saldo from neraca_saldo a
                                    where a.periode='01' and a.tahun=".$tahun." and a.akun IN (".$akun_impl.") ".$add_where."");
            $saldo_awalx = $q_saldo_awal->row_array();
            $saldo_awal = $saldo_awalx['saldo'];
            
        }else{
            
            $saldo_awal = 0;
        }
        
        return $saldo_awal;
        
    }

    function getReportNeraca($bulan=null, $tahun=null, $cabang=null) {
        /*
        select b.kel_akun, b.kode, sum(a.saldo) saldo, 
        b.nama as nama_lv5, b2.nama as nama_lv4, b3.nama as nama_lv3, b4.nama as nama_lv2, b5.nama as nama_lv1,
        b.parent as parent_lv5, b2.parent as parent_lv4, b3.parent as parent_lv3, b4.parent as parent_lv2, b5.parent as parent_lv1
        from neraca_saldo a
        join m_akun b on b.id = a.akun
        join m_akun b2 on b2.id = b.parent
        join m_akun b3 on b3.id = b2.parent
        join m_akun b4 on b4.id = b3.parent
        join m_akun b5 on b5.id = b4.parent
        where b.kel_akun in ('1','2','3') and a.periode='01' and a.tahun=2020 and a.cabang=3 ---jika pilihan per cabang
        group by b.kel_akun, b.kode, nama_lv5, nama_lv4, nama_lv3,nama_lv2,nama_lv1,parent_lv5,parent_lv4,parent_lv3,parent_lv2,parent_lv1
        order by b.kel_akun
        */
        //$bulan = '01';
        //$tahun = '2020';
        $tahun_sblm = $tahun-1;
        
        $add_where = '';
        if(!empty($cabang)){
            $add_where = " and cabang=".$cabang."";
        }
        
        $sql1 = "select b.kel_akun, b.kode, coalesce(sum(a.saldo),0) saldo_skr, 
                coalesce((select sum(saldo) from neraca_saldo where akun = b.id and tahun='".$tahun_sblm."' and periode ='".$bulan."' ".$add_where."),0) as saldo_lalu,
                b.id as akun_lv5, b.parent as akun_lv4, b2.parent as akun_lv3, b3.parent as akun_lv2, b4.parent as akun_lv1,
                b.nama as nama_lv5, b2.nama as nama_lv4, b3.nama as nama_lv3, b4.nama as nama_lv2, b5.nama as nama_lv1
                from m_akun b
                left join neraca_saldo a on b.id = a.akun and a.periode='".$bulan."' and a.tahun='".$tahun."' ".$add_where."
                join m_akun b2 on b2.id = b.parent
                join m_akun b3 on b3.id = b2.parent
                join m_akun b4 on b4.id = b3.parent
                join m_akun b5 on b5.id = b4.parent
                where b.kel_akun in ('1','2','3') 
                group by b.kel_akun, b.kode, nama_lv5, nama_lv4, nama_lv3,nama_lv2,nama_lv1,akun_lv5, akun_lv4,akun_lv3,akun_lv2,akun_lv1, b.id
                order by b.kel_akun ASC, b.kode asc";
        
        $q_report1 = $this->db->query($sql1);
        $q_report_neraca1 = $q_report1->result_array();
        
        $total_level5 = array();
        $total_level4 = array();
        $total_level3 = array();
        $total_level2 = array();
        $total_level1 = array();
        
        foreach($q_report_neraca1 as $row){
            if(empty($total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_lalu'])){
                $total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_lalu'] = 0;
            }
            if(empty($total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_skr'])){
                $total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_skr'] = 0;
            }
            if(empty($total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_lalu'])){
                $total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_lalu'] = 0;
            }
            if(empty($total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_skr'])){
                $total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_skr'] = 0;
            }
            if(empty($total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_lalu'])){
                $total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_lalu'] = 0;
            }
            if(empty($total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_skr'])){
                $total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_skr'] = 0;
            }
            if(empty($total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_lalu'])){
                $total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_lalu'] = 0;
            }
            if(empty($total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_skr'])){
                $total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_skr'] = 0;
            }
            if(empty($total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_lalu'])){
                $total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_lalu'] = 0;
            }
            if(empty($total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_skr'])){
                $total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_skr'] = 0;
            }
            
            //mutlakan kewajiban & dana
            if($row['kel_akun'] == 2 || $row['kel_akun'] == 3){
                //if($row['saldo_lalu'] < 0){
                    $row['saldo_lalu'] = $row['saldo_lalu'] * -1;
                //}
                //if($row['saldo_skr'] < 0){
                    $row['saldo_skr'] = $row['saldo_skr'] * -1;
                //}
            }

            $total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_lalu'] += $row['saldo_lalu'];
            $total_level5[$row['kel_akun']][$row['akun_lv5']]['saldo_skr'] +=  $row['saldo_skr'];
            
            $total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_lalu'] += $row['saldo_lalu'];
            $total_level4[$row['kel_akun']][$row['akun_lv4']]['saldo_skr'] +=  $row['saldo_skr'];
            
            $total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_lalu'] += $row['saldo_lalu'];
            $total_level3[$row['kel_akun']][$row['akun_lv3']]['saldo_skr'] +=  $row['saldo_skr'];
            
            $total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_lalu'] += $row['saldo_lalu'];
            $total_level2[$row['kel_akun']][$row['akun_lv2']]['saldo_skr'] +=  $row['saldo_skr'];
            
            $total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_lalu'] += $row['saldo_lalu'];
            $total_level1[$row['kel_akun']][$row['akun_lv1']]['saldo_skr'] +=  $row['saldo_skr'];
        }
        
        
        
        $sql = "select acc.id, acc.kode, acc.nama, acc.kel_akun, acc.level, acc.parent,
                coalesce((select sum(coalesce(saldo.saldo,0)) from neraca_saldo saldo where akun = acc.id and tahun ='".$tahun_sblm."' and periode ='".$bulan."' ".$add_where."),0) saldo_lalu,
                coalesce((select sum(coalesce(saldo.saldo,0)) from neraca_saldo saldo where akun = acc.id and tahun ='".$tahun."' and periode ='".$bulan."' ".$add_where."),0) saldo_skr
                from m_akun acc
                left join neraca_saldo saldo on saldo.akun=acc.id ".$add_where."
                where acc.kel_akun IN ('1','2','3')
                group by acc.id, acc.kode, acc.nama, acc.kel_akun, acc.level
                order by acc.id ASC";
        $q_report = $this->db->query($sql);
        $q_report_neraca = $q_report->result_array();
        
        $data_neracax = array();
        $data_neracay = array();
        $data_neracaz = array();
        $x1 = 0;
        $x2 = 0;
        $x3 = 0;
        foreach($q_report_neraca as $row){
            
            if(empty($total_level1[$row['kel_akun']][$row['id']]['saldo_lalu'])){
                $total_level1[$row['kel_akun']][$row['id']]['saldo_lalu'] = 0;
            }
            if(empty($total_level1[$row['kel_akun']][$row['id']]['saldo_skr'])){
                $total_level1[$row['kel_akun']][$row['id']]['saldo_skr'] = 0;
            }
            if(empty($total_level2[$row['kel_akun']][$row['id']]['saldo_lalu'])){
                $total_level2[$row['kel_akun']][$row['id']]['saldo_lalu'] = 0;
            }
            if(empty($total_level2[$row['kel_akun']][$row['id']]['saldo_skr'])){
                $total_level2[$row['kel_akun']][$row['id']]['saldo_skr'] = 0;
            }
            if(empty($total_level3[$row['kel_akun']][$row['id']]['saldo_lalu'])){
                $total_level3[$row['kel_akun']][$row['id']]['saldo_lalu'] = 0;
            }
            if(empty($total_level3[$row['kel_akun']][$row['id']]['saldo_skr'])){
                $total_level3[$row['kel_akun']][$row['id']]['saldo_skr'] = 0;
            }
            
            if($row['kel_akun'] == 1){
                //Asset
                if($row['level'] == 3){
                    $data_neracax[$x1]['id_rek'] = $row['id'];
                    $data_neracax[$x1]['kode_rek'] = $row['kode'];
                    $data_neracax[$x1]['nama_rek'] = $row['nama'];
                    $data_neracax[$x1]['kel_akun'] = $row['kel_akun'];
                    $data_neracax[$x1]['parent'] = $row['parent'];
                    $data_neracax[$x1]['level'] = $row['level'];
                    $data_neracax[$x1]['saldo_lalu'] = $total_level3[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracax[$x1]['saldo_skr'] =  $total_level3[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x1 += 1;
        		}
        		else if($row['level'] == 2){
                    $data_neracax[$x1]['id_rek'] = $row['id'];
                    $data_neracax[$x1]['kode_rek'] = $row['kode'];
                    $data_neracax[$x1]['nama_rek'] = $row['nama'];
                    $data_neracax[$x1]['kel_akun'] = $row['kel_akun'];
                    $data_neracax[$x1]['parent'] = $row['parent'];
                    $data_neracax[$x1]['level'] = $row['level'];
                    $data_neracax[$x1]['saldo_lalu'] = $total_level2[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracax[$x1]['saldo_skr'] =  $total_level2[$row['kel_akun']][$row['id']]['saldo_skr'];
        		    
        			$x1 += 1;
        		}
        		else if($row['level'] == 1){
                    $data_neracax[$x1]['id_rek'] = $row['id'];
                    $data_neracax[$x1]['kode_rek'] = $row['kode'];
                    $data_neracax[$x1]['nama_rek'] = $row['nama'];
                    $data_neracax[$x1]['kel_akun'] = $row['kel_akun'];
                    $data_neracax[$x1]['parent'] = $row['parent'];
                    $data_neracax[$x1]['level'] = $row['level'];
                    $data_neracax[$x1]['saldo_lalu'] = $total_level1[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracax[$x1]['saldo_skr'] =  $total_level1[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x1 += 1;
        		}
            
            }else if($row['kel_akun'] == 2){ 
                //Saldo Dana
                if($row['level'] == 3){
                    $data_neracay[$x2]['id_rek'] = $row['id'];
                    $data_neracay[$x2]['kode_rek'] = $row['kode'];
                    $data_neracay[$x2]['nama_rek'] = $row['nama'];
                    $data_neracay[$x2]['kel_akun'] = $row['kel_akun'];
                    $data_neracay[$x2]['parent'] = $row['parent'];
                    $data_neracay[$x2]['level'] = $row['level'];
                    $data_neracay[$x2]['saldo_lalu'] = $total_level3[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracay[$x2]['saldo_skr'] =  $total_level3[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x2 += 1;
        		}
        		else if($row['level'] == 2){
                    $data_neracay[$x2]['id_rek'] = $row['id'];
                    $data_neracay[$x2]['kode_rek'] = $row['kode'];
                    $data_neracay[$x2]['nama_rek'] = $row['nama'];
                    $data_neracay[$x2]['kel_akun'] = $row['kel_akun'];
                    $data_neracay[$x2]['parent'] = $row['parent'];
                    $data_neracay[$x2]['level'] = $row['level'];
                    $data_neracay[$x2]['saldo_lalu'] = $total_level2[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracay[$x2]['saldo_skr'] =  $total_level2[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x2 += 1;
        		}
        		else if($row['level'] == 1){
                    $data_neracay[$x2]['id_rek'] = $row['id'];
                    $data_neracay[$x2]['kode_rek'] = $row['kode'];
                    $data_neracay[$x2]['nama_rek'] = $row['nama'];
                    $data_neracay[$x2]['kel_akun'] = $row['kel_akun'];
                    $data_neracay[$x2]['parent'] = $row['parent'];
                    $data_neracay[$x2]['level'] = $row['level'];
                    $data_neracay[$x2]['saldo_lalu'] = $total_level1[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracay[$x2]['saldo_skr'] =  $total_level1[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x2 += 1;
        		}
                
            
            }else if($row['kel_akun'] == 3){ 
                //Saldo Dana
                if($row['level'] == 3){
                    $data_neracaz[$x3]['id_rek'] = $row['id'];
                    $data_neracaz[$x3]['kode_rek'] = $row['kode'];
                    $data_neracaz[$x3]['nama_rek'] = $row['nama'];
                    $data_neracaz[$x3]['kel_akun'] = $row['kel_akun'];
                    $data_neracaz[$x3]['parent'] = $row['parent'];
                    $data_neracaz[$x3]['level'] = $row['level'];
                    $data_neracaz[$x3]['saldo_lalu'] = $total_level3[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracaz[$x3]['saldo_skr'] =  $total_level3[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x3 += 1;
        		}
        		else if($row['level'] == 2){
                    $data_neracaz[$x3]['id_rek'] = $row['id'];
                    $data_neracaz[$x3]['kode_rek'] = $row['kode'];
                    $data_neracaz[$x3]['nama_rek'] = $row['nama'];
                    $data_neracaz[$x3]['kel_akun'] = $row['kel_akun'];
                    $data_neracaz[$x3]['parent'] = $row['parent'];
                    $data_neracaz[$x3]['level'] = $row['level'];
                    $data_neracaz[$x3]['saldo_lalu'] = $total_level2[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracaz[$x3]['saldo_skr'] =  $total_level2[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x3 += 1;
        		}
        		else if($row['level'] == 1){
                    $data_neracaz[$x3]['id_rek'] = $row['id'];
                    $data_neracaz[$x3]['kode_rek'] = $row['kode'];
                    $data_neracaz[$x3]['nama_rek'] = $row['nama'];
                    $data_neracaz[$x3]['kel_akun'] = $row['kel_akun'];
                    $data_neracaz[$x3]['parent'] = $row['parent'];
                    $data_neracaz[$x3]['level'] = $row['level'];
                    $data_neracaz[$x3]['saldo_lalu'] = $total_level1[$row['kel_akun']][$row['id']]['saldo_lalu'];
                    $data_neracaz[$x3]['saldo_skr'] =  $total_level1[$row['kel_akun']][$row['id']]['saldo_skr'];
        		
        			$x3 += 1;
        		}
                
            }
            
        }
        
        $data_result = array(
            'data_neracax' =>   $data_neracax,
            'data_neracay' =>   $data_neracay,
            'data_neracaz' =>   $data_neracaz,
        );
        
        //echo '<pre>';print_r($data_neracax);die();
        return $data_result;
    }
    
    function getReportArusKas($cabang=null, $bulan=null, $tahun=null) {
        
        $tanggal1 = $tahun.'-'.$bulan.'-01';
        $tanggal2 = $this->customlib->lastDayOfTheMonth($tanggal1); //$tahun.'-'.$bulan.'-31';
        
        //----Laporan Arus Kas
        /*
        select b3.nama level_1,b2.nama level_2,b.nama level_3,a.nama level_4,sum(d.saldo) saldo from m_arus_kas a
        join m_arus_kas b on b.id = a.parent
        join m_arus_kas b2 on b2.id = b.parent
        join m_arus_kas b3 on b3.id = b2.parent
        join m_arus_kas_det c on c.arus_kas = a.id
        join neraca_saldo d on d.akun = c.akun
        where a.is_deleted=2 and a.level=4 and d.periode='03' and d.tahun=2020 and d.cabang=3
        group by b3.nama,b2.nama,b.nama,a.nama
        */
        $add_where2 = '';
        if($cabang){
            $add_where2 .= " and d.cabang=".$cabang."";
        }
        
        //get total parent
		//update dony 20210716
        $q_reportp = $this->db->query("select a.id, a.level, a.kode, a.parent,a.nama,sum(a.saldo) saldo from (
									  select a.id, a.level, a.kode, a.parent, a.tipe,e. kel_akun,a.nama, 
									  case when e.kel_akun <> '2' then (d.mutasi_debet - d.mutasi_kredit) 
									  else case when a.tipe = 1 then (-1 * d.mutasi_kredit)
										   else d.mutasi_debet
										   end
									  end saldo
									  from m_arus_kas a 
									  left join m_arus_kas_det c on c.arus_kas = a.id 
									  left join neraca_saldo d on d.akun = c.akun and d.periode='".$bulan."' and d.tahun=".$tahun." ".$add_where2."
									  left join m_akun e on e.id = d.akun
									  where a.is_deleted=2 
									  order by a.kode asc ) a
									  group by a.id, a.level, a.parent, a.nama, a.kode
									  order by a.kode asc");
        $q_report_parent = $q_reportp->result_array();
        
        $data_saldox = array();
        foreach($q_report_parent as $row){
            
            $kodex = substr($row['kode'],0,1);
            $kodex3 = substr($row['kode'],0,3);
            
            if($row['level'] == 1){
        		$akun_coa1 = $row['id'];
        	}elseif($row['level'] == 2){
        		$akun_coa2 = $row['id'];
        	}elseif($row['level'] == 3){
        		$akun_coa3 = $row['id'];
        	}elseif($row['level'] == 4){
        		$akun_coa4 = $row['id'];
        	}
            
            if($row['level'] == 4){
                if(empty($data_saldox['sisa_saldo'][$kodex3])){
                    $data_saldox['sisa_saldo'][$kodex3] = 0;
                }

                $data_saldox['sisa_saldo'][$kodex3] += $row['saldo'];
            }

            $data_saldox[$row['id']] = array(
                    'id'		        => $row['id'],
                    'nama'		        => $row['nama'],
                    'level'		        => $row['level'],
                    'parent'		    => $row['parent'],
                    'saldo'		        => ($row['saldo']) ? $row['saldo'] : 0,
            );
            
        	if($row['level'] > 1){
        		$data_saldox[$akun_coa1]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 2){
        		$data_saldox[$akun_coa2]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 3){
        		$data_saldox[$akun_coa3]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 4){
        		$data_saldox[$akun_coa4]['saldo'] += $row['saldo'];
        	}      
            
            
            
        }
        //echo '<pre>';print_r($data_saldox);die();
        
        $add_where3 = '';
        if($cabang){
            $add_where3 .= " and d.cabang=".$cabang."";
        }
        // detail
		//update dony 20210716
        $q_report = $this->db->query("select a.id, a.level, a.kode, a.parent,a.nama,sum(a.saldo) saldo from (
									  select a.id, a.level, a.kode, a.parent, a.tipe,e. kel_akun,a.nama, 
									  case when e.kel_akun <> '2' then (d.mutasi_debet - d.mutasi_kredit) 
									  else case when a.tipe = 1 then (-1 * d.mutasi_kredit)
										   else d.mutasi_debet
										   end
									  end saldo
									  from m_arus_kas a 
									  left join m_arus_kas_det c on c.arus_kas = a.id 
									  left join neraca_saldo d on d.akun = c.akun and d.periode='".$bulan."' and d.tahun=".$tahun." ".$add_where3."
									  left join m_akun e on e.id = d.akun
									  where a.is_deleted=2 
									  order by a.kode asc ) a
									  group by a.id, a.level, a.parent, a.nama, a.kode
									  order by a.kode asc ");
        $q_report_arus_kas = $q_report->result_array();
        
        $data_report = array();
        if($q_report_arus_kas){
            foreach($q_report_arus_kas as $row){
                
                $kodex = substr($row['kode'],0,1);
                $kodex3 = substr($row['kode'],0,3);
                
                if($row['level'] == 4){
                    if(empty($data_report[$row['id']][$kodex]['saldo'])){
                           $data_report['sisa_saldo'][$kodex3] = 0;
                    }
                    if($kodex3 == '1.1'){
                        $data_report['sisa_saldo']['1.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '1.2'){
                        $data_report['sisa_saldo']['1.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '2.1'){
                        $data_report['sisa_saldo']['2.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '2.2'){
                        $data_report['sisa_saldo']['2.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '3.1'){
                        $data_report['sisa_saldo']['3.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '3.2'){
                        $data_report['sisa_saldo']['3.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    
                }
                
                if(empty($data_report[$row['id']][$kodex]['saldo'])){
                    $data_report[$row['id']][$kodex]['saldo'] = 0;
                }
                
                $data_report[$row['id']][$kodex] = array(
                        'id'		        => $row['id'],
                        'nama'		        => $row['nama'],
                        'level'		        => $row['level'],
                        'parent'		    => $row['parent'],
                        'saldo'		        => ($row['saldo']) ? $row['saldo'] : 0,
                );


                if($row['level'] < 4){
                    $data_report[$row['id']][$kodex]['saldo'] += $data_saldox[$row['id']]['saldo'];
                }
                if($row['level'] == 1){
                    $data_report[$row['id']][$kodex]['saldo'] = '';
                }
                
            }
        }
        
        return $data_report;
        
    }


    function getReportArusKasTahun($cabang=null, $tahun=null) {
        
        $add_where2 = '';
        if($cabang){
            $add_where2 .= " and d.cabang=".$cabang."";
        }
        
        //get total parent
         $q_reportp = $this->db->query("select a.id, a.level, a.kode, a.parent,a.nama,sum(a.saldo) saldo from (
                                      select a.id, a.level, a.kode, a.parent, a.tipe,e. kel_akun,a.nama, 
                                      case when e.kel_akun <> '2' then (d.mutasi_debet - d.mutasi_kredit) 
                                      else case when a.tipe = 1 then (-1 * d.mutasi_kredit)
                                           else d.mutasi_debet
                                           end
                                      end saldo
                                      from m_arus_kas a 
                                      left join m_arus_kas_det c on c.arus_kas = a.id 
                                      left join neraca_saldo d on d.akun = c.akun and d.tahun=".$tahun." ".$add_where2."
                                      left join m_akun e on e.id = d.akun
                                      where a.is_deleted=2 
                                      order by a.kode asc ) a
                                      group by a.id, a.level, a.parent, a.nama, a.kode
                                      order by a.kode asc");
        $q_report_parent = $q_reportp->result_array();
        
        $data_saldox = array();
        foreach($q_report_parent as $row){
            
            $kodex = substr($row['kode'],0,1);
            $kodex3 = substr($row['kode'],0,3);
            
            if($row['level'] == 1){
                $akun_coa1 = $row['id'];
            }elseif($row['level'] == 2){
                $akun_coa2 = $row['id'];
            }elseif($row['level'] == 3){
                $akun_coa3 = $row['id'];
            }elseif($row['level'] == 4){
                $akun_coa4 = $row['id'];
            }
            
            if($row['level'] == 4){
                if(empty($data_saldox['sisa_saldo'][$kodex3])){
                    $data_saldox['sisa_saldo'][$kodex3] = 0;
                }

                $data_saldox['sisa_saldo'][$kodex3] += $row['saldo'];
            }

            $data_saldox[$row['id']] = array(
                    'id'                => $row['id'],
                    'nama'              => $row['nama'],
                    'level'             => $row['level'],
                    'parent'            => $row['parent'],
                    'saldo'             => ($row['saldo']) ? $row['saldo'] : 0,
            );
            
            if($row['level'] > 1){
                $data_saldox[$akun_coa1]['saldo'] += $row['saldo'];
            }
            
            if($row['level'] > 2){
                $data_saldox[$akun_coa2]['saldo'] += $row['saldo'];
            }
            
            if($row['level'] > 3){
                $data_saldox[$akun_coa3]['saldo'] += $row['saldo'];
            }
            
            if($row['level'] > 4){
                $data_saldox[$akun_coa4]['saldo'] += $row['saldo'];
            }      
            
            
            
        }
        //echo '<pre>';print_r($data_saldox);die();
        
        $add_where3 = '';
        if($cabang){
            $add_where3 .= " and d.cabang=".$cabang."";
        }
        // detail
        $q_report = $this->db->query("select a.id, a.level, a.kode, a.parent,a.nama,sum(a.saldo) saldo from (
                                      select a.id, a.level, a.kode, a.parent, a.tipe,e. kel_akun,a.nama, 
                                      case when e.kel_akun <> '2' then (d.mutasi_debet - d.mutasi_kredit) 
                                      else case when a.tipe = 1 then (-1 * d.mutasi_kredit)
                                           else d.mutasi_debet
                                           end
                                      end saldo
                                      from m_arus_kas a 
                                      left join m_arus_kas_det c on c.arus_kas = a.id 
                                      left join neraca_saldo d on d.akun = c.akun and d.tahun=".$tahun." ".$add_where3."
                                      left join m_akun e on e.id = d.akun
                                      where a.is_deleted=2 
                                      order by a.kode asc ) a
                                      group by a.id, a.level, a.parent, a.nama, a.kode
                                      order by a.kode asc ");
        $q_report_arus_kas = $q_report->result_array();
        
        $data_report = array();
        if($q_report_arus_kas){
            foreach($q_report_arus_kas as $row){
                
                $kodex = substr($row['kode'],0,1);
                $kodex3 = substr($row['kode'],0,3);
                
                if($row['level'] == 4){
                    if(empty($data_report[$row['id']][$kodex]['saldo'])){
                           $data_report['sisa_saldo'][$kodex3] = 0;
                    }
                    if($kodex3 == '1.1'){
                        $data_report['sisa_saldo']['1.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '1.2'){
                        $data_report['sisa_saldo']['1.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '2.1'){
                        $data_report['sisa_saldo']['2.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '2.2'){
                        $data_report['sisa_saldo']['2.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '3.1'){
                        $data_report['sisa_saldo']['3.1'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    if($kodex3 == '3.2'){
                        $data_report['sisa_saldo']['3.2'] += $data_saldox['sisa_saldo'][$kodex3];
                    }
                    
                }
                
                if(empty($data_report[$row['id']][$kodex]['saldo'])){
                    $data_report[$row['id']][$kodex]['saldo'] = 0;
                }
                
                $data_report[$row['id']][$kodex] = array(
                        'id'                => $row['id'],
                        'nama'              => $row['nama'],
                        'level'             => $row['level'],
                        'parent'            => $row['parent'],
                        'saldo'             => ($row['saldo']) ? $row['saldo'] : 0,
                );


                if($row['level'] < 4){
                    $data_report[$row['id']][$kodex]['saldo'] += $data_saldox[$row['id']]['saldo'];
                }
                if($row['level'] == 1){
                    $data_report[$row['id']][$kodex]['saldo'] = '';
                }
                
            }
        }
        
        return $data_report;
        
    }

    function getDataNeracaSaldo($cabang, $bulan, $tahun, $level='') {
        
        $tanggal1 = $tahun.'-'.$bulan.'-01';
        $tanggal2 = $this->customlib->lastDayOfTheMonth($tanggal1); 
        
        //get total parent
        $this->db->select("a.id, a.kel_akun, a.kode, a.nama, a.level, a.parent, a.posisi_akun, b.mutasi_debet, b.mutasi_kredit, b.saldo_bulan_lalu, b.saldo, b.posisi", false);
        $this->db->from('m_akun a');
        $this->db->join('neraca_saldo b',"a.id = b.akun AND b.periode = '".$bulan."' AND b.tahun = '".$tahun."' AND b.cabang = ".$cabang."", 'left');
        $this->db->join('m_cabang e','b.cabang = e.id AND b.cabang = '.$cabang.'','left');
        $this->db->where('a.is_deleted', 2, false);
        $this->db->order_by('a.kode', 'ASC');
        $query = $this->db->get();
        
        $data_saldox = array();
        foreach($query->result_array() as $row){
            
            if($row['level'] == 1){
        		$akun_coa1 = $row['id'];
        	}elseif($row['level'] == 2){
        		$akun_coa2 = $row['id'];
        	}elseif($row['level'] == 3){
        		$akun_coa3 = $row['id'];
        	}elseif($row['level'] == 4){
        		$akun_coa4 = $row['id'];
        	}
            
            $data_saldox[$row['id']] = array(
                    'id'		        => $row['id'],
                    'kel_akun'		    => $row['kel_akun'],
                    'kode'		        => $row['kode'],
                    'nama'		        => $row['nama'],
                    'level'		        => $row['level'],
                    'parent'		    => $row['parent'],
                    'posisi'		    => $row['posisi'],
                    'saldo_bulan_lalu'	=> ($row['saldo_bulan_lalu'] ? $row['saldo_bulan_lalu'] : 0),
                    'mutasi_debet'		=> $row['mutasi_debet'],
                    'mutasi_kredit'		=> $row['mutasi_kredit'],
                    'saldo'		        => ($row['saldo']) ? $row['saldo'] : 0,
            );
            
        	if($row['level'] > 1){
        		$data_saldox[$akun_coa1]['saldo_bulan_lalu'] += $row['saldo_bulan_lalu'];
        		$data_saldox[$akun_coa1]['mutasi_debet'] += $row['mutasi_debet'];
        		$data_saldox[$akun_coa1]['mutasi_kredit'] += $row['mutasi_kredit'];
        		$data_saldox[$akun_coa1]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 2){
        		$data_saldox[$akun_coa2]['saldo_bulan_lalu'] += $row['saldo_bulan_lalu'];
        		$data_saldox[$akun_coa2]['mutasi_debet'] += $row['mutasi_debet'];
        		$data_saldox[$akun_coa2]['mutasi_kredit'] += $row['mutasi_kredit'];
        		$data_saldox[$akun_coa2]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 3){
        		$data_saldox[$akun_coa3]['saldo_bulan_lalu'] += $row['saldo_bulan_lalu'];
        		$data_saldox[$akun_coa3]['mutasi_debet'] += $row['mutasi_debet'];
        		$data_saldox[$akun_coa3]['mutasi_kredit'] += $row['mutasi_kredit'];
        		$data_saldox[$akun_coa3]['saldo'] += $row['saldo'];
        	}
        	
        	if($row['level'] > 4){
        		$data_saldox[$akun_coa4]['saldo_bulan_lalu'] += $row['saldo_bulan_lalu'];
        		$data_saldox[$akun_coa4]['mutasi_debet'] += $row['mutasi_debet'];
        		$data_saldox[$akun_coa4]['mutasi_kredit'] += $row['mutasi_kredit'];
        		$data_saldox[$akun_coa4]['saldo'] += $row['saldo'];
        	}    
            
        }
        
        $this->db->select("a.id, a.kel_akun, a.kode, a.nama, a.level, a.parent, a.posisi_akun, b.mutasi_debet, b.mutasi_kredit, b.saldo_bulan_lalu, b.saldo, b.posisi", false);
        $this->db->from('m_akun a');
        $this->db->join('neraca_saldo b',"a.id = b.akun AND b.periode = '".$bulan."' AND b.tahun = '".$tahun."' AND b.cabang = ".$cabang."", 'left');
        $this->db->join('m_cabang e','b.cabang = e.id AND b.cabang = '.$cabang.'','left');
        $this->db->where('a.is_deleted', 2, false);
        if(!empty($level)){
            $this->db->where('a.level', $level, false);
        }
        $this->db->order_by('a.kode', 'ASC');
        $query = $this->db->get();
        
        $data_report = array();
        foreach($query->result_array() as $row){

            $posisi_akun = ($row['posisi_akun'] == 1) ? 'D' : 'K'; 
            
            $data_report[$row['id']] = array(
                    'id'		        => $row['id'],
                    'kel_akun'		    => $row['kel_akun'],
                    'kode'		        => $row['kode'],
                    'nama'		        => $row['nama'],
                    'level'		        => $row['level'],
                    'parent'		    => $row['parent'],
                    'posisi'		    => $row['posisi'] ? $row['posisi'] : $posisi_akun,
                    'saldo_bulan_lalu'	=> ($row['saldo_bulan_lalu'] ? $row['saldo_bulan_lalu'] : 0),
                    'mutasi_debet'		=> $row['mutasi_debet'],
                    'mutasi_kredit'		=> $row['mutasi_kredit'],
                    'saldo'		        => ($row['saldo']) ? $row['saldo'] : 0,
            );

            
        	if($row['level'] < 5){
        		$data_report[$row['id']]['saldo_bulan_lalu'] += $data_saldox[$row['id']]['saldo_bulan_lalu'];
        		$data_report[$row['id']]['mutasi_debet'] += $data_saldox[$row['id']]['mutasi_debet'];
        		$data_report[$row['id']]['mutasi_kredit'] += $data_saldox[$row['id']]['mutasi_kredit'];
        		$data_report[$row['id']]['saldo'] += $data_saldox[$row['id']]['saldo'];
        	}
        	
        }
        
        return $data_report;
        
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
            return $query->result_array();
            
        } else {
        
            $this->db->order_by('kode', 'ASC');
            $this->db->where('is_deleted', 2);
            $this->db->where('level', '5');
            $query = $this->db->select('*')->get('m_akun');
            return $query->result_array();
        }
    }

    function getSaldo($cabang, $akun, $bulan, $tahun) {
            
        $this->db->select('saldo, saldo_bulan_lalu');
        $this->db->where("cabang", $cabang);
        $this->db->where("akun", $akun);
        $this->db->where("periode", $bulan);
        $this->db->where("tahun", $tahun);
        $query = $this->db->get('neraca_saldo');
        return $query->row_array();
    }

    function getKelAkun() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_kel_akun');
        return $query->result_array();
    }

    function getCabangByID($cabang=null) {
        if($cabang){
            $this->db->where('id', $cabang);
            $query = $this->db->get('m_cabang');
            return $query->row_array();
        }else{
            return array('nama'=>'SEMUA UNIT KERJA');
        }
        
    }

    function getDanaByID($dana=null) {
        if($dana){
            $this->db->where('id', $dana);
            $query = $this->db->get('m_dana');
            return $query->row_array();
        }else{
            return array('nama'=>'');
        }
        
    }

    function getJenisDana() {

        $this->db->order_by('nama', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_dana');
        return $query->result_array();
    }

    function getLevel() {

        $this->db->order_by('level', 'ASC');
        $query = $this->db->select('distinct(level) as level')->get('m_akun');
        return $query->result_array();
    }

    function getTahun($cabang='') {

        $this->db->order_by('tahun', 'ASC');
        if($cabang){
            $this->db->where('cabang', $cabang);
        }
        $query = $this->db->select('distinct(tahun) as tahun')->get('neraca_saldo');
        if($query->row_array() > 0){
            return $query->result_array();
        }else{
            return array();
        }
    }

    function getTahunAll() {

        $this->db->order_by('tahun', 'ASC');
        $query = $this->db->select('distinct(tahun) as tahun')->get('neraca_saldo');
        return $query->result_array();
    }
	
    function getTipeJurnalAll() {

        $this->db->order_by('kode', 'ASC');
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_tipe_jurnal');
        return $query->result_array();
    }

    function getAkunKas($cabang='') {

        $this->db->order_by('id', 'ASC');
        if($cabang){
            $this->db->where('cabang', $cabang);
        }
        $query = $this->db->select('*')->where("is_deleted", 2)->get('m_akun_kas');
        return $query->result_array();
    }

    function getUserCabang($cabang='') {

        $this->db->order_by('id', 'ASC');
        if($cabang){
            $this->db->where('cabang', $cabang);
        }
        $this->db->where('id >', 1);
        $query = $this->db->select('*')->where("is_active", '1')->get('users');
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