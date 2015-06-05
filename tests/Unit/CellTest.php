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

class CellTest extends \PHPUnit_Framework_TestCase
{
    /**
     * It can get its groups.
     */
    public function testGetGroups()
    {
        $cell = new Cell('test', array('foo'));
        $this->assertEquals(array('foo'), $cell->getGroups());
    }

    /**
     * It can determine if it belongs to a given group.
     */
    public function testInGroup()
    {
        $cell = new Cell('test', array('foo'));
        $this->assertTrue($cell->inGroup('foo'));
        $this->assertFalse($cell->inGroup('aww'));
    }

    /**
     * It can get a value.
     */
    public function testGetSetValue()
    {
        $cell = new Cell('test');
        $this->assertEquals('test', $cell->getValue());
    }
}
