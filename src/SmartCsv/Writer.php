<?php

namespace ColbyGatte\SmartCsv;

use Exception;

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
        $this->file = Utils::file($file);

        $this->file->openWrite();
    }

    /**
     * @param \ColbyGatte\SmartCsv\Header|\ColbyGatte\SmartCsv\Row|array $row
     */
    public function write($row)
    {
        if ($row instanceof Header) {
            $row = $row->getValues();
        } elseif ($row instanceof Row) {
            $row = $row->toArray();
        } elseif (! is_array($row)) {
            throw new Exception('Invalid row.');
        }

        $this->file->write($row);
    }
}
