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

class RowTest extends AggregateableCase
{
    public function getAggregate()
    {
        return $this->getRowAggregate();
    }

    /**
     * It should get cells by index.
     */
    public function testGetCell()
    {
        $cell = $this->getAggregate()->getCell(0);
        $this->assertEquals('text', $cell->value());

        $cell = $this->getAggregate()->getCell(4);
        $this->assertEquals(5, $cell->value());
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
}
