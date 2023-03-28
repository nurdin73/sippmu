<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Tutup_periode_model extends CI_model {

    
    function getData($cabang = null) {

        if (!empty($cabang)) {
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            return $query->row_array();
        }
    }

    
    public function closingTutupPeriode($cabang, $periode_bln, $periode_thn) {
        
        if (!empty($cabang)) {
            $userinput = $this->customlib->getSessionUsername();
            $periode_date1 = $periode_thn.'-'.$periode_bln.'-01';
            $periode_date2 = $this->customlib->lastDayOfTheMonth($periode_date1);
            
            if($periode_bln == '12'){
                $periode_bln_next = '01';
                $periode_thn_next = $periode_thn + 1;
            }else{
                $periode_bln_next = sprintf("%02d", ($periode_bln + 1));
                $periode_thn_next = $periode_thn;
            }
            
            //cek periode setup_periode_laporan
            $query = $this->db->where("cabang", $cabang)->get('setup_periode_laporan');
            $data_setup = $query->row_array();
            
            if(empty($data_setup['bulan_berjalan'])){
                $result = array('success' => 'false','message'=>'Setup Bulan Berjalan tahun berjalan masih kosong (Setup Periode Laporan) ');
                return $result;die();
            }
            if(empty($data_setup['bln_aktif_saatini'])){
                $result = array('success' => 'false','message'=>'Setup Bulan Aktif Saat Ini masih kosong (Setup Periode Laporan) ');
                return $result;die();
            }
            if(empty($data_setup['bln_aktif_akandatang'])){
                $result = array('success' => 'false','message'=>'Setup Bulan Aktif Akan Datang masih kosong (Setup Periode Laporan) ');
                return $result;die();
            }
            
            $currency = $data_setup['currency'];
            $bulan_berjalan_arr = $data_setup['bulan_berjalan'];
            $bulan_berjalan_exp = explode('/',$bulan_berjalan_arr);
            $bulan_berjalan_bln = trim($bulan_berjalan_exp[0]);
            $bulan_berjalan_thn = trim($bulan_berjalan_exp[1]);
            
            $bln_aktif_saatini_arr = $data_setup['bln_aktif_saatini'];
            $bln_aktif_saatini_exp = explode('/',$bln_aktif_saatini_arr);
            $bln_aktif_saatini_bln = trim($bln_aktif_saatini_exp[0]);
            $bln_aktif_saatini_thn = trim($bln_aktif_saatini_exp[1]);

            $bln_aktif_sebelumnya_arr = $data_setup['bln_aktif_sebelumnya'];
            $bln_aktif_sebelumnya_exp = explode('/',$bln_aktif_sebelumnya_arr);
            $bln_aktif_sebelumnya_bln = trim($bln_aktif_sebelumnya_exp[0]);
            $bln_aktif_sebelumnya_thn = trim($bln_aktif_sebelumnya_exp[1]);

            $bln_aktif_akandatang_arr = $data_setup['bln_aktif_akandatang'];
            $bln_aktif_akandatang_exp = explode('/',$bln_aktif_akandatang_arr);
            $bln_aktif_akandatang_bln = trim($bln_aktif_akandatang_exp[0]);
            $bln_aktif_akandatang_thn = trim($bln_aktif_akandatang_exp[1]);
            
            if($periode_bln != $bulan_berjalan_bln || $periode_thn != $bulan_berjalan_thn){
                $result = array('success' => 'false','message'=>'Periode bulan berjalan dan tahun berjalan tidak sama dengan Setup Periode Laporan '.$bulan_berjalan_bln.'/'.$bulan_berjalan_thn);
            }else 
            if($periode_bln != $bln_aktif_saatini_bln || $periode_thn != $bln_aktif_saatini_thn){
                $result = array('success' => 'false','message'=>'Periode bulan aktif saat ini dan tahun aktif saat ini tidak sama dengan Setup Periode Laporan '.$bln_aktif_saatini_bln.'/'.$bln_aktif_saatini_thn);
            }else 
            if($periode_bln_next != $bln_aktif_akandatang_bln || $periode_thn_next != $bln_aktif_akandatang_thn){
                $result = array('success' => 'false','message'=>'Periode bulan aktif akan datang dan tahun aktif akan datang tidak sama dengan Setup Periode Laporan '.$bln_aktif_akandatang_bln.'/'.$bln_aktif_akandatang_thn);
            }else{
                
            ### cek jurnal yg masih register
                $this->db->where("status", 1);
                $this->db->where("tanggal::date >= '".$periode_date1."' AND tanggal::date <= '".$periode_date2."'", '', false);
                $this->db->where("cabang", $cabang);
                $query_cek = $this->db->get('jurnal_header');
                if ($query_cek->num_rows() > 0) {
                    $result = array('success' => 'false','message'=>'Tidak Dapat Tutup Periode Untuk Bulan '.$periode_bln.' Tahun '.$periode_thn.'. Masih ada jurnal yang belum di posting!');
                    return $result; die();    
                }
                
            ### Sinkronisasi Jurnal dengan Neraca Saldo
                //Data di neraca saldo untuk periode yang sama dengan periode yang ditutup, harus sinkron dengan hasil dari query ini :
                $query_sync = $this->db->query("select b.id, b.cabang,b.akun,c.kel_akun,c.kode,c.nama, b.saldo_bulan_lalu,coalesce(a.debit,0) debet ,coalesce(a.kredit,0) kredit, b.saldo_bulan_lalu + coalesce(a.debit,0) - coalesce(a.kredit,0) saldo  
                                    from neraca_saldo b
                                    left join 
                                    (
                                        select b.akun,sum(b.debet) debit ,sum(b.kredit) kredit 
                                        from jurnal_header a
                                        left join jurnal_detail b on b.jurnal_header = a.id
                                        where a.tanggal::date >= '".$periode_date1."' and a.tanggal <= '".$periode_date2."' and a.is_posting=TRUE and a.cabang=".$cabang."
                                        group by b.akun,b.cabang
                                        order by b.akun
                                    ) a on a.akun = b.akun
                                    left join m_akun c on c.id = b.akun
                                    where b.periode='".$periode_bln."' and tahun='".$periode_thn."' and b.cabang=".$cabang."
                                    order by c.kode");
                $data_sync = $query_sync->result_array();
                //Sinkronisasi 
                if($data_sync){
                    $to_be_update = array();
                    foreach($data_sync as $sync){
                         $data_neraca = array( 
                            'id'                => $sync['id'],
                            //'cabang'          => $cabang,
                            'akun'              => $sync['akun'],
                            'saldo_bulan_lalu'  => $sync['saldo_bulan_lalu'], 
                            'mutasi_debet'      => $sync['debet'],
                            'mutasi_kredit'     => $sync['kredit'],
                            'saldo'             => $sync['saldo'],
                            'posisi'            => ($sync['saldo'] > 0) ? 'D' : 'K',
                            //'periode'           => $periode_bln,
                            //'tahun'             => $periode_thn,
                            'modified_by'       => $userinput,
                            'modified_date'     => date('Y-m-d H:i:s'),
                        );
                        
                        $to_be_update[] = $data_neraca;
                    }
                    
                    if (!empty($to_be_update)) {
                        $this->db->update_batch('neraca_saldo', $to_be_update, 'id');
                    }
                }
                
            ### insert/update perubahan dana
                $dana_arr = array();
                
                //---penerimaan  (--Bulan dan Tahun Tutup Periode dan cabang tutup periode)
				//update dony
                $q_penerimaaan = $this->db->query("select d.cabang,a.id dana,sum(d.mutasi_debet - d.mutasi_kredit) total_penerimaan 
                                from m_dana a
                                join m_dana_trx b on b.dana = a.id
                                join m_dana_trx_det c on c.dana_trx = b.id
                                join neraca_saldo d on d.akun = c.akun_trx
                                where b.tipe=1 and d.periode='".$periode_bln."' and d.tahun=".$periode_thn." and d.cabang=".$cabang." 
								and a.is_deleted= 2 and b.is_deleted=2 and c.is_deleted=2
                                group by a.id,d.cabang");
                $data_penerimaaan = $q_penerimaaan->result_array();
                if($data_penerimaaan){
                    foreach($data_penerimaaan as $row){
                        $dana_arr[$row['cabang']][$row['dana']]['total_penerimaan'] = $row['total_penerimaan'];
                    }
                }
                
                //---penyaluran (--Bulan dan Tahun Tutup Periode dan cabang tutup periode)
				//update dony
                $q_penyaluran = $this->db->query("select a.cabang,a.dana,sum(a.total_penyaluran) total_penyaluran from (
													select d.cabang,a.id dana, case when (d.mutasi_debet - d.mutasi_kredit)< 0 then -1 * (d.mutasi_debet - d.mutasi_kredit) else (d.mutasi_debet - d.mutasi_kredit) end total_penyaluran 
													from m_dana a
													join m_dana_trx b on b.dana = a.id
													join m_dana_trx_det c on c.dana_trx = b.id
													join neraca_saldo d on d.akun = c.akun_trx
													where b.tipe=2 and d.periode='".$periode_bln."' and d.tahun=".$periode_thn." and d.cabang=".$cabang." 
													and a.is_deleted=2 and b.is_deleted=2 and c.is_deleted=2
												) a
												group by a.cabang,a.dana");
								
						
                $data_penyaluran = $q_penyaluran->result_array();
                if($data_penyaluran){
                    foreach($data_penyaluran as $row){
                        $dana_arr[$row['cabang']][$row['dana']]['total_penyaluran'] = $row['total_penyaluran'];
                    }
                }
                
                //---saldo bulan lalu (--Bulan dan Tahun Tutup Periode dan cabang tutup periode)
				//update dony
                $q_saldo_bln_lalu = $this->db->query("select a.cabang,a.dana,sum(a.saldo_bulan_lalu) saldo_bulan_lalu from (
														select coalesce(c.cabang,".$cabang.") cabang,a.id dana,COALESCE(c.saldo_bulan_lalu,0) saldo_bulan_lalu 
														from m_dana a
														join m_dana_det b on b.dana = a.id
														left join neraca_saldo c on c.akun = b.akun_dana and c.periode='".$periode_bln."' and c.tahun=".$periode_thn." and c.cabang=".$cabang." 
														and a.is_deleted=2 and b.is_deleted=2
													 ) a
													 group by a.dana,a.cabang");
													
				
                $data_saldo_bln_lalu = $q_saldo_bln_lalu->result_array();
                
                if($data_saldo_bln_lalu){
                    foreach($data_saldo_bln_lalu as $row){
                        if(empty($dana_arr[$row['cabang']][$row['dana']]['total_penerimaan'])){
                            $dana_arr[$row['cabang']][$row['dana']]['total_penerimaan'] = 0;
                        }
                        if(empty($dana_arr[$row['cabang']][$row['dana']]['total_penyaluran'])){
                            $dana_arr[$row['cabang']][$row['dana']]['total_penyaluran'] = 0;
                        }
                        
                        $saldo_bulan_lalu = $row['saldo_bulan_lalu'];
                        $total_penerimaan = $dana_arr[$row['cabang']][$row['dana']]['total_penerimaan'];
                        $total_penyaluran = $dana_arr[$row['cabang']][$row['dana']]['total_penyaluran'];
                        
                        //insert ke tabel perubahan_dana
                        $data_perubahan_dana = array( 
                            'cabang'        => $row['cabang'],
                            'dana'          => $row['dana'],
                            'currency'      => 'IDR',
                            'nilai_kurs'    => '1',
                            'saldo_bulan_lalu'  => $saldo_bulan_lalu,
                            'penerimaan'    => $total_penerimaan,
                            'penyaluran'    => $total_penyaluran,
							//update dony
                            'saldo'         => $saldo_bulan_lalu + $total_penerimaan + $total_penyaluran,//saldo_bulan_lalu + penerimaan - penyaluran
                            'periode'       => $periode_bln,
                            'tahun'         => $periode_thn,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );
                        $this->db->insert('perubahan_dana', $data_perubahan_dana);
                        $pdana_id = $this->db->insert_id();

                        if($pdana_id){
                            
                            ###insert ke tabel perubahan_dana_det
							//update dony
                             $q_dana_det = $this->db->query("select a.cabang,a.dana_trx,sum (a.jumlah) jumlah from (
																select d.cabang,b.id dana_trx,
																case when b.tipe=2 and (d.mutasi_debet - d.mutasi_kredit) < 0 then -1 * (d.mutasi_debet - d.mutasi_kredit) else (d.mutasi_debet - d.mutasi_kredit) end jumlah 
																from m_dana a
																join m_dana_trx b on b.dana = a.id
																join m_dana_trx_det c on c.dana_trx = b.id
																join neraca_saldo d on d.akun = c.akun_trx
																where d.periode='".$periode_bln."' and d.tahun=".$periode_thn." and d.cabang=".$cabang." and a.id=".$row['dana']."
																and a.is_deleted=2 and b.is_deleted=2 and c.is_deleted=2
															) a
															group by a.cabang,a.dana_trx");
											

                            $data_dana_det = $q_dana_det->result_array();
                            if($data_dana_det){
                                foreach($data_dana_det as $det){

                                    //insert ke tabel perubahan_dana_det
                                    $data_pdana_det = array( 
                                        'cabang'        => $det['cabang'],
                                        'perubahan_dana'=> $pdana_id, 
                                        'dana_trx'      => $det['dana_trx'],
                                        'currency'      => 'IDR',
                                        'nilai_kurs'    => '1',
                                        'jumlah'        => $det['jumlah'],
                                        'created_by'    => $userinput,
                                        'created_date'  => date('Y-m-d H:i:s'),
                                    );
                                    $this->db->insert('perubahan_dana_det', $data_pdana_det);
                                    
                                }
                            }
                        }
                    }
                }
                
                //die();
                
            ### update akun akun dana di tabel neraca saldo, berdasarkan data sbb :
			    //update dony
                $q_dana_det_cek = $this->db->query("select a.cabang,b.akun_dana,b.tipe,sum(a.jumlah) jumlah 
													from perubahan_dana_det a
													join perubahan_dana c on c.id = a.perubahan_dana
													join m_dana_trx b on b.id = a.dana_trx
													where  b.is_deleted=2 and a.cabang=".$cabang." and c.periode='".$periode_bln."' and c.tahun =".$periode_thn."
													group by a.cabang,b.akun_dana,b.tipe");
								
					

                $data_cek_dana = $q_dana_det_cek->result_array();
                
                if($data_cek_dana){
                        $penerimaanx = 0;
                        $pengeluaranx = 0;
                        $selisih = 0;
						$akun_dana = 0;
						
                    foreach($data_cek_dana as $cek){
                        //selisih = per akun dana di hitung selisih antara tipe =1 (peneriman) dengan tipe=2 (pengeluaran)
                        //mutasi_debet		:=	selisih (jika selisih < 0)										
                        //mutasi_kredit		:=	selisih (jika selisih > 0)										
                        //saldo				:=	saldo_bulan_lalu + mutasi_debet - mutasi_kredit	

                        $data_neracax = $this->getDataNeracaPerAkun($cabang, $periode_bln, $periode_thn, $cek['akun_dana']);
                        $saldo_bulan_lalux = $data_neracax['saldo_bulan_lalu'];
                        $mutasi_debetx = $data_neracax['mutasi_debet'];
                        $mutasi_kreditx = $data_neracax['mutasi_kredit'];
                        
	                    //update dony
						if($akun_dana != $cek['akun_dana']){
                            $penerimaanx = 0;
							$pengeluaranx = 0;
							$selisih = 0;
                        }
						
                        if($cek['tipe'] == 1){
                            $penerimaanx = $penerimaanx + $cek['jumlah'];
                        }
                        if($cek['tipe'] == 2){
                            $pengeluaranx = $pengeluaranx + $cek['jumlah'];
                        }
						
						//update dony
                        $selisih = $penerimaanx + $pengeluaranx;
                        
                        $data_saldo = array();
                        if($selisih < 0){
                            $data_saldo['mutasi_debet'] = $selisih;
                            $data_saldo['mutasi_kredit'] = 0;//$mutasi_kreditx;
                        }
                        if($selisih > 0){
                            $data_saldo['mutasi_kredit'] = $selisih;
                            $data_saldo['mutasi_debet'] = 0;//$mutasi_debetx;
                        }
                        

                        $data_saldo['saldo'] = $saldo_bulan_lalux + $data_saldo['mutasi_debet'] - $data_saldo['mutasi_kredit'];
                        
                        //update dony
						$akun_dana = $cek['akun_dana'];
						
						//update dony
						 //cek akun di neraca saldo
						$query3 = $this->db->where(array('akun'=>$cek['akun_dana'], 'cabang'=>$cabang, 'tahun'=>$periode_thn, 'periode'=>$periode_bln))->get("neraca_saldo");
						$dt_saldo = $query3->row_array();
					
					    if($dt_saldo['id']){
							
							//update akun dana di tabel neraca saldo
							$this->db->where('cabang',$cabang);
							$this->db->where('akun',$cek['akun_dana']);
							$this->db->where('periode',$periode_bln);
							$this->db->where('tahun',$periode_thn);
							$this->db->update('neraca_saldo', $data_saldo);
							
						}else{
							
							$data_neraca_saldo = array( 
								'cabang'        => $cabang,
								'akun'          => $cek['akun_dana'],
								'currency'      => 'IDR',
								'nilai_kurs'    => 1,
								'saldo_bulan_lalu'   => 0,
								'mutasi_debet'  => $data_saldo['mutasi_debet'],
								'mutasi_kredit' => $data_saldo['mutasi_kredit'],
								'saldo'         => $data_saldo['saldo'],
								'posisi'        => ($data_saldo['saldo'] > 0) ? 'D' : 'K',
								'periode'       => $periode_bln,
								'tahun'         => $periode_thn,
								'created_by'    => $userinput,
								'created_date'  => date('Y-m-d H:i:s'),
                             );
                             
							 $this->db->insert('neraca_saldo', $data_neraca_saldo);
							
					    }

                        
                    }
                    
                }
                
                
            ###- insert ke neraca saldo dengan periode bulan berikutnya
                //- isi kolom saldo awal bulan lalu dengan saldo akhir dari neraca saldo bulan sebelumnya
                #cek akun di neraca saldo bulan sebelumnya
                $this->db->where('cabang',$cabang);
                $this->db->where('periode',$periode_bln);
                $this->db->where('tahun',$periode_thn);
                $query3 = $this->db->get("neraca_saldo");
                $data_neraca = $query3->result_array();

                //trans_start
                $this->db->trans_start();
                $this->db->trans_strict(FALSE);

                if($data_neraca){

                    $to_be_insert = array();
                    foreach($data_neraca as $dt){

                        $data_neraca_saldo = array( 
                            'cabang'        => $cabang,
                            'akun'          => $dt['akun'],
                            'currency'      => $dt['currency'],
                            'nilai_kurs'    => $dt['nilai_kurs'],
                            'saldo_bulan_lalu'   => $dt['saldo'], //saldo_bulan_lalu = saldo akhir dari neraca saldo bulan sebelumnya
                            'saldo'         => $dt['saldo'],
                            'posisi'        => ($dt['saldo'] > 0) ? 'D' : 'K',
                            'periode'       => $periode_bln_next,
                            'tahun'         => $periode_thn_next,
                            'created_by'    => $userinput,
                            'created_date'  => date('Y-m-d H:i:s'),
                        );

                        $to_be_insert[] = $data_neraca_saldo;
                    }

                    if (!empty($to_be_insert)) {
                        $this->db->insert_batch('neraca_saldo', $to_be_insert);
                    }

                }
                
            ### update tabel setup_periode_laporan
                //bln_aktif_sebelumnya		:=	++ 1 bulan		
                //bln_aktif_saatini			:=	++ 1 bulan		
                //bln_aktif_akandatang		:=	++ 1 bulan		
                //bulan_berjalan			:=	++ 1 bulan		
                
                if($bln_aktif_sebelumnya_bln == '12'){
                    $bln_aktif_sebelumnya_bln_next = '01';
                    $bln_aktif_sebelumnya_thn_next = $bln_aktif_sebelumnya_thn + 1;
                }else{
                    $bln_aktif_sebelumnya_bln_next = sprintf("%02d", ($bln_aktif_sebelumnya_bln + 1));
                    $bln_aktif_sebelumnya_thn_next = $bln_aktif_sebelumnya_thn;
                }
                $bln_aktif_sebelumnya_plus = $bln_aktif_sebelumnya_bln_next.'/'.$bln_aktif_sebelumnya_thn_next;

                if($bln_aktif_saatini_bln == '12'){
                    $bln_aktif_saatini_bln_next = '01';
                    $bln_aktif_saatini_thn_next = $bln_aktif_saatini_thn + 1;
                }else{
                    $bln_aktif_saatini_bln_next = sprintf("%02d", ($bln_aktif_saatini_bln + 1));
                    $bln_aktif_saatini_thn_next = $bln_aktif_saatini_thn;
                }
                $bln_aktif_saatini_plus = $bln_aktif_saatini_bln_next.'/'.$bln_aktif_saatini_thn_next;

                
                if($bln_aktif_akandatang_bln == '12'){
                    $bln_aktif_akandatang_bln_next = '01';
                    $bln_aktif_akandatang_thn_next = $bln_aktif_akandatang_thn + 1;
                }else{
                    $bln_aktif_akandatang_bln_next = sprintf("%02d", ($bln_aktif_akandatang_bln + 1));
                    $bln_aktif_akandatang_thn_next = $bln_aktif_akandatang_thn;
                }
                $bln_aktif_akandatang_plus = $bln_aktif_akandatang_bln_next.'/'.$bln_aktif_akandatang_thn_next;

                
                $data_lap = array(
                    'bln_aktif_sebelumnya'  => $bln_aktif_sebelumnya_plus,
                    'bln_aktif_saatini'     => $bln_aktif_saatini_plus,
                    'bln_aktif_akandatang'  => $bln_aktif_akandatang_plus,
                    'bulan_berjalan'        => $periode_bln_next.'/'.$periode_thn_next
                );
                
                $this->db->where('cabang', $cabang);
                $this->db->update('setup_periode_laporan', $data_lap);
                

                $this->db->trans_complete();

                if ($this->db->trans_status() === FALSE) {

                    $this->db->trans_rollback();
                    $result = array('success' => 'false','message'=>'Tutup Periode gagal!');
                } else {

                    $this->db->trans_commit();
                    $result = array('success' => 'true','message'=>'Tutup Periode Bulan '.$periode_bln.' Tahun '.$periode_thn.' Berhasil..');
                }


                
            }
            
        }else{
            $result = array('success' => 'false','message'=>'Unit Kerja tidak ditemukan!');
        }
        
        return $result;
        die();       
    }

    function getDataNeracaPerAkun($cabang, $periode_bln, $periode_thn, $akun){
        $this->db->where('cabang',$cabang);
        $this->db->where('akun',$akun);
        $this->db->where('periode',$periode_bln);
        $this->db->where('tahun',$periode_thn);
        $query3 = $this->db->get("neraca_saldo");
        $data_neraca = $query3->row_array();

        return $data_neraca;
    }

    
}

?>