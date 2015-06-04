<?php

namespace DTL\Cellular\Tests\Unit;

use DTL\Cellular\Workspace;
use DTL\Cellular\Table;
use DTL\Cellular\Cell;
use DTL\Cellular\Row;

class WorkspaceTest extends AggregateableCase
{
    public function getAggregate()
    {
        return new Workspace(array(
            new Table(array(
                parent::getRowAggregate(),
            ))
        ));
    }

    /**
     * It should only accept elements of type Row.
     *
     * @expectedException DTL\Cellular\Exception\InvalidCollectionTypeException
     */
    public function testInvalidElement()
    {
        $this->getAggregate()[1] = new Cell('asd');
    }

    /**
     * It should return an array representation.
     */
    public function testToArray()
    {
        $workspace = new Workspace(array(
            new Table(array(
                new Row(array(
                    new Cell(4),
                    new Cell(2),
                )),
                new Row(array(
                    new Cell('hello'),
                    new Cell('goodbye'),
                )),
            )),
        ));

        $expected = array(
            array(
                array(
                    4,
                    2,
                ),
                array(
                    'hello',
                    'goodbye',
                ),
            ),
        );

        $this->assertEquals($expected, $workspace->toArray());
    }

    /**
     * It should return a row by index.
     */
    public function testGetTable()
    {
        $workspace = Workspace::create();
        $workspace->createAndAddTable();
        $table2 = $workspace->createAndAddTable();

        $this->assertSame($workspace->getTable(1), $table2);
    }

    /**
     * It should return a rows by groups.
     */
    public function testGetTableByGroups()
    {
        $workspace = Workspace::create();
        $workspace->createAndAddTable(array('foo'));
        $this->assertCount(0, $workspace->getTables(array('bar')));
        $this->assertCount(1, $workspace->getTables(array('foo')));
    }

    /**
     * It returns empty for groups (workspaces cannot have groups)
     */
    public function testGetGroups()
    {
        $workspace = Workspace::create();
        $this->assertCount(0, $workspace->getGroups());
    }
}
