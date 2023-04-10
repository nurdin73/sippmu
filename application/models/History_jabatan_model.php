<?php

class History_jabatan_model extends CI_Model
{
    protected $table = 'history_jabatan';
    public function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function get($id)
    {
        $this->db->select("s.id_sdm, s.sdm_nama, j.nama as jabatan, j.id as jabatan_id, hj.periode_id, p.*,  hj.id");
        $this->db->from("$this->table as hj");
        $this->db->where('hj.id', $id);
        $this->db->join("m_sdm as s", "s.id_sdm = hj.sdm_id", "left");
        $this->db->join("m_jabatan as j", "j.id = hj.jabatan_id", "left");
        $this->db->join('m_periodes as p', 'p.id = hj.periode_id', 'left');
        $query = $this->db->get();
        return $query->row_array();
    }


    function all($unit_id = null, $periode = null)
    {
        $this->datatables->select("hj.id, s.sdm_nama as nama, j.nama as jabatan, p.*", false);
        $this->datatables->from("$this->table as hj");
        $this->datatables->join("m_sdm as s", "s.id_sdm = hj.sdm_id", "LEFT");
        $this->datatables->join("m_jabatan as j", "j.id = hj.jabatan_id", "LEFT");
        $this->datatables->join("m_periodes as p", "p.id = hj.periode_id", "left");
        $this->datatables->where('hj.is_deleted', false);
        if ($unit_id) $this->datatables->where('j.id_unit', $unit_id);
        if ($periode) $this->datatables->where('p.id', $periode);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
    }

    public function byPeriode($periodeId = null, $unit_id = null)
    {
        $this->db->select("s.sdm_nama as nama, j.nama as jabatan, p.*, hj.id", false);
        $this->db->from("$this->table as hj");
        $this->db->join("m_periodes as p", "p.id = hj.periode_id", 'left');
        $this->db->join("m_sdm as s", "s.id_sdm = hj.sdm_id", "LEFT");
        $this->db->join("m_jabatan as j", "j.id = hj.jabatan_id", "LEFT");
        if ($periodeId) $this->db->where('periode_id', $periodeId);
        if ($unit_id) $this->db->where('j.id_unit', $unit_id);
        $this->db->where('hj.is_deleted', false);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function destroy($id)
    {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', true);
        $this->db->update($this->table);
    }
}
