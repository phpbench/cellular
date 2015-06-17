<?php

namespace DTL\Cellular\Tests\Unit;

use DTL\Cellular\Util;

class UtilTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It should sort and preserve order for unchanging comparisons
     */
    public function testSort()
    {
        $array = array(
            array('col1' => 20, 'col2' => 20),
            array('col1' => 20, 'col2' => 10),
            array('col1' => 10, 'col2' => 50),
            array('col1' => 10, 'col2' => 10),
            array('col1' => 10, 'col2' => 20),
        );

        $expected = array(
            array('col1' => 10, 'col2' => 50),
            array('col1' => 10, 'col2' => 10),
            array('col1' => 10, 'col2' => 20),
            array('col1' => 20, 'col2' => 20),
            array('col1' => 20, 'col2' => 10),
        );

        Util::mergesort($array, function ($row1, $row2) {
            return strcmp($row1['col1'], $row2['col1']);
        });

        $this->assertEquals($expected, $array);
    }
}
