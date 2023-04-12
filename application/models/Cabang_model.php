<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/**
 * 
 */
class Cabang_model extends CI_model
{

    public function valid_cabang($str)
    {
        $kode = $this->input->post('kode');
        $id = $this->input->post('idx');
        if (!isset($id)) {
            $id = 0;
        }
        if ($this->check_cabang_exists($kode, $id)) {
            $this->form_validation->set_message('check_exists', 'Record ' . $kode . ' already exists');
            return FALSE;
        } else {
            return TRUE;
        }
    }

    function check_cabang_exists($kode, $id)
    {

        if ($id != 0) {
            $data = array('id != ' => $id, 'kode' => $kode, 'is_deleted' => 2);
            $query = $this->db->where($data)->get('m_cabang');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        } else {

            $this->db->where('is_deleted', 2);
            $this->db->where('kode', $kode);
            $query = $this->db->get('m_cabang');
            if ($query->num_rows() > 0) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }

    function getall()
    {
        $this->datatables->select("a.id as id, b.nama as nama_klasifikasi, c.kode as parent_kode, a.kode, a.nama, a.alamat, a.is_active", false);
        $this->datatables->from('m_cabang as a');
        $this->datatables->join('m_cabang_klasifikasi as b', 'b.id = a.klasifikasi');
        $this->datatables->join('m_cabang as c', 'c.id = a.parent', 'LEFT');
        $this->datatables->where('a.is_deleted', 2);
        $this->datatables->add_column('view', '<a onclick="get(this)" data-id="$1" class="btn btn-default btn-xs" data-target="#editmyModal" data-toggle="tooltip" title="" data-original-title=' . $this->lang->line('edit') . '> 
                                                    <i class="fa fa-pencil"></i></a>
                                                <a  class="btn btn-default btn-xs" onclick="deleterecord(this)" data-id="$1" data-nama="$2" data-toggle="tooltip" title=""  data-original-title=' . $this->lang->line('delete') . '>
                                                        <i class="fa fa-trash"></i></a>', 'id,nama');
        return $this->datatables->generate();
    }

    function getData($id = null, $filter = null)
    {

        $this->db->select("mc.id, mc.nama, mc.kode, mc.parent, case when exists(select 1 from m_cabang mcc where mcc.parent = mc.id) then true else false end as have_child");
        $this->db->from("m_cabang as mc");
        if (isset($id)) {

            $query = $this->db->where("mc.id", $id)->get('m_cabang');
            return $query->row_array();
        } else {
            if ($filter) {
                // $this->db->like('nama', $filter);
                $this->db->where("nama ilike", "%$filter%");
            }
            $query = $this->db->get();
            return $query->result_array();
        }
    }

    function parent($parent = null)
    {
        $this->db->select("mc.id, mc.nama, mc.kode, mc.parent, case when exists(select 1 from m_cabang mcc where mcc.parent = mc.id) then true else false end as have_child");
        // $this->db->select('c.*, CASE WHEN select * FROM m_cabang as cc WHERE cc.parent = c.id LIMIT 1 THEN true ELSE false END as have_child');
        $this->db->from('m_cabang as mc');
        if ($parent) {
            $this->db->where('mc.parent', $parent);
        } else {
            $this->db->where('mc.parent', 0);
        }

        $query = $this->db->get();
        return $query->result_array();
    }

    public function addData($data)
    {

        $this->db->insert('m_cabang', $data);
        $cabang_id = $this->db->insert_id();

        //setup_periode_laporan
        $data_setup = array();
        $data_setup['cabang'] = $cabang_id;
        $data_setup['created_by'] = $data['created_by'];
        $this->db->insert('setup_periode_laporan', $data_setup);

        return $cabang_id;
    }

    public function editData($data, $id)
    {

        if (isset($id)) {
            $this->db->where('id', $id);
            $this->db->update('m_cabang', $data);
        }
        return $id;
    }

    function deleteData($id)
    {
        //$this->db->where("id", $id)->delete("m_cabang");
        if (isset($id)) {
            $userinput = $this->customlib->getSessionUsername();
            $data = array(
                'is_deleted' => 1,
                'deleted_by' => $userinput,
                'deleted_date' => date('Y-m-d H:i:s')
            );

            $this->db->where('id', $id);
            $this->db->update('m_cabang', $data);
        }
        return $id;
    }


    function getCabangParent()
    {

        $query = $this->db->select('*')
            ->where("is_active", "t")
            //->where("parent", 0)
            ->get('m_cabang');
        return $query->result_array();
    }

    function getCabangklasifikasi()
    {

        $this->db->order_by('id', 'ASC');
        $query = $this->db->select('*')->where("is_active", "yes")->get('m_cabang_klasifikasi');
        return $query->result_array();
    }

    function getCabang()
    {

        $this->db->order_by('kode', 'ASC');
        $this->db->where('is_active', 't');
        $this->db->where('is_deleted', 2);
        $query = $this->db->select('*')->get('m_cabang');
        return $query->result_array();
    }

    function getKodeCabang($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->select('*')->get('m_cabang');
        $data = $query->row_array();
        return $data['kode'];
    }
}
