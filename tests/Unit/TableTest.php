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

class TableTest extends AggregateableCase
{
    public function getAggregate()
    {
        return new Table(array(
            parent::getRowAggregate(),
        ));
    }

    /**
     * It should get columns
     */
    public function testGetColumn()
    {
        $column = $this->getAggregate()->getColumn(1);
        $this->assertEquals(1, $column->sum());
    }

    /**
     * It should return an array representation
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
                2
            ),
            array(
                'hello',
                'goodbye'
            )
        );

        $this->assertEquals($expected, $table->toArray());
    }

    /**
     * It should provide a table builder
     */
    public function testTableBuilder()
    {
        $table = Table::createBuilder()
            ->row()
                ->cell('hello', ['group1'])
                ->cell('goodbye', ['group2'])
            ->end()
            ->row()
                ->cell('bonjour', ['group1'])
                ->cell('aurevoir', ['group2'])
            ->end()
            ->getTable();

        $expectedTable = new Table(array(
            new Row(array(
                new Cell('hello', ['group1']),
                new Cell('goodbye', ['group2']),
            )),
            new Row(array(
                new Cell('bonjour', ['group1']),
                new Cell('aurevoir', ['group2']),
            )),
        ));

        $this->assertEquals($expectedTable, $table);
    }

    /**
     * It should produce a new aggregated table based on unique column values
     */
    public function testAggregate()
    {
        $table = Table::createBuilder()
            ->row()
                ->cell('hello')
                ->cell(12)
                ->cell('goodbye')
            ->end()
            ->row()
                ->cell('hello')
                ->cell(12)
                ->cell('goodbye')
            ->end()
            ->row()
                ->cell('goodbye')
                ->cell(12)
                ->cell('bar')
            ->end()
            ->getTable();

        $newTable = $table->aggregate(array(0));
        $this->assertNotSame($table, $newTable);
        $this->assertCount(2, $newTable->getRows());
        $this->assertEquals(24, $newTable->getRow(0)->getCell(1)->value());
        $this->assertEquals(12, $newTable->getRow(1)->getCell(1)->value());
    }
}
