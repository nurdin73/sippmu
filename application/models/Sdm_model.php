<?php

class Sdm_model extends CI_Model
{

    protected $table = 'm_sdm';
    protected $histories = 'history_jabatan';
    protected $klasifikasi = 'm_cabang_klasifikasi';

    public function __construct()
    {
        parent::__construct();
        // $this->db->from($this->table);
    }

    public function getAll()
    {
        $this->datatables->select("a.id, a.kode, concat(u.username, '-', u.name) as name, concat(c.kode, '-', c.nama) as unit_kerja, j.nama as jabatan, a.is_active", false);
        $this->datatables->from('m_sdm as a');
        $this->datatables->join('users as u', 'u.id = a.user_id', 'LEFT');
        $this->datatables->join('m_cabang as c', 'c.id = a.unit_id', 'LEFT');
        $this->datatables->join('m_jabatan as j', 'j.id = a.jabatan_id', 'LEFT');
        $this->datatables->where('a.is_deleted', false);
        $this->datatables->where('a.jabatan_id !=', null);
        // $this->datatables->where('a.status', true);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,name');
        return $this->datatables->generate();
    }

    function all($filter = null)
    {
        if ($filter) $this->db->where("email ilike", "%$filter%");
        // $this->db->where('status', true);
        $this->db->join('users as u', 'u.id = m_sdm.user_id', 'left');
        $this->db->limit(10);
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function create($data)
    {
        $this->db->insert($this->table, $data);
        $sdm_id = $this->db->insert_id();
        return $sdm_id;
    }

    function get($id)
    {
        $this->db->from("$this->table as s");
        $this->db->select("s.id, u.email, c.id as unit_id, concat(c.kode, '-', c.nama) as unit_kerja, j.id as jabatan_id, j.nama as jabatan");
        $this->db->join('users as u', 'u.id = s.user_id', 'LEFT');
        $this->db->join("m_cabang as c", 'c.id = s.unit_id', 'left');
        $this->db->join("m_jabatan as j", 'j.id = s.jabatan_id', 'left');
        $this->db->where('s.id', $id);
        $this->db->where('s.is_deleted', false);
        $query = $this->db->limit(10)->get();
        return $query->row_array();
    }

    public function getByUserId($userId)
    {
        $this->db->from("$this->table as s");
        $this->db->select("s.id, u.email, c.id as unit_id, concat(c.kode, '-', c.nama) as unit_kerja, j.id as jabatan_id, j.nama as jabatan");
        $this->db->join('users as u', 'u.id = s.user_id', 'LEFT');
        $this->db->join("m_cabang as c", 'c.id = s.unit_id', 'left');
        $this->db->join("m_jabatan as j", 'j.id = s.jabatan_id', 'left');
        $this->db->where('s.user_id', $userId);
        $this->db->where('s.is_deleted', false);
        $query = $this->db->get();
        return $query->row_array();
    }

    function sdmByUnit($id)
    {
        $this->datatables->select("a.id, u.email, concat(c.kode, '-', c.nama) as unit_kerja, j.nama as jabatan, a.is_active", false);
        $this->datatables->from('m_sdm as a');
        $this->datatables->join('users as u', 'u.id = a.user_id', 'LEFT');
        $this->datatables->join('m_cabang as c', 'c.id = a.unit_id', 'LEFT');
        $this->datatables->join('m_jabatan as j', 'j.id = a.jabatan_id', 'LEFT');
        $this->datatables->where('a.unit_id', $id);
        $this->datatables->where('a.is_deleted', false);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,email');
        return $this->datatables->generate();
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function destroy($id)
    {
        $this->db->where('id', $id);
        $this->db->delete($this->table);
    }

    public function statistics($type = null, $periodeId = null)
    {
        if ($type == 'keanggotaan') return $this->statisticKeanggotaan($periodeId);
        if ($type == 'pengurus') return $this->statisticPengurus($periodeId);
        if ($type == 'ranting') return $this->statisticPengurusRanting($periodeId);
        return $this->statisticAnggota($periodeId);
    }

    protected function statisticAnggota($periodeId = null)
    {
        $klasifikasi = $this->db->select("ck.id, concat(ck.kode, '-', ck.nama) as label");
        $klasifikasi->from("$this->klasifikasi as ck");
        $klasifikasi->where('ck.is_active', true);
        $klasifikasi->where('ck.deleted_date', null);
        $klas = $klasifikasi->get();
        $classes =  $klas->result_array();
        foreach ($classes as $key => $value) {
            $nonNBM = $this->getUserByKlasifikasi($value['id'], null, $periodeId);
            $nbm = $this->getUserByKlasifikasi($value['id'], 'nbm', $periodeId);
            $classes[$key]['total_nbm'] = intval($nbm);
            $classes[$key]['total_non_nbm'] = intval($nonNBM);
        }
        return $classes;
    }

    function getUserByKlasifikasi($id, $type = 'nbm', $periodeId = null)
    {
        $nbm = $this->db->select("count(h.id) as total");
        $nbm->from("$this->histories as h");
        $nbm->join('users as u', 'u.id = h.user_id', 'left');
        $nbm->join('m_jabatan as j', 'j.id = h.jabatan_id', 'left');
        $nbm->join('m_cabang as c', 'c.id = j.id_unit', 'left');
        $nbm->join('m_cabang_klasifikasi as ck', 'c.klasifikasi = ck.id', 'left');
        if ($periodeId) $nbm->where('periode_id', $periodeId);
        $nbm->where('h.is_deleted', false);
        $nbm->where('c.is_active', true);
        $nbm->where('ck.id', $id);
        if ($type == 'nbm') $nbm->where('u.nbm !=', null);
        if ($type !== 'nbm') $nbm->where('u.nbm', null);
        $nbm->group_by('ck.kode, ck.nama');
        $query = $nbm->get();
        $totalUser = $query->row_array();
        return $totalUser['total'] ?? 0;
    }

    protected function statisticKeanggotaan($periodeId = null)
    {
        // NBM
        $nbm = $this->db->select("count(h.id) as total");
        $nbm->from("$this->histories as h");
        $nbm->join('users as u', 'u.id = h.user_id', 'left');
        if ($periodeId) $nbm->where('periode_id', $periodeId);
        $nbm->where('h.is_deleted', false);
        $nbm->where('u.nbm !=', null);
        $nbm = $nbm->get();
        $data[] = [
            'label' => 'NBM',
            'total' => $nbm->row_array()['total'],
        ];
        // non NBM;
        $nonNBM = $this->db->select("count(h.id) as total");
        $nonNBM->from("$this->histories as h");
        $nonNBM->join('users as u', 'u.id = h.user_id', 'left');
        if ($periodeId) $nonNBM->where('periode_id', $periodeId);
        $nonNBM->where('h.is_deleted', false);
        $nonNBM->where('u.nbm', null);
        $nonNBM = $nonNBM->get();
        $data[] = [
            'label' => 'Non NBM',
            'total' => $nonNBM->row_array()['total'],
        ];
        return $data;
    }

    protected function statisticPengurusRanting($periodeId = null)
    {
        # code...
    }

    protected function statisticPengurus($periodeId = null)
    {
        $klasifikasi = $this->db->select("ck.id, ck.kode as label");
        $klasifikasi->from("$this->klasifikasi as ck");
        $klasifikasi->where('ck.is_active', true);
        $klasifikasi->where('ck.deleted_date', null);
        $klas = $klasifikasi->get();
        $classes =  $klas->result_array();
        foreach ($classes as $key => $value) {
            $this->db->select('c.nama');
            $this->db->from("$this->histories as h");
            $this->db->join('m_jabatan as j', 'j.id = h.jabatan_id', 'left');
            $this->db->join('m_cabang as c', 'c.id = j.id_unit', 'left');
            $this->db->join('m_cabang_klasifikasi as ck', 'c.klasifikasi = ck.id', 'left');
            if ($periodeId) $this->db->where('periode_id', $periodeId);
            $this->db->where('h.is_deleted', false);
            $this->db->where('c.is_active', true);
            $this->db->where('ck.id', $value['id']);
            $this->db->group_by('c.id');
            $query = $this->db->get();
            $totalData = $query->result_array();
            $classes[$key]['total'] = intval(count($totalData) ?? 0);
        }
        return $classes;
    }
}