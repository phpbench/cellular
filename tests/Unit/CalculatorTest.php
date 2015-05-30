<?php

namespace DTL\DataTable\Tests\Unit;

use DTL\DataTable\Calculator;

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
    public function testAverage()
    {
        $expected = 33 / 7;
        $this->assertEquals($expected, Calculator::avg(array(2, 2, 2, 2, 2, 20, 3)));
    }

    /**
     * Average should handle no values.
     */
    public function testAverageNoValue()
    {
        $this->assertEquals(0, Calculator::avg(array()));
    }

    /**
     * It should return the median (floor).
     */
    public function testMedianFloor()
    {
        $this->assertEquals(3, Calculator::median(array(10, 3, 3)));
    }

    /**
     * It should return the median (ceil).
     */
    public function testMedianCeil()
    {
        $this->assertEquals(10, Calculator::median(array(10, 3, 3), true));
    }

    /**
     * Median should handle no values.
     */
    public function testMedianNoValues()
    {
        $this->assertEquals(0, Calculator::median(array()));
    }

}
