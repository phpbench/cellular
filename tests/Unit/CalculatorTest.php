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

use DTL\DataTable\Calculator;
use DTL\DataTable\Cell;
use DTL\DataTable\Row;

class CalculatorTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should return the sum.
     */
    public function testSum()
    {
        $sum = Calculator::sum(array(30, 3));
        $this->assertEquals(33, $sum);
    }

    /**
     * It should return the min.
     */
    public function testMin()
    {
        $min = Calculator::min(array(4, 6, 1, 5));
        $this->assertEquals(1, $min);
    }

    /**
     * It should return the max.
     */
    public function testMax()
    {
        $max = Calculator::max(array(3, 1, 13, 5));
        $this->assertEquals(13, $max);
    }

    /**
     * It should return the average.
     */
    public function testMean()
    {
        $expected = 33 / 7;
        $this->assertEquals($expected, Calculator::mean(array(2, 2, 2, 2, 2, 20, 3)));
    }

    /**
     * Mean should handle no values.
     */
    public function testMeanNoValue()
    {
        $this->assertEquals(0, Calculator::mean(array()));
    }

    /**
     * It should return the median of an even set of numbers.
     * The median should be the average between the middle two numbers.
     */
    public function testMedianEven()
    {
        $this->assertEquals(6, Calculator::median(array(9, 5, 7, 3)));
        $this->assertEquals(8, Calculator::median(array(9, 5, 7, 3, 10, 20)));
    }

    /**
     * It should return the median of an odd set of numbers
     */
    public function testMedianOdd()
    {
        $this->assertEquals(3, Calculator::median(array(10, 3, 3), true));
        $this->assertEquals(3, Calculator::median(array(10, 8, 3, 1, 2), true));
    }

    /**
     * Median should handle no values.
     */
    public function testMedianNoValues()
    {
        $this->assertEquals(0, Calculator::median(array()));
    }

    /**
     * It should work with Cell objects
     */
    public function testSumCell()
    {
        $result = Calculator::sum(array(new Cell(12), new Cell(12)));
        $this->assertEquals(24, $result);
    }

    /**
     * It should work with Cellular objects
     */
    public function testSumCellular()
    {
        $result = Calculator::sum(
            array(
                new Row(array(new Cell(12), new Cell(12))),
                new Row(array(new Cell(12), new Cell(12))),
            )
        );
        $this->assertEquals(48, $result);
    }

    /**
     * It should throw an exception if the value is not a valid object
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Values must be either of type Cellular, Cell or they must be numeric. Got "stdClass"
     */
    public function testSumNonValidObject()
    {
        Calculator::sum(
            array(
                new \stdClass,
            )
        );
    }

    /**
     * It should throw an exception if the value is not a valid scalar
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Values must be either of type Cellular, Cell or they must be numeric. Got "string"
     */
    public function testSumNonValidScalar()
    {
        Calculator::sum(
            array(
                'hello',
            )
        );
    }
}
