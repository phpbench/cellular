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

abstract class AggregateableCase extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return the sum.
     */
    public function testSum()
    {
        $sum = $this->getAggregate()->sum(['x']);
        $this->assertEquals(33, $sum);
    }

    /**
     * It should return the min.
     */
    public function testMin()
    {
        $min = $this->getAggregate()->min(['x']);
        $this->assertEquals(1, $min);
    }

    /**
     * It should return the max.
     */
    public function testMax()
    {
        $max = $this->getAggregate()->max(['x']);
        $this->assertEquals(13, $max);
    }

    /**
     * It should return the average.
     */
    public function testAverage()
    {
        $expected = 33 / 7;
        $this->assertEquals($expected, $this->getAggregate()->avg(['x']));
    }

    /**
     * Average should handle no values.
     */
    public function testAverageNoValue()
    {
        $this->assertEquals(0, $this->getAggregate()->avg(['notexist']));
    }

    /**
     * It should return the median (floor).
     */
    public function testMedianFloor()
    {
        $this->assertEquals('3', $this->getAggregate()->median(['x']));
    }

    /**
     * It should return the median (ceil).
     */
    public function testMedianCeil()
    {
        $this->assertEquals('5', $this->getAggregate()->median(['x'], true));
    }

    /**
     * Median should handle no values.
     */
    public function testMedianNoValues()
    {
        $this->assertEquals(0, $this->getAggregate()->median(['notexist']));
    }

    /**
     * It should return all of the values.
     */
    public function testValues()
    {
        $expected = array(1, 13, 1, 5, 8, 3, 2);
        $this->assertEquals($expected, array_values($this->getAggregate()->values(['x'])));
    }

    /**
     * It should apply a closure to each cell.
     */
    public function testMap()
    {
        $expected = array(2, 14, 2, 6, 9, 4, 3);
        $aggregate = $this->getAggregate();
        $aggregate->map(function (Cell $cell) {
            return $cell->getValue() + 1;
        });
        $this->assertEquals($expected, array_values($aggregate->values(['x'])));
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
