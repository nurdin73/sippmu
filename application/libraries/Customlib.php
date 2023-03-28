<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Customlib {

    var $CI;

    function __construct() {
        $this->CI = & get_instance();
        $this->CI->load->library('session');
        $this->CI->load->library('user_agent');
        $this->CI->load->model('Setting_model', '', TRUE);
    }

    function getCSRF() {
        $csrf_input = "<input type='hidden' ";
        $csrf_input .= "name='" . $this->CI->security->get_csrf_token_name() . "'";
        $csrf_input .= " value='" . $this->CI->security->get_csrf_hash() . "'/>";

        return $csrf_input;
    }

    function numberFormatId($n = '', $d=2)
	{
		return ($n === '') ? '' : number_format( (float) $n, $d, ',', '.');
	}

    function numberFormat($n = '')
	{
		return ($n === '') ? '' : number_format( (float) $n, 2, '.', ',');
	}

    function lastDayOfTheMonth($date = '')
    {
        $month  = date('m', strtotime($date));
        $year   = date('Y', strtotime($date));
        $result = strtotime("{$year}-{$month}-01");
        $result = strtotime('-1 second', strtotime('+1 month', $result));

        return date('Y-m-d', $result);
    }

    function lastDayOfTheMonthYearBefore($date = '')
    {
        $dates   = strtotime($date);
        $result = strtotime('-1 year', $dates);

        return date('Y-m-d', $result);
    }

    function contentAvailabelFor() {
        $content_for = array();
        $role_array = $this->getStaffRole();
        $role = json_decode($role_array);
        $content_for[$role->name] = "All " . $role->name;
        $content_for['student'] = 'Student';
        return $content_for;
    }

    function getCalltype() {
        $call_type = array();
        $call_type['Incoming'] = 'Incoming';
        $call_type['Outgoing'] = 'Outgoing';
        return $call_type;
    }

    function getGender() {
        $gender = array();
        $gender['Male'] = $this->CI->lang->line('male');
        $gender['Female'] = $this->CI->lang->line('female');
        return $gender;
    }

    function getStatus() {
        $status = array();
        $status[""] = $this->CI->lang->line('select');
        $status['enabled'] = 'Enabled';
        $status['disabled'] = 'Disabled';
        return $status;
    }

    function getDateFormat() {
        $dateFormat = array();
        $dateFormat['d-m-Y'] = 'dd-mm-yyyy';
        $dateFormat['d-M-Y'] = 'dd-mmm-yyyy';
        $dateFormat['d/m/Y'] = 'dd/mm/yyyy';
        $dateFormat['d.m.Y'] = 'dd.mm.yyyy';
        $dateFormat['m-d-Y'] = 'mm-dd-yyyy';
        $dateFormat['m/d/Y'] = 'mm/dd/yyyy';
        $dateFormat['m.d.Y'] = 'mm.dd.yyyy';
        return $dateFormat;
    }

    function getCurrency() {
        $currency = array();
        $currency['AED'] = 'AED';
        $currency['AFN'] = 'AFN';
        $currency['ALL'] = 'ALL';
        $currency['AMD'] = 'AMD';
        $currency['ANG'] = 'ANG';
        $currency['AOA'] = 'AOA';
        $currency['ARS'] = 'ARS';
        $currency['AUD'] = 'AUD';
        $currency['AWG'] = 'AWG';
        $currency['AZN'] = 'AZN';
        $currency['BAM'] = 'BAM';
        $currency['BBD'] = 'BAM';
        $currency['BDT'] = 'BDT';
        $currency['BGN'] = 'BGN';
        $currency['BHD'] = 'BHD';
        $currency['BIF'] = 'BIF';
        $currency['BMD'] = 'BMD';
        $currency['BND'] = 'BND';
        $currency['BOB'] = 'BOB';
        $currency['BOV'] = 'BOV';
        $currency['BRL'] = 'BRL';
        $currency['BSD'] = 'BSD';
        $currency['BTN'] = 'BTN';
        $currency['BWP'] = 'BWP';
        $currency['BYN'] = 'BYN';
        $currency['BYR'] = 'BYR';
        $currency['BZD'] = 'BZD';
        $currency['CAD'] = 'CAD';
        $currency['CDF'] = 'CDF';
        $currency['CHE'] = 'CHE';
        $currency['CHF'] = 'CHF';
        $currency['CHW'] = 'CHW';
        $currency['CLF'] = 'CLF';
        $currency['CLP'] = 'CLP';
        $currency['CNY'] = 'CNY';
        $currency['COP'] = 'COP';
        $currency['COU'] = 'COU';
        $currency['CRC'] = 'CRC';
        $currency['CUC'] = 'CUC';
        $currency['CUP'] = 'CUP';
        $currency['CVE'] = 'CVE';
        $currency['CZK'] = 'CZK';
        $currency['DJF'] = 'DJF';
        $currency['DKK'] = 'DKK';
        $currency['DOP'] = 'DOP';
        $currency['DZD'] = 'DZD';
        $currency['EGP'] = 'EGP';
        $currency['ERN'] = 'ERN';
        $currency['ETB'] = 'ETB';
        $currency['EUR'] = 'EUR';
        $currency['FJD'] = 'FJD';
        $currency['FKP'] = 'FKP';
        $currency['GBP'] = 'GBP';
        $currency['GEL'] = 'GEL';
        $currency['GHS'] = 'GHS';
        $currency['GIP'] = 'GIP';
        $currency['GMD'] = 'GMD';
        $currency['GNF'] = 'GNF';
        $currency['GTQ'] = 'GTQ';
        $currency['GYD'] = 'GYD';
        $currency['HKD'] = 'HKD';
        $currency['HNL'] = 'HNL';
        $currency['HRK'] = 'HRK';
        $currency['HTG'] = 'HTG';
        $currency['HUF'] = 'HUF';
        $currency['IDR'] = 'IDR';
        $currency['ILS'] = 'ILS';
        $currency['INR'] = 'INR';
        $currency['IQD'] = 'IQD';
        $currency['IRR'] = 'IRR';
        $currency['ISK'] = 'ISK';
        $currency['JMD'] = 'JMD';
        $currency['JOD'] = 'JOD';
        $currency['JPY'] = 'JPY';
        $currency['KES'] = 'KES';
        $currency['KGS'] = 'KGS';
        $currency['KHR'] = 'KHR';
        $currency['KMF'] = 'KMF';
        $currency['KPW'] = 'KPW';
        $currency['KRW'] = 'KRW';
        $currency['KWD'] = 'KWD';
        $currency['KYD'] = 'KYD';
        $currency['KZT'] = 'KZT';
        $currency['LAK'] = 'LAK';
        $currency['LBP'] = 'LBP';
        $currency['LKR'] = 'LKR';
        $currency['LRD'] = 'LRD';
        $currency['LSL'] = 'LSL';
        $currency['LYD'] = 'LYD';
        $currency['MAD'] = 'MAD';
        $currency['MDL'] = 'MDL';
        $currency['MGA'] = 'MGA';
        $currency['MKD'] = 'MKD';
        $currency['MMK'] = 'MMK';
        $currency['MNT'] = 'MNT';
        $currency['MOP'] = 'MOP';
        $currency['MRO'] = 'MRO';
        $currency['MUR'] = 'MUR';
        $currency['MVR'] = 'MVR';
        $currency['MWK'] = 'MWK';
        $currency['MXN'] = 'MXN';
        $currency['MXV'] = 'MXV';
        $currency['MYR'] = 'MYR';
        $currency['MZN'] = 'MZN';
        $currency['NAD'] = 'NAD';
        $currency['NGN'] = 'NGN';
        $currency['NIO'] = 'NIO';
        $currency['NOK'] = 'NOK';
        $currency['NPR'] = 'NPR';
        $currency['NZD'] = 'NZD';
        $currency['OMR'] = 'OMR';
        $currency['PAB'] = 'PAB';
        $currency['PEN'] = 'PEN';
        $currency['PGK'] = 'PGK';
        $currency['PHP'] = 'PHP';
        $currency['PKR'] = 'PKR';
        $currency['PLN'] = 'PLN';
        $currency['PYG'] = 'PYG';
        $currency['QAR'] = 'QAR';
        $currency['RON'] = 'RON';
        $currency['RSD'] = 'RSD';
        $currency['RUB'] = 'RUB';
        $currency['RWF'] = 'RWF';
        $currency['SAR'] = 'SAR';
        $currency['SBD'] = 'SBD';
        $currency['SCR'] = 'SCR';
        $currency['SDG'] = 'SDG';
        $currency['SEK'] = 'SEK';
        $currency['SGD'] = 'SGD';
        $currency['SHP'] = 'SHP';
        $currency['SLL'] = 'SLL';
        $currency['SOS'] = 'SOS';
        $currency['SRD'] = 'SRD';
        $currency['SSP'] = 'SSP';
        $currency['STD'] = 'STD';
        $currency['SVC'] = 'SVC';
        $currency['SYP'] = 'SYP';
        $currency['SZL'] = 'SZL';
        $currency['THB'] = 'THB';
        $currency['TJS'] = 'TJS';
        $currency['TMT'] = 'TMT';
        $currency['TND'] = 'TND';
        $currency['TOP'] = 'TOP';
        $currency['TRY'] = 'TRY';
        $currency['TTD'] = 'TTD';
        $currency['TWD'] = 'TWD';
        $currency['TZS'] = 'TZS';
        $currency['UAH'] = 'UAH';
        $currency['UGX'] = 'UGX';
        $currency['USD'] = 'USD';
        $currency['USN'] = 'USN';
        $currency['UYI'] = 'UYI';
        $currency['UYU'] = 'UYU';
        $currency['UZS'] = 'UZS';
        $currency['VEF'] = 'VEF';
        $currency['VND'] = 'VND';
        $currency['VUV'] = 'VUV';
        $currency['WST'] = 'WST';
        $currency['XAF'] = 'XAF';
        $currency['XAG'] = 'XAG';
        $currency['XAU'] = 'XAU';
        $currency['XBA'] = 'XBA';
        $currency['XBB'] = 'XBB';
        $currency['XBC'] = 'XBC';
        $currency['XBD'] = 'XBD';
        $currency['XCD'] = 'XCD';
        $currency['XDR'] = 'XDR';
        $currency['XOF'] = 'XOF';
        $currency['XPD'] = 'XPD';
        $currency['XPF'] = 'XPF';
        $currency['XPT'] = 'XPT';
        $currency['XSU'] = 'XSU';
        $currency['XTS'] = 'XTS';
        $currency['XUA'] = 'XUA';
        $currency['XXX'] = 'XXX';
        $currency['YER'] = 'YER';
        $currency['ZAR'] = 'ZAR';
        $currency['ZMW'] = 'ZMW';
        $currency['ZWL'] = 'ZWL';
        return $currency;
    }

    function getRteStatus() {
        $status = array();
        $status['Yes'] = $this->CI->lang->line('yes');
        $status['No'] = $this->CI->lang->line('no');
        return $status;
    }

    function getDaysname() {
        $status = array();
        $status['Monday'] = 'Monday';
        $status['Tuesday'] = 'Tuesday';
        $status['Wednesday'] = 'Wednesday';
        $status['Thursday'] = 'Thursday';
        $status['Friday'] = 'Friday';
        $status['Saturday'] = 'Saturday';
        $status['Sunday'] = 'Sunday';
        return $status;
    }

    function getAppDateFormat() {
        $admin = $this->CI->session->userdata('admin');
        if ($admin) {
            return $admin['date_format'];
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');
            return $student['date_format'];
        }
    }

    function getTimeZone() {
        $admin = $this->CI->session->userdata('admin');
        if ($admin) {
            return $admin['timezone'];
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');
            return $student['timezone'];
        }
    }

    function getCurrencyFormat() {
        $admin = $this->CI->session->userdata('admin');
        if ($admin) {
            return $admin['currency_symbol'];
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');
            return $student['currency_symbol'];
        }
    }

    function getLoggedInUserData() {
        $admin = $this->CI->session->userdata('admin');
        if ($admin) {
            return $admin;
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');
            return $student;
        }
    }

    function getCurrentTheme() {

        $theme = "default";
        $admin = $this->CI->session->userdata('admin');

        if ($admin) {
            if (isset($admin['theme']) && $admin['theme'] != "") {
                $ext = pathinfo($admin['theme'], PATHINFO_EXTENSION);
                $theme = basename($admin['theme'], "." . $ext);
            }
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');


            if (isset($student['theme']) && $student['theme'] != "") {
                $ext = pathinfo($student['theme'], PATHINFO_EXTENSION);
                $theme = basename($student['theme'], "." . $ext);
            }
        }
        return $theme;
    }

    function getRTL() {
        $rtl = "";
        $admin = $this->CI->session->userdata('admin');
        if ($admin) {
            if ($admin['is_rtl'] == "disabled") {
                $rtl = "";
            } else {
                $rtl = "dir='rtl' lang='ar'";
            }
        } else if ($this->CI->session->userdata('student')) {
            $student = $this->CI->session->userdata('student');

            if ($student['is_rtl'] == "disabled") {
                $rtl = "";
            } else {
                $rtl = "dir='rtl' lang='ar'";
            }
        }
        return $rtl;
    }

    function getStaffID() { // users table id of users
        $session_Array = $this->CI->session->userdata('admin');
        $user_id = $session_Array['id'];
        return $user_id;
    }

    function getSessionLanguage() {
        $data_session = $this->CI->session->userdata('admin');
        $language = $data_session['language'];
        $lang_id = $language['lang_id'];
        return $lang_id;
    }

//    function checkPaypalDisplay() {
//        $payment_setting = $this->CI->paymentsetting_model->get();
//        return $payment_setting;
//    }

    function getSessionUsername() {
        $data_session = $this->CI->session->userdata('admin');
        $username = $data_session['username'];

        return $username;
    }

    function getSessionName() {
        $data_session = $this->CI->session->userdata('admin');
        $username = $data_session['name'];

        return $username;
    }

    function getSessionRoles($role) {
        $data_session = $this->CI->session->userdata('admin');
        $roles = $data_session['roles'];

        return $roles[$role];
    }

    function getSessionIsAdmin() {
        $data_session = $this->CI->session->userdata('admin');
        $roles = $data_session['roles'];

        return $roles['is_admin'];
    }

    function getSessionIsSuperadmin() {
        $data_session = $this->CI->session->userdata('admin');
        $roles = $data_session['roles'];

        return $roles['is_superadmin'];
    }

    function getSessionCabang() {
        $data_session = $this->CI->session->userdata('admin');
        $cabang = $data_session['cabang'];

        return $cabang;
    }
    
    function getSessionCabangName() {
        $data_session = $this->CI->session->userdata('admin');
        $cabang = $data_session['cabang_name'];

        return $cabang;
    }

    function getSessionHeadReport() {
        $data_session = $this->CI->session->userdata('admin');
        $head = $data_session['head_report'];

        return $head;
    }

    function getSessionIsPusat() {
        $data_session = $this->CI->session->userdata('admin');
        $cabang = $data_session['is_pusat'];

        return $cabang;
    }

    function getMonthDropdown() {
        $array = array();
        for ($m = 1; $m <= 12; $m++) {
            $month = date('F', mktime(0, 0, 0, $m, 1, date('Y')));
            $array[$month] = $month;
        }
        return $array;
    }

    function getMonthList() {
        $months = array(
            '01' => 'Januari', 
            '02' => 'Februari', 
            '03' => 'Maret', 
            '04' => 'April', 
            '05' => 'Mei', 
            '06' => 'Juni', 
            '07' => 'Juli', 
            '08' => 'Agustus', 
            '09' => 'September', 
            '10' => 'Oktober', 
            '11' => 'November', 
            '12' => 'Desember');
        return $months;
    }

    function getMonthByCode($bln='') {
        $months = array(
            '01' => 'Januari', 
            '02' => 'Februari', 
            '03' => 'Maret', 
            '04' => 'April', 
            '05' => 'Mei', 
            '06' => 'Juni', 
            '07' => 'Juli', 
            '08' => 'Agustus', 
            '09' => 'September', 
            '10' => 'Oktober', 
            '11' => 'November', 
            '12' => 'Desember');
        
        if($bln){
            return $months[$bln];
        }else{
            return false;
        }
    }


    function getStaffRole() {
        $admin = $this->CI->session->userdata('admin');
        $roles = $admin['roles'];
        if ($admin) {
            $role_key = key($roles);
            return json_encode(array('id' => $roles[$role_key], 'name' => $role_key));
        }
    }

    function getAppName() {
        $admin = $this->CI->Setting_model->getSetting();
        return $admin->name;
    }

    function getAppVersion() {
        $appVersion = "2.0";
        return $appVersion;
    }

    function datetostrtotime($date) {
        $format = $this->getAppDateFormat();

        if ($format == 'd-m-Y')
            list($day, $month, $year) = explode('-', $date);
        if ($format == 'd/m/Y')
            list($day, $month, $year) = explode('/', $date);
        if ($format == 'd-M-Y')
            list($day, $month, $year) = explode('-', $date);
        if ($format == 'd.m.Y')
            list($day, $month, $year) = explode('.', $date);
        if ($format == 'm-d-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm/d/Y')
            list($month, $day, $year) = explode('/', $date);
        if ($format == 'm.d.Y')
            list($month, $day, $year) = explode('.', $date);
        $date = $year . "-" . $month . "-" . $day;
        return strtotime($date);
    }

    function dateyyyymmddTodateformat($date) {

        $format = $this->getAppDateFormat();

        if ($format == 'd-m-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd/m/Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd-M-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd.m.Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm-d-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm/d/Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm.d.Y')
            list($month, $day, $year) = explode('-', $date);
        $date = $year . "-" . $day . "-" . $month;


        return strtotime($date);
    }

    function dateFront() {
        $admin = $this->CI->Setting_model->getSetting();
        return $admin->date_format;
    }

    function dateyyyymmddTodateformatFront($date) {
        $format = $this->dateFront();

        if ($format == 'd-m-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd/m/Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd-M-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'd.m.Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm-d-Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm/d/Y')
            list($month, $day, $year) = explode('-', $date);
        if ($format == 'm.d.Y')
            list($month, $day, $year) = explode('-', $date);
        $date = $year . "-" . $day . "-" . $month;


        return strtotime($date);
    }

    function timezone_list() {
        static $timezones = null;

        if ($timezones === null) {
            $timezones = [];
            $offsets = [];
            $now = new DateTime('now', new DateTimeZone('UTC'));

            foreach (DateTimeZone::listIdentifiers() as $timezone) {

                $now->setTimezone(new DateTimeZone($timezone));
                $offsets[] = $offset = $now->getOffset();
                $timezones[$timezone] = '(' . $this->format_GMT_offset($offset) . ') ' . $this->format_timezone_name($timezone);
            }

            array_multisort($offsets, $timezones);
        }
        return $timezones;
    }

    function format_GMT_offset($offset) {
        $hours = intval($offset / 3600);
        $minutes = abs(intval($offset % 3600 / 60));
        return 'GMT' . ($offset ? sprintf('%+03d:%02d', $hours, $minutes) : '');
    }

    public function format_timezone_name($name) {
        $name = str_replace('/', ', ', $name);
        $name = str_replace('_', ' ', $name);
        $name = str_replace('St ', 'St. ', $name);
        return $name;
    }

    function getMailMethod() {
        $mail_method = array();
        $mail_method['sendmail'] = 'SendMail';
        $mail_method['smtp'] = 'SMTP';
        return $mail_method;
    }

    public function setUserLog($username, $role) {
        if ($this->CI->agent->is_browser()) {
            $agent = $this->CI->agent->browser() . ' ' . $this->CI->agent->version();
        } elseif ($this->CI->agent->is_robot()) {
            $agent = $this->CI->agent->robot();
        } elseif ($this->CI->agent->is_mobile()) {
            $agent = $this->CI->agent->mobile();
        } else {
            $agent = 'Unidentified User Agent';
        }

        $data = array(
            'users' => $username,
            'roles' => $role,
            'ipaddress' => $this->CI->input->ip_address(),
            'user_agent' => $agent . ", " . $this->CI->agent->platform(),
        );
        $this->CI->userlog_model->add($data);
    }

    function mediaType() {
        $media_type = array();
        $media_type['image/jpeg'] = "Image";
        $media_type['video'] = "Video";
        $media_type['text/plain'] = "Text";
        $media_type['application/zip'] = "Zip";
        $media_type['application/x-rar'] = "Rar";
        $media_type['application/pdf'] = "Pdf";
        $media_type['application/msword'] = "Word";
        $media_type['application/vnd.ms-excel'] = "Excel";
        $media_type['other'] = "Other";
        return $media_type;
    }

    function getFormString($str, $start, $end) {

        $string = false;
        $pattern = sprintf(
                '/%s(.+?)%s/ims', preg_quote($start, '/'), preg_quote($end, '/')
        );

        if (preg_match($pattern, $str, $matches)) {
            list(, $match) = $matches;
            $string = trim($match);
        }
        return $string;
    }

    function uniqueFileName($prefix = "", $name = "") {
        if (!empty($_FILES)) {
            $newFileName = uniqid($prefix, true) . '.' . strtolower(pathinfo($name, PATHINFO_EXTENSION));
            return $newFileName;
        }
        return false;
    }

    function getUserData() {
        $result = $this->getLoggedInUserData();
        $id = $result["id"];
        $data = $this->CI->user_model->get($id);
        
        return $data;
    }

    function countincompleteTask($id) {

        $result = $this->CI->calendar_model->countincompleteTask($id);
        return $result;
    }

    function getincompleteTask($id) {

        $result = $this->CI->calendar_model->getincompleteTask($id);
        return $result;
    }

    function getClassbyteacher($id) {

        $getUserassignclass = $this->CI->classteacher_model->getclassbyuser($id);
        $classteacherlist = $getUserassignclass;
        $class = array();
        foreach ($classteacherlist as $key => $value) {
            $class[] = $value["id"];
        }

        if (!empty($class)) {

            $getSubjectassignclass = $this->CI->classteacher_model->classbysubjectteacher($id, $class);
            $subjectteacherlist = $getSubjectassignclass;

            $classlist = array_merge($classteacherlist, $subjectteacherlist);

            $i = 0;
            foreach ($classlist as $key => $value) {

                $data[$i]["id"] = $value["id"];
                $data[$i]["class"] = $value["class"];


                $i++;
            }
        } else {
            $getSubjectassignclass = $this->CI->classteacher_model->getsubjectbyteacher($id);



            $data = $getSubjectassignclass;
        }

        return $data;
    }

    public function getclassteacher($id) {

        $getUserassignclass = $this->CI->classteacher_model->getclassbyuser($id);
        $classteacherlist = $getUserassignclass;

        return $classteacherlist;
    }

    public function getteachersubjects($id) {

        $getUserassignclass = $this->CI->classteacher_model->getsubjectbyteacher($id);
        $classteacherlist = $getUserassignclass;

        return $classteacherlist;
    }
    public function getLimitChar($string,$str_length=50) {

        $string = strip_tags($string);
        if (strlen($string) > $str_length) {

            // truncate string
            $stringCut = substr($string, 0, $str_length);
            $endPoint = strrpos($stringCut, ' ');

            //if the string doesn't contain any space then it will cut without word basis.
            $string = $endPoint? substr($stringCut, 0, $endPoint) : substr($stringCut, 0);
            $string .= '...';
        }
        return $string;
    }

}


