<?php

namespace ColbyGatte\CsvMan;

class Writer
{
    /**
     * @var string
     */
    protected $delimiter;

    protected $fileHandle;

    protected $filePath;

    public function __construct($filePath, $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->fileHandle = fopen($filePath, 'w');
        $this->delimiter = $delimiter;
    }

    public function write($row)
    {
        if ($row instanceof Header) {
            $row = $row->getHeaderValues();
        } elseif ($row instanceof Row) {
            $row = $row->toCsvArray();
        }

        fputcsv($this->fileHandle, $row, $this->delimiter);
    }
}