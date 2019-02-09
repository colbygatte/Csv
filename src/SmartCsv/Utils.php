<?php

namespace ColbyGatte\SmartCsv;

use ColbyGatte\SmartCsv\Iterators\Sip;

/**
 * Class Utils
 *
 * @package ColbyGatte\SmartCsv
 */
class Utils
{
    /**
     * @param \ColbyGatte\SmartCsv\Csv $csv
     * @param                          $filePath
     * @param string                   $delimiter
     */
    public static function write(Csv $csv, $file)
    {
        $writer = new Writer($file);

        $writer->write($csv->header());

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

        $file->openRead();

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
     * @param          $original
     * @param          $new
     * @param callable $callback
     * @param string   $delimiter
     */
    public static function alter($original, $new, callable $callback)
    {
        $sip = new Sip($original);
        $writer = new Writer($new);
        $writer->write($sip->header());

        foreach ($sip as $row) {
            false !== $callback($row) ? $writer->write($row) : null;
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
