<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Setting_model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    public function get($id = null) {

        $this->db->select('vsettings.id,vsettings.mobile_api_url,vsettings.app_primary_color_code,vsettings.app_secondary_color_code,vsettings.lang_id,vsettings.is_rtl,vsettings.cron_secret_key, vsettings.timezone,
          vsettings.name,vsettings.email,vsettings.phone,languages.language,vsettings.app_logo,
          vsettings.address,vsettings.dise_code,vsettings.date_format,vsettings.currency,vsettings.currency_symbol,vsettings.fee_due_days,vsettings.image,vsettings.theme'
        );
        $this->db->from('vsettings');
        $this->db->join('languages', 'languages.id = vsettings.lang_id');
        if ($id != null) {
            $this->db->where('vsettings.id', $id);
        } else {
            $this->db->order_by('vsettings.id');
        }
        $query = $this->db->get();

        if ($id != null) {
            return $query->row_array();
        } else {
            $session_array = $this->session->has_userdata('session_array');
            $result = $query->result_array();
            
            return $result;
            
        }
    }

    public function getSettingDetail($id = null) {

        $this->db->select('vsettings.id,vsettings.lang_id,vsettings.is_rtl,vsettings.timezone,
          vsettings.name,vsettings.email,vsettings.phone,languages.language,
          vsettings.address,vsettings.dise_code,vsettings.date_format,vsettings.currency,vsettings.currency_symbol,vsettings.image,vsettings.theme'
        );
        $this->db->from('vsettings');
        $this->db->join('languages', 'languages.id = vsettings.lang_id');
        $this->db->order_by('vsettings.id');
        $query = $this->db->get();
        return $query->row();
    }

    public function getSetting() {

        $this->db->select('vsettings.id,vsettings.lang_id,vsettings.is_rtl,vsettings.fee_due_days,vsettings.cron_secret_key,vsettings.timezone,vsettings.mobile_api_url,
          vsettings.name,vsettings.email,vsettings.phone,languages.language,languages.code as `language_code`,
          vsettings.address,vsettings.app_logo,vsettings.dise_code,vsettings.date_format,vsettings.app_primary_color_code,vsettings.app_secondary_color_code,vsettings.currency,vsettings.currency_symbol,vsettings.image,vsettings.theme'
        );
        $this->db->from('vsettings');
        $this->db->join('languages', 'languages.id = vsettings.lang_id');

        $this->db->order_by('vsettings.id');

        $query = $this->db->get();

        return $query->row();
    }

    public function remove($id) {
        $this->db->where('id', $id);
        $this->db->delete('vsettings');
    }

    public function add($data) {
        if (isset($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('vsettings', $data);
        } else {
            $this->db->insert('vsettings', $data);
            return $this->db->insert_id();
        }
    }

    public function getCurrentAppName() {
        $session_result = $this->get();
        return $session_result[0]['name'];
    }

    public function getCurrentSessiondata() {
        $session_result = $this->get();
        return $session_result[0];
    }

    public function getCurrency() {
        $session_result = $this->get();
        return $session_result[0]['currency'];
    }

    public function getCurrencySymbol() {
        $session_result = $this->get();
        return $session_result[0]['currency_symbol'];
    }

    public function getDateYmd() {
        return date('Y-m-d');
    }

    public function getDateDmy() {
        return date('d-m-Y');
    }

    public function add_cronsecretkey($data, $id) {

        $this->db->where("id", $id)->update("vsettings", $data);
    }

}
