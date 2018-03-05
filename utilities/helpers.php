<?php

use ColbyGatte\SmartCsv\Csv;
use ColbyGatte\SmartCsv\CsvUtils;
use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Iterators\Sip;

if (! defined('CSV_FUNCTIONS') || ! CSV_FUNCTIONS) {
    return;
}

if (! function_exists('csv_sip')) {
    function csv_sip($file_path)
    {
        return new Sip($file_path);
    }
}

if (! function_exists('csv_alter')) {
    function csv_alter($original_file_path, $altered_file_path, $callback)
    {
        CsvUtils::alter($original_file_path, $altered_file_path, $callback);
    }
}

if (! function_exists('csv_slurp')) {
    function csv_slurp($file_path)
    {
        return CsvUtils::slurp($file_path);
    }
}