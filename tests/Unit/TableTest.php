<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\DataTable\Tests\Unit;

use DTL\DataTable\Table;
use DTL\DataTable\Row;
use DTL\DataTable\Cell;
use DTL\DataTable\Column;
use DTL\DataTable\Builder\TableBuilder;

class TableTest extends AggregateableCase
{
    public function getAggregate()
    {
        return new Table(array(
            parent::getRowAggregate(),
        ));
    }

    /**
     * It should only accept elements of type Row
     *
     * @expectedException DTL\DataTable\Exception\InvalidCollectionTypeException
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
            ->setCell(0, 'hello', ['one'])
            ->setCell(1, 12);
        $table->createAndAddRow()
            ->setCell(0, 'hello')
            ->setCell(1, 12);
        $table->createAndAddRow()
            ->setCell(0, 'goodbye', ['one'])
            ->setCell(1, 12);

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
        $this->assertContainsOnlyInstancesOf('DTL\DataTable\Column', $columns);
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
     * It should return a row by index
     */
    public function testGetRow()
    {
        $table = Table::create();
        $table->createAndAddRow();
        $row2 = $table->createAndAddRow();

        $this->assertSame($table->getRow(1), $row2);
    }

    /**
     * It should return a rows by groups
     */
    public function testGetRowByGroups()
    {
        $table = Table::create();
        $table->createAndAddRow(array('foo'));
        $this->assertCount(0, $table->getRows(array('bar')));
        $this->assertCount(1, $table->getRows(array('foo')));
    }

    /**
     * It should return its groups (tables never have any groups at the moment)
     */
    public function testGetGroups()
    {
        $table = Table::create();
        $this->assertCount(0, $table->getGroups());
    }
}
