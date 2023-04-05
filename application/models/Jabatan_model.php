<?php

class Jabatan_model extends CI_Model
{
    protected $table = 'm_jabatan';

    function getAll()
    {
        $this->datatables->select("j.id, j.nama, c.nama as unit_kerja, j.is_active", false);
        $this->datatables->from('m_jabatan as j');
        $this->datatables->join('m_cabang as c', 'c.id = j.id_unit', 'LEFT');
        $this->datatables->where('j.is_deleted', false);
        $this->datatables->add_column('view', '<a data-id="$1" class="btn btn-default btn-xs btn-edit" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs btn-delete" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
    }

    function get($id)
    {
        $this->db->select("m_jabatan.id, m_jabatan.nama, concat(c.kode, '-', c.nama) as unit_kerja, c.id as unit_id, m_jabatan.is_active");
        $this->db->where('m_jabatan.id', $id);
        $this->db->join('m_cabang as c', "c.id = m_jabatan.id_unit", "left");
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function getData($unit = null, $search = null)
    {
        if($unit) {
            $this->db->where('id_unit', $unit);
        }
        if($search) {
            $this->db->where('nama ilike', "%$search%");
        }
        $this->db->where('is_deleted', false);
        $this->db->where('is_active', true);
        $query = $this->db->limit(10)->get($this->table);
        return $query->result_array();
    }

    public function create($data)
    {
        $this->db->insert($this->table, $data);
        $jabatan_id = $this->db->insert_id();
        return $jabatan_id;
    }

    public function update($id, $data)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $data);
    }

    public function destroy($id)
    {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', true);
        $this->db->update($this->table);
    }

}