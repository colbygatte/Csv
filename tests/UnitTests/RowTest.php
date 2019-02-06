<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Row;
use Tests\TestCase;

class RowTest extends TestCase
{
    protected $row;

    public function setUp()
    {
        parent::setUp();

        $this->row = (new Row(new Header([
            'foo', 'bar', 'baz',
        ])))->set(['foo one', 'bar one', 'baz one']);
    }

    /** @test */
    public function row_to_json()
    {
        $this->assertEquals(
            '{"foo":"foo one","bar":"bar one","baz":"baz one"}',
            $this->row->toJson()
        );
    }

    /** @test */
    public function row_to_string()
    {
        $this->assertEquals(
            "\"foo one\",\"bar one\",\"baz one\"\n",
            (string) $this->row
        );

        $this->assertEquals(
            "\"foo one\",\"bar one\",\"baz one\"\n",
            $this->row->toString()
        );
    }

    /** @test */
    public function row_to_dictionary()
    {
        $this->assertEquals(
            ['foo' => 'foo one', 'bar' => 'bar one', 'baz' => 'baz one'],
            $this->row->toDictionary()
        );
    }

    /** @test */
    public function row_to_array()
    {
        $this->assertEquals(
            ['foo one', 'bar one', 'baz one'],
            $this->row->toArray()
        );
    }
}
