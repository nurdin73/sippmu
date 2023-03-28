<?php //defined('SYSPATH') or die('No direct script access.');

class Helper_Date {

    public static function getEffectiveDate($format = "U") {
        //return date($format, time());

        $a = new CI_Controller();
        if ($a->session->userdata("TGL_HARI_INI") == null) {
        return date($format, time());
        } else {
            // get h/m/s
            list($d, $m, $y) = explode("-", date("j-n-Y"), time());

            $tglhariini = $a->session->userdata("TGL_HARI_INI");
            $y2 = substr($tglhariini, 0, 4);
            $m2 = substr($tglhariini, 4, 2);
            $d2 = substr($tglhariini, 6, 2);
            return date($format, mktime(0, 0, 0, $m2, $d2, $y2) + time() - mktime(0, 0, 0, $m, $d, $y));
        }
    }

    // from int timestamp to string
    public static function dateToString($timestamp) {
        $date = new DateTime();
        date_timestamp_set($date, $timestamp);

        $month = "";
        switch (date("m", $timestamp)) {
            case "01" : $month = "Januari";
                break;
            case "02" : $month = "Februari";
                break;
            case "03" : $month = "Maret";
                break;
            case "04" : $month = "April";
                break;
            case "05" : $month = "Mei";
                break;
            case "06" : $month = "Juni";
                break;
            case "07" : $month = "Juli";
                break;
            case "08" : $month = "Agustus";
                break;
            case "09" : $month = "September";
                break;
            case "10" : $month = "Oktober";
                break;
            case "11" : $month = "November";
                break;
            case "12" : $month = "Desember";
                break;
        }
        return date("d", $timestamp) . " " . $month . " " . date("Y", $timestamp);
    }

    // from int timestamp to string
    public static function dateToYMD($timestamp) {
        $date = new DateTime();
        date_timestamp_set($date, $timestamp);

        return date("Y", $timestamp) . date("m", $timestamp) . date("d", $timestamp);
    }

    // from int timestamp to string
    public static function dateToDMY($timestamp) {
        $date = new DateTime();
        date_timestamp_set($date, $timestamp);

        return date("d", $timestamp) ."-". date("m", $timestamp) ."-". date("Y", $timestamp);
    }

    /**
     * get current date
     * @return string
     */
    public static function now($format = "Ymd") {
        if(Leafx2_Session::instance()->get("TGL_HARI_INI") == null){
            return date($format,time());
        }else{
            // get h/m/s
            list($d,$m,$y) = explode("-",date("j-n-Y"),time());

            $tglhariini = Leafx2_Session::instance()->get("TGL_HARI_INI");
            $y2 = substr($tglhariini, 0, 4);
            $m2 = substr($tglhariini, 4, 2);
            $d2 = substr($tglhariini, 6, 2);
            return date($format,mktime(0,0,0,$m2,$d2,$y2) + time() - mktime(0,0,0,$m,$d,$y));
        }
    }

    /**
     * go to specific date from tanggal acuan (Ymd) using strtotime string
     * @return string
     */
    public static function go_to_date($strtotime_string, $tanggal_acuan = null, $format = "Ymd"){
        $tanggal_acuan = $tanggal_acuan != null && $tanggal_acuan != "" ? $tanggal_acuan : Helper_Date::now();
        return date($format, strtotime($strtotime_string, Helper_Date::ymd2ts($tanggal_acuan)));
    }


    /**
     * get tomorrow date
     * @return string
     */
    public static function tomorrow($format = "Ymd") {
        return Helper_Date::go_to_date($format, "+1 day");
    }


    /**
     * format date from ymd to timestamp
     * @return int
     */
    public static function ymd2ts($date) {
        $y = self::get_year($date);
        $m = self::get_month($date);
        $d = self::get_date($date);

        return mktime(0,0,0,$m,$d,$y);
    }

    /**
     * format date from dmy to timestamp
     * @return int
     */
    public static function dmy2ts($date){
        return Helper_Date::ymd2ts(Helper_Date::dmy2ymd($date));
    }

    /**
     * format date from d-m-y to ymd
     * @return int
     */
    public static function dmy2ymd($date, $delimiter = "-"){
        list($tgl, $bln, $thn) = explode($delimiter, $date);
        return $date != "" && $date != "0" ? $thn.$bln.$tgl : "";
    }

    /**
     * format date from d-m-y to ymd
     * @return int
     */
    public static function dmy2ymd_string($date, $delimiter = "-"){
        list($tgl, $bln, $thn) = explode($delimiter, $date);
        return $date != "" && $date != "0" ? $thn.'-'.$bln.'-'.$tgl : "";
    }

    /**
     * format date from ymd to d-m-y
     * @return int
     */
    public static function ymd2dmy($date){
        return $date != "" && $date != "0" ? self::get_date($date)."-".self::get_month($date)."-".self::get_year($date) : "";
    }

    /**
     * format date from d-m-y to ymd
     * @return int
     */
    public static function ymd2dmy_string($date, $delimiter = "-"){
        list($thn, $bln, $tgl) = explode($delimiter, $date);
        return $date != "" && $date != "0" ? $tgl.'-'.$bln.'-'.$thn : "";
    }

    /**
     * format date from d-m-y to ymd delimeter /
     * @return int
     */
    public static function ymd2dmy_slash($date, $delimiter = "-"){
        list($thn, $bln, $tgl) = explode($delimiter, $date);
        return $date != "" && $date != "0" ? $tgl.'/'.$bln.'/'.$thn : "";
    }

    /**
     * format date from y-m-d to ymd
     * @return int
     */
    public static function ymd2dmy_stringlong($date, $delimiter = "-"){
        list($thn, $bln, $tgl) = explode($delimiter, $date);
        return $tgl . " " . self::bulan($bln) . " " . $thn;
        //return $date != "" && $date != "0" ? $tgl.'-'.$bln.'-'.$thn : "";
    }

    /**
     * get year from date ymd
     * @return int
     */
    public static function get_year($date) {
        return substr($date,0,4);
    }

    /**
     * get month from date ymd
     * @return int
     */
    public static function get_month($date) {
        return substr($date,4,2);
    }

    /**
     * get date from date ymd
     * @return int
     */
    public static function get_date($date) {
        return substr($date,6,2);
    }

    /**
     * get the day of the week from a date, for example 20120630 return = 6, since its on Saturday
     * @static
     * @param  $ymd input date
     * @return int Representation number day of week
     */
    public static function get_day_of_week($ymd) {
        return date("w",Helper_Date::ymd2ts($ymd));
    }

    /**
     * get periode from year month to string
     * @static
     * @param  $ym input date
     * @return int Representation number day of week
     */
    public static function ym2string($ym) {
        $year   = substr($ym, 0, 4);
        $month  = substr($ym, 4, 2);
        $ymd    = $ym."01";

        return (date("F", Helper_Date::ymd2ts($ymd))) . " " . $year;
    }

    /**
     * convert ymd to string representation
     * @static
     * @param  $ymd input date
     * @return string Formatted date
     */
    public static function ymd2string($ymd) {
        $year   = substr($ymd, 0, 4);
        $day   = substr($ymd, -2);
        return $day . " ".(date("F", Helper_Date::ymd2ts($ymd))) . " " . $year;
    }

    /**
     * count person's age, given birth date on ymd format
     * @static
     * @param  $ymd input date
     * @return string Formatted date
     */
    public static function count_age($ymd = null){
        if($ymd != null){
            return Helper_Date::now("Y") - Helper_Date::get_year($ymd);
        }else{
            return "";
        }
    }

    /*
     * check if date is valid format, currently support Ymd and d-m-Y
     * @return bool
     */
    public static function isvalid($date, $format = "Ymd"){
        if($date == null || $date == ""){
            return true;
        }else{
            $isvalid = false;

            switch($format){
                case "d-m-Y"    :
                    if(preg_match("/^[0-9]{2}-[0-9]{2}-[0-9]{4}$/",$date)) $isvalid = true;
                    break;
                    default         :
                    if(preg_match("/^[0-9]{4}[0-9]{2}[0-9]{2}$/",$date)) $isvalid = true;
                    break;
            }

            return $isvalid;
        }
    }

    public static function hari($hari) {
        //echo $hari;
        switch ($hari){
            case 0 : $hari="Minggu";
                Break;
            case 1 : $hari="Senin";
                Break;
            case 2 : $hari="Selasa";
                Break;
            case 3 : $hari="Rabu";
                Break;
            case 4 : $hari="Kamis";
                Break;
            case 5 : $hari="Jumat";
                Break;
            case 6 : $hari="Sabtu";
                Break;
        }
        return $hari;
    }

    public static function bulan($bulan) {
        switch ($bulan){
            case '01' : $bulan="Januari";
                Break;
            case '02' : $bulan="Februari";
                Break;
            case '03' : $bulan="Maret";
                Break;
            case '04' : $bulan="April";
                Break;
            case '05' : $bulan="Mei";
                Break;
            case '06' : $bulan="Juni";
                Break;
            case '07' : $bulan="Juli";
                Break;
            case '08' : $bulan="Agustus";
                Break;
            case '09' : $bulan="September";
                Break;
            case '10' : $bulan="Oktober";
                Break;
            case '11' : $bulan="November";
                Break;
            case '12' : $bulan="Desember";
                Break;
        }
        return $bulan;
    }

    public static function hitungUmur($tgllahir) {
        $tgl = explode("-", $tgllahir);
        // memecah $tgllahir yang tadinya YYYY-MM-DD menjadi array
        // $tgl[0] = tahun (YYYY)
        //  $tgl[1] = bulan (MM)
        // $tgl[2] = hari (DD)

        $umur = Helper_Date::getEffectiveDate("Y") - $tgl[0];  //ini untuk ngitung umurnya

        if(($tgl[1] > Helper_Date::getEffectiveDate("m")) || ($tgl[1] == Helper_Date::getEffectiveDate("m") && Helper_Date::getEffectiveDate("d") < $tgl[2])) //ngecek apakah tgl lahir dan bulannya belum lewat?
        {
            $umur -= 1;
        }
        return $umur;
    }

    // from int timestamp to string
    public static function timestamptoymd($timestamp) {
        $date = new DateTime();
        date_timestamp_set($date, $timestamp);

        return date("Y", $timestamp) . '-' . date("m", $timestamp) . '-' . date("d", $timestamp);
    }

}