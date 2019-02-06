<?php

use ColbyGatte\SmartCsv\CsvUtils;
use ColbyGatte\SmartCsv\Iterators\Sip;

if (! function_exists('csv_sip')) {
    function csv_sip($file)
    {
        return new Sip($file);
    }
}

if (! function_exists('csv_alter')) {
    function csv_alter($original, $altered, $callback)
    {
        CsvUtils::alter($original, $altered, $callback);
    }
}

if (! function_exists('csv_slurp')) {
    function csv_slurp($file)
    {
        return CsvUtils::slurp($file);
    }
}
