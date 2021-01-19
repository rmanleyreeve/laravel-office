<?php


namespace App\Providers;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Request;
use App\Models\ChangeLog;

class AppUtilsProvider {

    // log changes to database
    public static function chlog($a, $obj = NULL) {
        if (!Session::get('user_id')){ return; }
        $d = ($obj) ? json_encode($obj,JSON_PRETTY_PRINT):NULL;
        ChangeLog::create([
            'user_fk' => Session::get('user_id'),
            'activity' => $a,
            'url' => Request::method() .' '.url()->full(),
            'data' => $d
        ]);
    }

    // pretty print arrays and vars
    public function pp($a) {
        if($a) {
            if(is_array($a)) {
                echo "<hr><pre>", print_r($a,true), "</pre><hr>";
            } else {
                echo "<hr><pre>{$a}</pre><hr>";
            }
        }
    }
    // remove nested directories
    public function recursiveRemoveDirectory($directory){
        foreach(glob("{$directory}/*") as $file){
            if(is_dir($file)) {
                $this->recursiveRemoveDirectory($file);
            } else {
                unlink($file);
            }
        }
        @rmdir($directory);
    }
    // check UTF8 string
    public function isUTF8($str) {
        if(is_array($str)) { return true; }
        return (bool) preg_match('//u', $str);
    }
    // XLS compatible string
    public function xlsVal($v,$find=[],$replace=[]) {
        $val = ($this->isUTF8($v))? $v : utf8_encode(stripslashes($v));
        // quotes and tab char forces values to be displayed as text in MS Excel
        $str = '"' . str_replace($find,$replace,$val) . "\"\t";
        return $val;
    }

    // convert filename to human readable form
    public function hrname($str,$uppercase = false){
        $out = str_replace(array('_fk','_','-'),array('',' ',' '),strtolower($str));
        return ($uppercase) ? strtoupper($out) : ucwords($out);
    }
    // convert string for sorting
    public function sortname($str){
        return str_replace(array(' ','_','-','&'),'',strtolower($str));
    }
    //convert string to filename
    public function filename($str){
        $out = htmlspecialchars_decode($str);
        $out = str_replace(array(' ',':',';','&','/','\\'),'-',strtolower($out));
        return mb_ereg_replace("([-]{2,})", '-', $out);
    }
    // convert h:m:s to h:m
    public function fixDbTime($t) {
        if($t) {
            $a = explode(':',$t);
            return "{$a[0]}:{$a[1]}";
        } else {
            return '';
        }
    }
    // convert db table names to class names
    public function fixDbName($str){
        return str_replace('_','-',strtolower($str));
    }
    // create teaser text, number of words from string
    public function teaser($txt,$num=50) {
        $txt = strip_tags(stripslashes($txt),"");
        $words = explode(" ",$txt);
        if(count($words)>$num) {
            $teaser = array_slice($words,0,$num);
            $txt = implode(" ",$teaser) . "...";
        }
        return $txt;
    }
    // create teaser text, number of chars from string
    public function teaserChars($txt,$num=100) {
        $txt = strip_tags(stripslashes($txt),"");
        return (strlen($txt)>$num) ? substr($txt,0,$num) . "..." : $txt;
    }
    // add leading zero
    public function lz($n){
        return ($n<10) ? "0{$n}" : $n;
    }
    // create unique sorted array
    public function unique($array) {
        $unique = array_intersect_key( $array , array_unique( array_map('serialize' , $array ) ) );
        $vals = [];
        foreach ($unique as $key => $row){
            $vals[$key] = $row['val'];
        }
        array_multisort($vals, SORT_ASC, $unique);
        return $unique;
    }
    // recursive multi array unique
    public function super_unique($array) {
        $result = array_map("unserialize", array_unique(array_map("serialize", $array)));
        foreach ($result as $key => $value){
            if ( is_array($value) ){
                $result[$key] = $this->super_unique($value);
            }
        }
        return $result;
    }
    // multidimensional in_array public function
    public function in_array_r($needle, $haystack, $strict = false) {
        foreach ($haystack as $item) {
            if (($strict ? $item === $needle : $item == $needle) || (is_array($item) && $this->in_array_r($needle, $item, $strict))) {
                return true;
            }
        }
        return false;
    }
    public function diff($a,$b) {
        return array_keys(array_diff_assoc($a,$b));
    }
    // extract POST
    public function postvars() {
        foreach($_POST as $k=>$v) {
            $$k = (!get_magic_quotes_gpc())?addslashes($v):$v;
        }
    }
    //extract GET
    public function getvars() {
        foreach($_GET as $k=>$v) {
            $$k = (!get_magic_quotes_gpc())?addslashes($v):$v;
        }
    }
    // date formatting
    public function dateUkToSql($ukdate){
        return implode('-',array_reverse(explode('/',$ukdate)));
    }
    // pretty file sizes
    public function formatBytes($size, $precision=2){
        $base = log($size, 1024);
        $suffixes = array('', 'K', 'M', 'G', 'T');
        return round(pow(1024, $base - floor($base)), $precision) .' '. $suffixes[floor($base)];
    }
    // form selectors ==================
    public function radio($a,$b) {
        if(is_array($a)) {
            echo (in_array($b,$a)) ? ' checked="checked"':'';
        } else {
            echo ($a==$b) ? ' checked="checked"':'';
        }
    }
    public function select($a,$b) {
        echo ($a==$b) ? ' selected="selected"':'';
    }
    public function checkbox($a,$b) {
        if(is_array($b)) {
            echo (in_array($a,$b)) ? ' checked="checked"':'';
        } else {
            echo ($a==$b) ? ' checked="checked"':'';
        }
    }
    public function selectmulti($a,$b) {
        if(is_array($b)) {
            echo (in_array($a,$b)) ? ' selected="selected"':'';
        } else {
            echo ($a==$b) ? ' selected="selected"':'';
        }
    }

    // text formatting public functions ==================
    public function nf($num) {
        return(is_numeric($num)) ? number_format($num,2) : $num;
    }
    public function _gbp($num){
        return (is_numeric($num)) ? '£' . number_format($num,2) : $num;
    }
    public function _br($str,$prefix = false){
        if($prefix) {
            return (trim($str) != '') ? '<br />'.$str:'';
        } else {
            return (trim($str) != '') ? $str.'<br />':'';
        }
    }
    public function _sp($str,$prefix = false){
        if($prefix) {
            return (trim($str) != '') ? '&nbsp;'.$str:'';
        } else {
            return (trim($str) != '') ? $str.'&nbsp;':'';
        }
    }
    public function _cm($str,$prefix = false){
        if($prefix) {
            return (trim($str) != '') ? ', '.$str:'';
        } else {
            return (trim($str) != '') ? $str.', ':'';
        }
    }
    public function _nl($str,$prefix = false){
        if($prefix) {
            return (trim($str) != '') ? "\n".$str:'';
        } else {
            return (trim($str) != '') ? $str."\n":'';
        }
    }
    public function _par($str){
        return (trim($str) != '') ? "({$str})":'';
    }
    public function yn($v,$full = FALSE) {
        $t = ($full)?'Yes':'Y';
        $f = ($full)?'No':'N';
        return ($v) ? $t : $f;
    }
    public function _d($d,$today = false) {
        if(!$d || empty($d) || $d==NULL || $d=='0000-00-00' || $d=='01/01/1970' || $d=='' || $d=='30/11/-0001') {
            return ($today) ? date('Y-m-d') : '';
        }
        return $d;
    }
    public function ordinal($number) {
        $ends = array('th','st','nd','rd','th','th','th','th','th','th');
        if ((($number % 100) >= 11) && (($number%100) <= 13)) {
            return $number. 'th';
        } else {
            return $number. $ends[$number % 10];
        }
    }
    public function startsWith($haystack, $needle){
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }
    public function endsWith($haystack, $needle){
        $length = strlen($needle);
        return ($length == 0) ? true : substr($haystack, -$length) === $needle;
    }
    public function trimLast($str) {
        return substr($str,0,strlen($str)-1);
    }
    public function checkUrl($str) {
        if($str=='') { return $str; }
        if($this->startsWith($str,'www')) { return 'http://'.$str; }
        if(!$this->startsWith($str,'http')) { return 'http://'.$str; }
        return $str;
    }
    public function hl($str){
        if(isset($_GET['s']) && stripos($str,$_GET['s'])!==false) {
            $needle = trim($_GET['s']);
            $str = preg_replace("/($needle)/i", '<span class="highlight">$1</span>', $str);
        }
        return $str;
    }

    // return client IP
    public function getIP(){
        $ip = NULL;
        if (!empty($_SERVER['HTTP_CLIENT_IP']))  {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }
    // HEX - RGB conversions
    public function hex2rgb($hex, $toArray = false) {
        $hex = str_replace("#", "", $hex);
        if(strlen($hex) == 3) {
            $r = hexdec(substr($hex,0,1).substr($hex,0,1));
            $g = hexdec(substr($hex,1,1).substr($hex,1,1));
            $b = hexdec(substr($hex,2,1).substr($hex,2,1));
        } else {
            $r = hexdec(substr($hex,0,2));
            $g = hexdec(substr($hex,2,2));
            $b = hexdec(substr($hex,4,2));
        }
        $rgb = array($r, $g, $b); // array with the rgb values
        return ($toArray) ? $rgb : implode(",", $rgb);
    }
    // work out contrast for backgrounds
    public function getContrast($hexcolor){
        return (hexdec($hexcolor) > 0xffffff/2) ? 'black':'white';
    }
    // JSON formatting ======================
    public function json_decode_pdf($json, $assoc = TRUE){
        $json = str_replace("\r\n","\n",$json);
        $json = str_replace("\r","\n",$json);
        $json = str_replace("\n","\\n",$json);
        $json = preg_replace('/([{,]+)(\s*)([^"]+?)\s*:/','$1"$3":',$json);
        $json = preg_replace('/(,)\s*}$/','}',$json);
        return json_decode($json,$assoc);
    }
    public function json_decode_html($json, $assoc = TRUE){
        $json = str_replace("\n","<br>",$json);
        $json = str_replace("\r","",$json);
        //$json = str_replace(" ","&nbsp;",$json);
        return json_decode($json,$assoc);
    }
    public function json_fix($txt){
        $txt = str_replace("\r\n","\n",$txt);
        $txt = str_replace("\n",'<br>',$txt);
        $txt = str_replace("\t",' ',$txt);
        $txt = str_replace(array('{','}','"'),'',$txt);
        $txt = stripslashes($txt);
        return ($txt);
    }

    // builds array from DB ENUM values, ensuring non-zero indexing
    public static function enumSelect($tbl,$col){
        $instance = new static; // create an instance of the model to be able to get the table name
        $type = DB::select( DB::raw("SHOW COLUMNS FROM {$tbl} WHERE Field = '{$col}';"))[0]->Type;
        preg_match('/^enum\((.*)\)$/', $type, $matches);
        $enum = array();
        foreach(explode(',', $matches[1]) as $value){
            $v = trim( $value, "'" );
            $enum[] = $v;
        }
        return $enum;
    }
    // duration in days
    public function getDays($sStartDate, $sEndDate){
        // This public function works best with YYYY-MM-DD but other date formats will work thanks to strtotime().
        $aDays[] = $sStartDate;
        // Set a 'temp' variable, sCurrentDate, with the start date - before beginning the loop
        $sCurrentDate = $sStartDate;
        // While the current date is less than the end date
        while($sCurrentDate < $sEndDate){
            // Add a day to the current date
            $sCurrentDate = date("Y-m-d", strtotime("+1 day", strtotime($sCurrentDate)));
            // Add this new day to the aDays array
            $aDays[] = $sCurrentDate;
        }
        // Once the loop has finished, return the array of days.
        return $aDays;
    }
    public function telLink($tel) {
        $tel = str_replace(array(' ','-','.',')','('),'',$tel);
        if(substr($tel,0,1) == '+') {
            return "tel:$tel";
        }
        elseif(substr($tel,0,1) == '0') {
            $tel = '+44' . substr($tel,1);
            return "tel:$tel";
        }
        return 'javascript://';
    }

}
