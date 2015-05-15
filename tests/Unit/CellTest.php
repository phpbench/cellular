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

class CellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It can say if it is in a group or not.
     */
    public function testInGroup()
    {
        $cell = new Cell('test', array('foo'));
        $this->assertTrue($cell->inGroup('foo'));
        $this->assertFalse($cell->inGroup('bar'));
    }

    /**
     * It can get a value.
     */
    public function testGetSetValue()
    {
        $cell = new Cell('test');
        $this->assertEquals('test', $cell->value());
    }

    /**
     * It satisfies the AggregateableInterface.
     */
    public function testSatisfaction()
    {
        $cell = new Cell('test');
        foreach (array(
            'value', 'sum', 'min', 'max', 'avg', 'median',
        ) as $method) {
            $this->assertEquals('test', $cell->$method());
        }
    }
}
