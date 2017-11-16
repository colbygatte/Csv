<?php

namespace ColbyGatte\SmartCsv;

/**
 * Class Writer
 *
 * @package ColbyGatte\SmartCsv
 */
class Writer
{
    /**
     * @var string
     */
    protected $delimiter;

    /**
     * @var bool|resource
     */
    protected $fileHandle;

    /**
     * @var string
     */
    protected $filePath;

    /**
     * Writer constructor.
     *
     * @param string $filePath
     * @param string $delimiter
     */
    public function __construct($filePath, $delimiter = ',')
    {
        $this->filePath = $filePath;
        $this->fileHandle = fopen($filePath, 'w');
        $this->delimiter = $delimiter;
    }

    /**
     * @param \ColbyGatte\SmartCsv\Header|\ColbyGatte\SmartCsv\Row|array $row
     */
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