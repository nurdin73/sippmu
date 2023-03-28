<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
define('THEMES_DIR', 'themes');
define('BASE_URI', str_replace('index.php', '', $_SERVER['SCRIPT_NAME']));

class MY_Controller extends CI_Controller
{

    protected $langs = array();

    public function __construct()
    {

        parent::__construct();
        $this->load->library('auth');
        $this->load->library('module_lib');
      
        $this->load->helper('directory');
        $this->load->model('setting_model');
        if ($this->session->has_userdata('admin')) {
            $admin    = $this->session->userdata('admin');
            $language = ($admin['language']['language']);
        } else if ($this->session->has_userdata('student')) {
            $student  = $this->session->userdata('student');
            $language = ($student['language']['language']);
        } else {
            $language = "English";
        }

        $lang_array = array('form_validation_lang');
        $map        = directory_map(APPPATH . "./language/" . $language . "/app_files");
        foreach ($map as $lang_key => $lang_value) {
            $lang_array[] = 'app_files/' . str_replace(".php", "", $lang_value);
        }

        $this->load->language($lang_array, $language);
    }

}

class Admin_Controller extends MY_Controller
{
    protected $aaaa = false;
    public function __construct()
    {
        parent::__construct();
        $this->auth->is_logged_in();
        $this->load->library('rbac');
    }


}


class Public_Controller extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

}

class Front_Controller extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

    }



}