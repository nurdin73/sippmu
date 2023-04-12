<?php

class Sdm_model extends CI_Model
{

    protected $table = 'm_sdm';

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
}
