<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\DataTable\Tests\Unit\Builder;

use DTL\DataTable\Table;
use DTL\DataTable\Builder\TableBuilder;
use DTL\DataTable\Row;
use DTL\DataTable\Cell;

class TableBuilderTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Its hould create a new instance.
     */
    public function testTableBuilder()
    {
        $table = TableBuilder::create()
            ->row()
                ->set(0, 'hello', ['group1'])
                ->set(1, 'goodbye', ['group2'])
            ->end()
            ->row()
                ->set(0, 'bonjour', ['group1'])
                ->set(1, 'aurevoir', ['group2'])
            ->end()
            ->getTable();

        $expectedTable = new Table(array(
            new Row(array(
                new Cell('hello', ['group1']),
                new Cell('goodbye', ['group2']),
            )),
            new Row(array(
                new Cell('bonjour', ['group1']),
                new Cell('aurevoir', ['group2']),
            )),
        ));

        $this->assertEquals($expectedTable, $table);
    }
}
