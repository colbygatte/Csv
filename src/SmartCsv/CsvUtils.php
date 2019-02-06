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
    public static function write(Csv $csv, $file)
    {
        $writer = new Writer($file);
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
    public static function slurp($file)
    {
        $file = static::file($file);

        $file->open('r');

        $csv = new Csv($file->read());

        while (false !== ($data = $file->read())) {
            $csv->append($data);
        }

        return $csv;
    }

    /**
     * @param string $filePath
     *
     * @return \ColbyGatte\SmartCsv\Iterators\Sip
     */
    public static function sip($file)
    {
        return new Sip($file);
    }

    /**
     * @param          $originalFilePath
     * @param          $alteredFilePath
     * @param callable $callback
     * @param string   $delimiter
     */
    public static function alter($originalFile, $alteredFile, callable $callback)
    {
        $sip = new Sip($originalFile);
        $writer = new Writer($alteredFile);
        $writer->write($sip->getHeader());

        foreach ($sip as $row) {
            if ($callback($row) !== false) {
                $writer->write($row);
            }
        }
    }

    /**
     * Get an instance of File. If $file is already a file, make sure it is closed
     * and return the same instance.
     *
     * @param string|\ColbyGatte\SmartCsv\File
     * @return \ColbyGatte\SmartCsv\File
     */
    public static function file($file)
    {
        if ($file instanceof File) {
            if ($file->isOpen()) {
                $file->close();
            }

            return $file;
        }

        return new File($file);
    }
}
