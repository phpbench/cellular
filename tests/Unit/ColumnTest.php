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

class ColumnTest extends \PHPUnit_Framework_TestCase
{
    private $column;

    public function setUp()
    {
        $table = new Table(array(
            new Row(array(
                new Cell(1),
                new Cell(1),
                new Cell(2), // << we are here
                new Cell(3),
            )),
            new Row(array(
                new Cell(5),
                new Cell(8),
                new Cell(13), // << we are here
                new Cell(21),
            )),
        ));

        $this->column = new Column($table, 2);
    }

    /**
     * It should return all of the values.
     */
    public function testValues()
    {
        $expected = array(2, 13);
        $this->assertEquals($expected, $this->column->getValues());
    }

    /**
     * Its should return the groups of the cells contained within.
     */
    public function testGetGroups()
    {
        $table = new Table(array(
            new Row(array(
                new Cell(1, array('foo')),
            )),
            new Row(array(
                new Cell(5, array('bar')),
            )),
        ));

        $column = new Column($table, 0);
        $this->assertEquals(array('foo', 'bar'), $column->getGroups());
    }

    /**
     * Its hould return cells.
     */
    public function testGetCells()
    {
        $table = new Table(array(
            new Row(array(
                new Cell(1, array('foo')),
            )),
            new Row(array(
                new Cell(5, array('bar')),
            )),
        ));

        $column = new Column($table, 0);
        $this->assertCount(2, $column->getCells());
        $this->assertCount(1, $column->getCells(array('foo')));
    }

    /**
     * It should return an array.
     */
    public function testToArray()
    {
        $table = new Table(array(
            new Row(array(
                new Cell(1, array('foo')),
            )),
            new Row(array(
                new Cell(5, array('bar')),
            )),
        ));
        $column = new Column($table, 0);
        $this->assertEquals(array(1, 5), $column->toArray());
    }
}
