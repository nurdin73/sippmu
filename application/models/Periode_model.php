<?php

class Periode_model extends CI_Model
{
    protected $table = 'm_periodes';

    function table()
    {
        $this->datatables->select("t.id, t.title, t.start, t.end", false);
        $this->datatables->from($this->table . " as t");
        $this->datatables->where('is_deleted', false);
        $this->datatables->add_column('view', '<a data-id="$1" class="btn btn-default btn-xs btn-edit" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs btn-delete" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,title');
        return $this->datatables->generate();
    }

    function all($filter = null, $limit = null)
    {
        if ($filter) $this->db->where("title ilike", "%$filter%");
        $this->db->where('is_deleted', false);
        if ($limit) $this->db->limit($limit);
        $this->db->order_by('start', 'asc');
        $query = $this->db->get($this->table);
        return $query->result_array();
    }

    function get($id)
    {
        $this->db->where("id", $id);
        $this->db->where('is_deleted', false);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function findPeriodeByDate($date)
    {
        $this->db->where("start <=", $date);
        $this->db->where('end >=', $date);
        $this->db->where('is_deleted', false);
        $query = $this->db->get($this->table);
        return $query->row_array();
    }

    function create($data)
    {
        $this->db->insert($this->table, $data);
        return $this->db->insert_id();
    }

    function update($id, $data)
    {
        $this->db->where("id", $id);
        $this->db->update($this->table, $data);
    }

    function delete($id)
    {
        $this->db->where("id", $id);
        $this->db->set('is_deleted', true);
        $this->db->update($this->table);
    }
}
