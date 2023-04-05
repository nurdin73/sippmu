<?php

class Sdm_model extends CI_Model 
{

    protected $table = 'm_sdm';

    public function __construct() {
        parent::__construct();
        // $this->db->from($this->table);
    }

    public function getAll()
    {
        $this->datatables->select("a.id_sdm as id, a.sdm_nbm as nbm, a.sdm_nama as nama, j.nama as jabatan, c.nama as unit_kerja, a.sdm_phone as telepon, a.sdm_hp as hp, a.sdm_status_ttd as ttd", false);
        $this->datatables->from('m_sdm as a');
        $this->datatables->join('m_cabang as c', 'c.id = a.uk_id', 'LEFT');
        $this->datatables->join('m_jabatan as j', 'j.id = a.sdm_jabatan', 'LEFT');
        // $this->datatables->where('a.status', true);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
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
        $this->db->select("s.*, c.id as unit_id, concat(c.kode, '-', c.nama) as unit_kerja, j.id as jabatan_id, j.nama as jabatan");
        $this->db->where('id_sdm', $id);
        $this->db->join("m_cabang as c", 'c.id = s.uk_id', 'left');
        $this->db->join("m_jabatan as j", 'j.id = s.sdm_jabatan', 'left');
        $query = $this->db->limit(10)->get();
        return $query->row_array();
    }

    public function update($id, $data)
    {
        $this->db->where('id_sdm', $id);
        $this->db->update($this->table, $data);
    }

    public function destroy($id)
    {
        $this->db->where('id_sdm', $id);
        $this->db->delete($this->table);
    }
}