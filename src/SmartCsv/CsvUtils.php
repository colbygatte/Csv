<?php

namespace ColbyGatte\SmartCsv;

use ColbyGatte\SmartCsv\Iterators\Sip;

/**
 * Class CsvUtils
 *
 * @package ColbyGatte\SmartCsv
 */
class CsvUtils
{
    /**
     * @param \ColbyGatte\SmartCsv\Csv $csv
     * @param                          $filePath
     * @param string                   $delimiter
     */
    public static function write(Csv $csv, $filePath, $delimiter = ',')
    {
        $writer = new Writer($filePath, $delimiter);
        $writer->write($csv->getHeader());
        foreach ($csv as $row) {
            $writer->write($row);
        }
    }

    /**
     * @param $filePath
     *
     * @return \ColbyGatte\SmartCsv\Csv
     */
    public static function slurp($filePath)
    {
        $fileHandle = fopen($filePath, 'r');

        $csv = new Csv(fgetcsv($fileHandle));

        while (false !== ($data = fgetcsv($fileHandle))) {
            $csv->append($data);
        }

        return $csv;
    }

    /**
     * @param          $originalFilePath
     * @param          $alteredFilePath
     * @param callable $callback
     * @param string   $delimiter
     */
    public static function alter($originalFilePath, $alteredFilePath, callable $callback, $delimiter = ',')
    {
        $sip = new Sip($originalFilePath);
        $writer = new Writer($alteredFilePath, $delimiter);
        $writer->write($sip->getHeader());

        foreach ($sip as $row) {
            if ($callback($row) !== false) {
                $writer->write($row);
            }
        }
    }
}