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

use DTL\DataTable\Cell;
use DTL\DataTable\Row;

class RowTest extends AggregateableCase
{
    public function getAggregate()
    {
        return $this->getRowAggregate();
    }

    /**
     * It should only accept elements of type Cell.
     *
     * @expectedException DTL\DataTable\Exception\InvalidCollectionTypeException
     */
    public function testInvalidElement()
    {
        $this->getAggregate()[2] = 'as';
    }

    /**
     * It should get cells by index.
     */
    public function testGetCell()
    {
        $cell = $this->getAggregate()->getCell(0);
        $this->assertEquals('text', $cell->getValue());

        $cell = $this->getAggregate()->getCell(4);
        $this->assertEquals(5, $cell->getValue());
    }

    /**
     * Its should throw an exception if a cell index is out of range.
     *
     * @expectedException OutOfBoundsException
     */
    public function testGetCellOutOfRange()
    {
        $this->getAggregate()->getCell(999);
    }

    /**
     * It should return an array representation.
     */
    public function testToArray()
    {
        $row = new Row(array(
            'hello' => new Cell('goodbye'),
            0 => new Cell('hello'),
        ));

        $this->assertEquals(array(
            'hello' => 'goodbye',
            0 => 'hello',
        ), $row->toArray());
    }

    /**
     * It should set the value of an existing cell.
     */
    public function testSetExisting()
    {
        $row = new Row(array(
            'hello' => new Cell('goodbye'),
        ));

        $row->set('hello', 'hello');
        $this->assertEquals($row['hello']->getValue(), 'hello');
    }

    /**
     * It should create a new cell if a non-existing column name is specified in "set".
     */
    public function testSetNonExisting()
    {
        $row = new Row(array(
            'hello' => new Cell('goodbye'),
        ));

        $row->set('goodbye', 'hello');
        $this->assertInstanceOf('DTL\DataTable\Cell', $row['goodbye']);
        $this->assertEquals($row['goodbye']->getValue(), 'hello');
    }
}
