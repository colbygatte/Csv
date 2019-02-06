<?php

namespace Tests\UnitTests;

use ColbyGatte\SmartCsv\Csv;
use ColbyGatte\SmartCsv\Header;
use ColbyGatte\SmartCsv\Row;
use ColbyGatte\SmartCsv\CsvUtils;
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
            $row->toArray()
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

        CsvUtils::write($csv, '/tmp/_csv.csv');

        $this->assertEquals(
            "name,age\n" .
            "Colby,26\n",
            file_get_contents('/tmp/_csv.csv')
        );
    }

    /** @test */
    public function using_mass_set_data_on_row_does_not_override_all_data()
    {
        $header = new Header(['name', 'age', 'occupation']);
        $row = new Row($header);

        $row->name = 'Colby';
        $row->setKeyed([
            'age' => 26,
            'occupation' => 'developer'
        ]);

        $this->assertEquals(
            ['Colby', 26, 'developer'],
            $row->toArray()
        );
    }

    /** @test */
    public function can_map_rows()
    {
        $csv = new Csv(['name', 'age']);
        $csv->append(['Colby', 26]);
        $csv->append(['Evan', 22]);

        $ages = $csv->pluckColumn('age');

        $this->assertEquals([26, 22], $ages);
    }

    /** @test */
    public function can_pluck()
    {
        $csv = new Csv(['name', 'age', 'occupation']);
        $csv->append(['Colby', 26, 'developer']);
        $csv->append(['Evan', 22, 'scaffold builder']);

        $data = $csv->pluckColumns(['name', 'occupation']);

        $this->assertEquals([
            ['name' => 'Colby', 'occupation' => 'developer'],
            ['name' => 'Evan', 'occupation' => 'scaffold builder']
        ], $data);
    }

    /** @test */
    public function can_add_column_to_headers()
    {
        $header = new Header(['name', 'age']);
        $row = new Row($header);
        $row->setKeyed(['name' => 'Colby', 'age' => 26]);

        $header->addColumn('occupation');

        $this->assertEquals(['Colby', 26, null], $row->toArray());
    }

    /** @test */
    public function can_get_certain_values_from_row()
    {
        $header = new Header(['name', 'age', 'food']);
        $row = new Row($header);
        $row->setKeyed(['name' => 'Colby', 'age' => 26, 'food' => 'pizza']);

        $this->assertEquals(
            ['name' => 'Colby'],
            $row->only('name')
        );

        $this->assertEquals(
            ['location' => 'Louisiana'],
            $row->only('location', 'Louisiana')
        );

        $this->assertEquals(
            ['name' => 'Colby', 'location' => 'NOT_SET'],
            $row->only(['name', 'location'], 'NOT_SET')
        );

        $this->assertSame(
            ['age' => 26, 'name' => 'Colby'],
            $row->only(['age', 'name'])
        );
    }
}