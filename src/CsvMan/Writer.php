<?php

namespace ColbyGatte\CsvMan;

class Writer
{
    public static function write(Csv $csv, $fileHandle, $delimiter = ',')
    {
        if (! is_resource($fileHandle)) {
            throw new \Exception('$fileHandle must be an open resource');
        }

        fputcsv($fileHandle, $csv->getHeader()->getHeaderValues(), $delimiter);

        foreach ($csv as $row) {
            fputcsv($fileHandle, $row->toCsvArray(), $delimiter);
        }
    }
}