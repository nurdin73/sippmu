<?php

class Timeline_model extends CI_Model {

    public function add($data) {

        if (isset($data["id"])) {

            $this->db->where("id", $data["id"])->update("student_timeline", $data);
        } else {

            $this->db->insert("student_timeline", $data);
            return $this->db->insert_id();
        }
    }

    public function add_user_timeline($data) {

        if (isset($data["id"])) {

            $this->db->where("id", $data["id"])->update("user_timeline", $data);
        } else {

            $this->db->insert("user_timeline", $data);
            return $this->db->insert_id();
        }
    }

    public function getStudentTimeline($id, $status = '') {

        if (!empty($status)) {

            $this->db->where("status", "yes");
        }
        $query = $this->db->where("student_id", $id)->order_by("timeline_date", "asc")->get("student_timeline");
        return $query->result_array();
    }

    public function getStaffTimeline($id, $status = '') {


        if (!empty($status)) {

            $this->db->where("status", $status);
        }
        $query = $this->db->where("user_id", $id)->order_by("timeline_date", "asc")->get("user_timeline");
        return $query->result_array();
    }

    public function delete_timeline($id) {

        $this->db->where("id", $id)->delete("student_timeline");
    }

    public function delete_user_timeline($id) {

        $this->db->where("id", $id)->delete("user_timeline");
    }

}

?>