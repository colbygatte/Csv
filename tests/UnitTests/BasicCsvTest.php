<?php

namespace Tests\UnitTests;

use ColbyGatte\CsvMan\Csv;
use ColbyGatte\CsvMan\Row;
use ColbyGatte\CsvMan\Writer;
use Tests\TestCase;

class BasicCsvTest extends TestCase
{
    /** @test */
    public function array_returned_from_row_is_in_correct_order()
    {
        $csv = new Csv(['name', 'age']);

        $row = new Row($csv->getHeader());
        $row->age = 26;
        $row->name = 'Colby';

        $this->assertEquals(
            ['Colby', 26],
            $row->toCsvArray()
        );
    }

    /** @test */
    public function can_append_row_using_unkeyed_array_and_will_pad_array_if_enough_values_are_not_passed()
    {
        $csv = new Csv(['name', 'age']);
        $row = $csv->append(['Colby']);

        $this->assertEquals('Colby', $row->name);
        $this->assertEquals('', $row->age);
        $this->assertCount(1, $csv);
    }

    /** @test */
    public function can_write_to_file()
    {
        $csv = new Csv(['name', 'age']);

        $row = new Row($csv->getHeader());
        $row->age = 26;
        $row->name = 'Colby';

        $csv->appendRow($row);

        Writer::write($csv, fopen('/tmp/_csv.csv', 'w'));
    }
}