<?php

class Asset_model extends CI_Model
{
    protected $table = 'm_assets';
    protected $category = 'm_asset_categories';

    public function data($unit_id = null)
    {
        $this->datatables->select('a.id, a.name, c.nama as unit, a.tipe_aset, a.luas_tanah, a.luas_bangunan, a.jml_lokal, a.perolehan, a.pendayagunaan, a.created_at');
        $this->datatables->from("$this->table as a");
        $this->datatables->join("m_cabang as c", "c.id = a.unit_id", "left");
        $this->datatables->where('a.is_deleted', false);
        if ($unit_id) $this->datatables->where('a.unit_id', $unit_id);
        return $this->datatables->generate();
    }

    public function get($id)
    {
        $this->db->select("concat('(',c.kode,') - ', c.nama) as unit, a.*, concat(a.latitude, ', ', a.longitude) as coord, a.unit_id");
        $this->db->from("$this->table as a");
        $this->db->join("m_cabang as c", "c.id = a.unit_id", "left");
        $this->db->where('a.is_deleted', false);
        $this->db->where('a.id', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function getCategories($code)
    {
        $this->db->select('*');
        $this->db->from($this->category);
        $this->db->where('code', $code);
        $query = $this->db->get();
        return $query->result_array();
    }

    public function create($payload)
    {
        $this->db->insert($this->table, $payload);
        return $this->db->insert_id();
    }

    public function update($id, $payload)
    {
        $this->db->where('id', $id);
        $this->db->update($this->table, $payload);
    }

    public function destroy($id)
    {
        $this->db->where('id', $id);
        $this->db->set('is_deleted', true);
        $this->db->update($this->table);
    }

    public function getByUnit($role, $id = null)
    {
        $this->db->from("$this->table as a");
        $this->db->select("a.id, a.unit_id, concat('(',c.kode,') - ', c.nama) as unit, a.longitude, a.latitude, a.alamat, a.luas_tanah, a.luas_bangunan, a.perolehan, a.pendayagunaan, a.tipe_aset, a.is_pusat, (SELECT COUNT(*) FROM $this->table as ta where ta.unit_id = a.unit_id and ta.is_deleted = false) as total_aset");
        // $userData = $this->customlib->getUserData();
        $this->db->join('m_cabang as c', 'c.id = a.unit_id', 'left');
        if ($role != 'super admin') {
            $this->db->where('a.unit_id', $id);
            $this->db->where('a.is_pusat', true);
        } else {
            $this->db->where('a.is_pusat', true);
        }
        if ($id) {
            $this->db->where('a.unit_id', $id);
            $this->db->where('a.is_pusat', false);
        }
        // if (!$id) $this->db->where('a.is_pusat', true);
        $this->db->where('a.is_deleted', false);
        // // if (!$id) $this->db->group_by('a.unit_id, a.id, c.id');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function checkPusatIsExist($unit_id)
    {
        $this->db->where('unit_id', $unit_id);
        $this->db->where('is_pusat', true);
        $this->db->where('is_deleted', false);
        $query = $this->db->get($this->table);
        return $query->num_rows();
    }
}
