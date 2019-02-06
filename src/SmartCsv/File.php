<?php

namespace ColbyGatte\SmartCsv;

use Exception;

/**
 * Class File
 *
 * @package ColbyGatte\SmartCsv
 */
class File
{
    /**
     * The file handle.
     *
     * @var resource
     */
    protected $handle;

    /**
     * CSV delimiter character.
     *
     * @var string
     */
    protected $delimiter = ',';

    /**
     * CSV enclosure character.
     *
     * @var string
     */
    protected $enclosure = '"';

    /**
     * CSV escape character.
     *
     * @var string
     */
    protected $escape = '\\';

    /**
     * Length of character chunks for reading CSV.
     * Setting to 0 allows any size line to be read.
     *
     * @var integer
     */
    protected $length = 0;

    /**
     * New File.
     *
     * @param string $file
     * @throws \Exception
     */
    public function __construct($file)
    {
        if (! file_exists($file)) {
            throw new Exception(sprintf('%s does not exists.', $file));
        }

        $this->file = $file;
    }

    /**
     * Check if the file handle is open.
     *
     * @return boolean
     */
    public function isOpen()
    {
        return is_resource($this->handle);
    }

    /**
     * Rewind the file handle.
     *
     * @return void
     */
    public function rewind()
    {
        rewind($this->handle);
    }

    /**
     * Open the file handle.
     *
     * @param string $mode
     * @return void
     * @throws \Exception
     */
    public function open($mode = 'r')
    {
        if (is_resource($this->handle)) {
            throw new Exception('CSV file already opened.');
        }

        if (! $this->handle = fopen($this->file, $mode)) {
            throw new Exception('Error opening file.');
        }
    }

    /**
     * Close the file handle.
     *
     * @return void
     * @throws \Exception
     */
    public function close()
    {
        if (! fclose($this->handle)) {
            throw new Exception('Error closing file.');
        }

        $this->handle = null;
    }

    /**
     * Create a new file handle and replicate the
     * settings from the current instance.
     *
     * @param string $file The new file path.
     * @return \ColbyGatte\SmartCsv\File
     */
    public function init($file)
    {
        return (new static($file))
            ->length($this->length)
            ->delimiter($this->delimiter)
            ->enclosure($this->enclosure)
            ->escape($this->escape);
    }

    /**
     * Write $data to the CSV.
     *
     * @param array $data
     * @return int|false
     * @throws \Exception
     */
    public function write($data)
    {
        if (! is_array($data)) {
            throw new Exception('Must be array.');
        }

        return fputcsv(
            $this->handle,
            $data,
            $this->delimiter,
            $this->enclosure,
            $this->escape
        );
    }

    /**
     * Read line from the CSV.
     *
     * @return array|false|null
     */
    public function read()
    {
        return fgetcsv(
            $this->handle,
            $this->length,
            $this->delimiter,
            $this->enclosure,
            $this->escape
        );
    }

    /**
     * Set the length.
     *
     * @param int $length
     * @return static
     */
    public function length($length)
    {
        $this->length = $length;

        return $this;
    }

    /**
     * Set the delimiter.
     *
     * @param string $delimiter
     * @return static
     */
    public function delimiter($delimiter)
    {
        $this->delimiter = $delimiter;

        return $this;
    }

    /**
     * Set the enclosure.
     *
     * @param string $enclosure
     * @return static
     */
    public function enclosure($enclosure)
    {
        $this->enclosure = $enclosure;

        return $this;
    }

    /**
     * Set the escape.
     *
     * @param string $escape
     * @return static
     */
    public function escape($escape)
    {
        $this->escape = $escape;

        return $this;
    }

    /**
     * Make sure that the file handle is closed.
     *
     * @return void
     */
    public function __destruct()
    {
        if ($this->isOpen()) {
            $this->close();
        }
    }
}
