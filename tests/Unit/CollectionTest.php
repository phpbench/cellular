<?php

namespace DTL\DataTable\Tests\Unit;

use DTL\DataTable\Collection;

class CollectionTest extends \PHPUnit_Framework_TestCase
{
    private $collection;

    public function setUp()
    {
        $this->collection = new Collection();
    }

    /**
     * It should act as an array
     */
    public function testArrayAccess()
    {
        $this->collection['foo'] = 'bar';
        $this->assertEquals('bar', $this->collection['foo']);
        $this->assertTrue(isset($this->collection['foo']));
        $this->assertFalse(isset($this->collection['bar']));
    }

    /**
     * It should allow addition to an array
     */
    public function testArrayAddition()
    {
        $this->collection[] = 'a';
        $this->collection[] = 'b';

        $this->assertEquals(array('a', 'b'), $this->collection->getElements());
    }

    /**
     * It should be countable
     */
    public function testCount()
    {
        $this->assertCount(0, $this->collection);
        $this->collection['bar'] = 'foo';
        $this->assertCount(1, $this->collection);
    }

    /**
     * It should be iteratable
     */
    public function testIterator()
    {
        $this->collection['foo'] = 'bar';

        foreach ($this->collection as $key => $value) {
            $this->assertEquals('foo', $key);
            $this->assertEquals('bar', $value);
            return;
        }

        $this->fail('Did not iterate');
    }


    /**
     * Its should sort its elements
     */
    public function testSort()
    {
        $this->collection[0] = 7;
        $this->collection[1] = 2;
        $this->collection[2] = 9;

        $this->collection->sort(function ($a, $b) {
            return $a > $b;
        });

        $this->assertEquals(array(2, 7, 9), $this->collection->getElements());
    }

    /**
     * It should partition its elements
     */
    public function testPartition()
    {
        $this->collection[] = array('a', 'b');
        $this->collection[] = array('a', 'a');
        $this->collection[] = array('b', 'a');
        $this->collection[] = array('b', 'a');

        $this->collection->partition(function ($element) {
            return $element[0];
        });

        $this->assertCount(2, $this->collection->getPartitions());
        $partitions = $this->collection->getPartitions();
        $this->assertEquals(
            array(
                array('a', 'b'),
                array('a', 'a'),
            ),
            $partitions[0]->getElements()
        );
        $this->assertEquals(
            array(
                array('b', 'a'),
                array('b', 'a'),
            ),
            array_values($partitions[1]->getElements())
        );

    }

    /**
     * It flow partitions into a new instance
     */
    public function testFork()
    {
        $collection = new Collection(array('one', 'two', 'three'), array('four', 'five', 'six'));
        $newInstance = $collection->fork(function ($partition, $newInstance) {
            $newInstance[] = $partition->first();
        });

        $this->assertEquals(array(
            'one', 'four'
        ), $newInstance->getElements());
    }

    /**
     * It should apply a closure to each element
     */
    public function testApply()
    {
        $collection = new Collection(array(1, 2), array(3, 4));
        $collection->apply(function (&$element) {
            $element++;
        });

        $this->assertEquals(
            array(2, 3, 4, 5),
            $collection->getElements()
        );
    }

    /**
     * It should map a closure to each element
     */
    public function testMap()
    {
        $collection = new Collection(array(1, 2), array(3, 4));
        $collection->map(function ($element) {
            return $element * 2;
        });

        $this->assertEquals(
            array(2, 4, 6, 8),
            $collection->getElements()
        );
    }

    /**
     * It should evaluate a closure on each element
     */
    public function testEvaluate()
    {
        $collection = new Collection(array(1, 2), array(3, 4));
        $result = $collection->evaluate(function ($element, $value) {
            return $element + $value;
        }, 0);

        $this->assertEquals(10, $result);
    }

    /**
     * It should be able to do some pretty cool stuff
     */
    public function testCoolStuff()
    {
        $collection = Collection::create(array(
            array(
                'a' => 10,
                'class' => 'red',
            ),
            array(
                'a' => 20,
                'class' => 'green',
            ),
            array(
                'a' => 10,
                'class' => 'red',
            ),
        ))->sort(function ($row1, $row2) {
            return $row1['class'] > $row2['class'];
        })->partition(function ($row) {
            return $row['class'];
        })->fork(function ($partition, $instance) {
            $instance[] = $partition->evaluate(function ($row, $value) {
                return $value + $row['a'];
            }, 0);
        });

        $this->assertEquals(array(20, 20), $collection->getElements());
    }

    /**
     * It should filter
     */
    public function testFilter()
    {
        $collection = Collection::create(array(
            array(
                'a' => 10,
                'class' => 'red',
            ),
            array(
                'a' => 20,
                'class' => 'green',
            ),
            array(
                'a' => 10,
                'class' => 'red',
            ),
        ))->filter(function ($element) {
            return $element['class'] === 'green';
        });

        $this->assertCount(1, $collection);
    }
}
