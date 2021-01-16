<?php

namespace App\Http\Controllers;

use Illuminate\Routing\Controller;

class CsvExportController extends Controller
{
    public function export($fields=[],$recordset=[]) {
        // takes 2 params, $fields (array of column names) and $recordset (array of column values)
        $headings=str_replace('_FK','',strtoupper(implode(',',$fields)));
        $csv="{$headings}\n";
        $find=array("\r\n","\n","\r","\t",'"',"&quot;");
        $replace=array(' ',' ',' ',' ',"'","'");
        $unique=array();
        foreach ($recordset as $k=>$item) {
            foreach ($fields as $f) {
                $unique[$k][$f]=$item[$f];
            }
        }
        foreach($unique as $item) {
            foreach($fields as $f) {
                $val = (isUTF8($item[$f]))? $item[$f] : utf8_encode(stripslashes($item[$f]));
                // quotes and tab char forces values to be displayed as text in MS Excel
                $csv .= '"' . str_replace($find,$replace,$val) . "\"\t,";
            }
            $csv = trim(htmlspecialchars_decode($csv),",") . "\n";
        }
        $content = "\xEF\xBB\xBF".$csv;
        return response($content)
            ->header('Content-Type', 'text/cs; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }


}
