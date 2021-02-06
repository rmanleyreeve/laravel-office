<?php

namespace App\Http\Controllers;

use App\Domain\AppUtils as Utils;
use Illuminate\Routing\Controller;

class CsvExportController extends Controller
{
    public function export($fields = [], $recordset = [])
    {
        // takes 2 params, $fields (array of column names) and $recordset (array of column values)
        $headings = str_replace('_FK', '', strtoupper(implode(',', $fields)));
        $csv = "{$headings}\n";
        $find = ["\r\n", "\n", "\r", "\t", '"', "&quot;"];
        $replace = [' ', ' ', ' ', ' ', "'", "'"];
        $unique = [];
        foreach ($recordset as $k => $item) {
            foreach ($fields as $f) {
                $unique[$k][$f] = $item[$f];
            }
        }
        foreach ($unique as $item) {
            foreach ($fields as $f) {
                $val = (Utils::isUTF8($item[$f])) ? $item[$f] : utf8_encode(stripslashes($item[$f]));
                // quotes and tab char forces values to be displayed as text in MS Excel
                $csv .= '"' . str_replace($find, $replace, $val) . "\"\t,";
            }
            $csv = trim(htmlspecialchars_decode($csv), ",") . "\n";
        }
        $content = "\xEF\xBB\xBF" . $csv;
        return response($content)
            ->header('Content-Type', 'text/cs; charset=utf-8')
            ->header('Access-Control-Allow-Origin', '*');
    }

}
