<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular\Tests\Unit;

use DTL\Cellular\Table;
use DTL\Cellular\Row;
use DTL\Cellular\Cell;
use DTL\Cellular\Column;

class TableTest extends AggregateableCase
{
    public function getAggregate()
    {
        return new Table(array(
            parent::getRowAggregate(),
        ));
    }

    /**
     * It should only accept elements of type Row.
     *
     * @expectedException DTL\Cellular\Exception\InvalidCollectionTypeException
     */
    public function testInvalidElement()
    {
        $this->getAggregate()[2] = new Cell('asd');
    }

    /**
     * It should get columns.
     */
    public function testGetColumn()
    {
        $column = $this->getAggregate()->getColumn(1);
        $this->assertEquals(array(1), $column->getValues());
    }

    /**
     * It should return an array representation.
     */
    public function testToArray()
    {
        $table = new Table(array(
            new Row(array(
                new Cell(4),
                new Cell(2),
            )),
            new Row(array(
                new Cell('hello'),
                new Cell('goodbye'),
            )),
        ));

        $expected = array(
            array(
                4,
                2,
            ),
            array(
                'hello',
                'goodbye',
            ),
        );

        $this->assertEquals($expected, $table->toArray());
    }

    /**
     * It should return a list of column names.
     */
    public function testGetColumnNames()
    {
        $table = Table::create();
        $table->createAndAddRow()
            ->set(0, 'hello', ['one'])
            ->set(1, 12);
        $table->createAndAddRow()
            ->set(0, 'hello')
            ->set(1, 12);
        $table->createAndAddRow()
            ->set(0, 'goodbye', ['one'])
            ->set(1, 12);

        $columnNames = $table->getColumnNames();
        $this->assertEquals(array(0, 1), $columnNames);

        return $table;
    }

    /**
     * It should return a list of column names according to group.
     *
     * @depends testGetColumnNames
     */
    public function testGetColumnNamesForGroup(Table $table)
    {
        $this->assertEquals(array(0), $table->getColumnNames(['one']));
    }

    /**
     * It should return all columns.
     *
     * @depends testGetColumnNames
     */
    public function testGetColumns(Table $table)
    {
        $columns = $table->getColumns();
        $this->assertContainsOnlyInstancesOf('DTL\Cellular\Column', $columns);
        $this->assertCount(2, $columns);
    }

    /**
     * It should return all columns.
     *
     * @depends testGetColumnNames
     */
    public function testGetColumnCount(Table $table)
    {
        $count = $table->getColumnCount();
        $this->assertEquals(2, $count);
    }

    /**
     * It should return a row by index.
     */
    public function testGetRow()
    {
        $table = Table::create();
        $table->createAndAddRow();
        $row2 = $table->createAndAddRow();

        $this->assertSame($table->getRow(1), $row2);
    }

    /**
     * It should throw an exception for an unknown row
     *
     * @expectedException \OutOfRangeException
     * @expectedExceptionMessage Row with index "5" does not exist. Must be >=0 < 2
     */
    public function testGetRowNotExist()
    {
        $table = Table::create();
        $table->createAndAddRow();
        $table->createAndAddRow();

        $table->getRow(5);
    }

    /**
     * It should return a rows by groups.
     */
    public function testGetRowByGroups()
    {
        $table = Table::create();
        $table->createAndAddRow(array('foo'));
        $this->assertCount(0, $table->getRows(array('bar')));
        $this->assertCount(1, $table->getRows(array('foo')));
    }

    /**
     * It should return its groups (tables never have any groups at the moment).
     */
    public function testGetGroups()
    {
        $table = Table::create();
        $this->assertCount(0, $table->getGroups());
    }

    /**
     * It should align the table and fill in missing cells in each row.
     */
    public function testAlign()
    {
        $table = Table::create();
        $table->createAndAddRow()
            ->set('hello', 'goodbye')
            ->set('goodbye', 'hello')
            ->set('adios', 'bienvenido');
        $table->createAndAddRow()
            ->set('aurevior', 'salut')
            ->set('gutentag', 'auf wiedersehen');
        $table->align();

        foreach ($table as $index => $row) {
            $key = 'hello';
            $this->assertTrue(isset($row[$key]), 'Row ' . $index . ' has key ' . $key);
        }
    }

    /**
     * It should be able to have a title.
     */
    public function testTitle()
    {
        $table = Table::create()
            ->setTitle('Hai')
            ->setTitle('Hai');
        $this->assertEquals('Hai', $table->getTitle());
    }

    /**
     * Its should be able to have a description.
     */
    public function testDescription()
    {
        $table = Table::create()
            ->setDescription('Hai')
            ->setDescription('Hai');
        $this->assertEquals('Hai', $table->getDescription());
    }
}
