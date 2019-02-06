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
     * @var File
     */
    protected $file;

    /**
     * Writer constructor.
     *
     * @param string $filePath
     * @param string $delimiter
     */
    public function __construct($file)
    {
        $this->file = CsvUtils::file($file);

        $this->file->open('w');
    }

    /**
     * @param \ColbyGatte\SmartCsv\Header|\ColbyGatte\SmartCsv\Row|array $row
     */
    public function write($row)
    {
        if ($row instanceof Header) {
            $row = $row->getHeaderValues();
        } elseif ($row instanceof Row) {
            $row = $row->toArray();
        }

        $this->file->write($row);
    }
}
