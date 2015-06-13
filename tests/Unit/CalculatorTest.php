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

use DTL\Cellular\Calculator;
use DTL\Cellular\Cell;
use DTL\Cellular\Row;

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
     * Sum should accept a Cellular instance.
     */
    public function testSumCellular()
    {
        $sum = Calculator::sum(new Row(array(new Cell(30), new Cell(3))));
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
     * Mean should return 0 if the sum of all values is zero
     */
    public function testMeanAllZeros()
    {
        $this->assertEquals(0, Calculator::mean(array(0, 0, 0)));
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
     * It should return the median of an odd set of numbers.
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
     * It should work with Cell objects.
     */
    public function testSumCell()
    {
        $result = Calculator::sum(array(new Cell(12), new Cell(12)));
        $this->assertEquals(24, $result);
    }

    /**
     * It should work with Cellular objects.
     */
    public function testSumCellularArray()
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
     * It should throw an exception if the value is not a valid object.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Values must be either of type CellularInterface, Cell or they must be numeric. Got "stdClass"
     */
    public function testSumNonValidObject()
    {
        Calculator::sum(
            array(
                new \stdClass(),
            )
        );
    }

    /**
     * It should throw an exception if the value is not a valid scalar.
     *
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage Values must be either of type CellularInterface, Cell or they must be numeric. Got "string"
     */
    public function testSumNonValidScalar()
    {
        Calculator::sum(
            array(
                'hello',
            )
        );
    }

    /**
     * It should provide a deviation as a percentage.
     */
    public function testDeviation()
    {
        $this->assertEquals(0, Calculator::deviation(10, 10));
        $this->assertEquals(100, Calculator::deviation(10, 20));
        $this->assertEquals(-10, Calculator::deviation(10, 9));
        $this->assertEquals(10, Calculator::deviation(10, 11));
        $this->assertEquals(11, Calculator::deviation(0, 11));
        $this->assertEquals(-100, Calculator::deviation(10, 0));
        $this->assertEquals(0, Calculator::deviation(0, 0));
    }

    /**
     * It can supply deviation using a Cell as the value.
     */
    public function testDeviationCell()
    {
        $this->assertEquals(-10, Calculator::deviation(10, new Cell(9)));
    }
}
