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

use DTL\Cellular\Cell;
use DTL\Cellular\Row;

abstract class AggregateableCase extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return all of the values.
     */
    public function testGetValues()
    {
        $expected = array(1, 13, 1, 5, 8, 3, 2);
        $this->assertEquals($expected, array_values($this->getAggregate()->getValues(['x'])));
    }

    /**
     * It should apply a closure to each cell.
     */
    public function testMapValues()
    {
        $expected = array(2, 14, 2, 6, 9, 4, 3);
        $aggregate = $this->getAggregate();
        $aggregate->mapValues(function (Cell $cell) {
            return $cell->getValue() + 1;
        }, ['x']);
        $this->assertEquals($expected, array_values($aggregate->getValues(['x'])));
    }

    /**
     * It should get and set groups.
     */
    public function testGetSetGroups()
    {
        $aggregate = $this->getAggregate();
        $aggregate->setGroups(array('one', 'two'));
        $this->assertEquals(array('one', 'two'), $aggregate->getGroups());
    }

    /**
     * It should say if it is in a given group or not.
     */
    public function testInGroup()
    {
        $aggregate = $this->getAggregate();
        $aggregate->setGroups(array('one', 'two'));
        $this->assertTrue($aggregate->inGroup('one'));
        $this->assertFalse($aggregate->inGroup('vache'));
    }

    /**
     * It should get and set attributes.
     */
    public function testSetGetAttribute()
    {
        $aggregate = $this->getAggregate();
        $aggregate->setAttribute('foo', 'bar');
        $this->assertTrue($aggregate->hasAttribute('foo'));
        $this->assertFalse($aggregate->hasAttribute('baz'));
        $this->assertEquals('bar', $aggregate->getAttribute('foo'));
    }

    /**
     * It should set all attributes
     */
    public function testSetAllAttributes()
    {
        $aggregate = $this->getAggregate();
        $aggregate->setAttributes(array('foo' => 'bar', 'bar' => 'foo'));
        $this->assertEquals(array('foo' => 'bar', 'bar' => 'foo'), $aggregate->getAttributes());
    }

    protected function getRowAggregate()
    {
        return new Row(array(
            new Cell('text'),
            new Cell(1, ['x']),
            new Cell(13, ['x']),
            new Cell(1, ['x']),
            new Cell(5, ['x']),
            new Cell(8, ['x']),
            new Cell(3, ['x']),
            new Cell(5, ['y']),
            new Cell(2, ['x']),
        ));
    }
}
