<?php

namespace DTL\DataTable;

/**
 * Base partitioned collection.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Collection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    private $partitions = array();

    /**
     * The constructor accepts a variable number of arrays.
     *
     * Each array represents a partition.
     */
    public function __construct()
    {
        $partitions = func_get_args();

        if (count($partitions) === 0) {
            $partitions = array(array());
        }

        foreach ($partitions as $partitionData) {
            $this->partitions[] = new Partition($partitionData);
        }
    }

    /**
     * Create a new collection
     *
     * @param mixed[] $elements
     *
     * @return this
     */
    public static function create(array $elements = array())
    {
        return new static($elements);
    }

    /**
     * Return the primary partition
     *
     * @return Parition
     */
    public function getPrimaryPartition()
    {
        return $this->partitions[0];
    }

    /**
     * Return all partitions
     *
     * @return Partition[]
     */
    public function getPartitions()
    {
        return $this->partitions;
    }

    /**
     * Return all the elements from all partitions as an associative array>
     *
     * Note that overlapping keys will be overwritten.
     *
     * @return mixed[]
     */
    public function getElements()
    {
        if (count($this->partitions) === 1) {
            return $this->getPrimaryPartition()->getElements();
        }
        $elements = array();
        foreach ($this->partitions as $partition) {
            $partitionElements = $partition->getElements();
            $elements = array_merge($elements, $partitionElements);
        }

        return $elements;
    }

    /**
     * Sort each partion with the given closure.
     *
     * @param \Closure $closure
     */
    public function sort(\Closure $closure)
    {
        foreach ($this->partitions as &$partition) {
            $partition->sort($closure);
        }

        return $this;
    }

    /**
     * Evaluate a value from each element.
     *
     * The closure is passed the element and a the value which was evaluated from
     * the last element. The initial value can be specified.
     *
     * @param \Closure $closure
     * @param mixed $initialValue
     *
     * @return mixed
     */
    public function evaluate(\Closure $closure, $initialValue = null)
    {
        $value = $initialValue;
        foreach ($this->partitions as &$partition) {
            $value = $partition->evaluate($closure, $value);
        }

        return $value;
    }

    /**
     * (re) Parition the union of existing partitions.
     *
     * The closure should return a unique key which will be used
     * as the discriminator for dividing partitions.
     *
     * @param \Closure $closure
     */
    public function partition(\Closure $closure)
    {
        $unifiedParition = $this->getElements();

        $lastDiscriminator = null;
        $currentPartition = new Partition();
        $partitions = array();

        foreach ($unifiedParition as $key => $element) {
            $discriminator = $closure($element);

            if (null === $lastDiscriminator) {
                $lastDiscriminator = $discriminator;
            }

            if ($lastDiscriminator != $discriminator) {
                $partitions[] = $currentPartition;
                $currentPartition = new Partition();
            }

            $currentPartition->set($key, $element);

            $lastDiscriminator = $discriminator;
        }

        $partitions[] = $currentPartition;

        $this->partitions = $partitions;

        return $this;
    }

    /**
     * Fork a new instance based on each partition.
     *
     * The closure will be passed each partition in turn and can
     * modify a new instance of this collection.
     *
     * @param \Closure $closure
     * @return this
     */
    public function fork(\Closure $closure)
    {
        $newInstance = new static(array());
        foreach ($this->partitions as $partition) {
            $collection = new static($partition->getElements());
            $closure($collection, $newInstance);
        }

        return $newInstance;
    }

    public function first()
    {
        return $this->getPrimaryPartition()->first();
    }

    /*
     * Apply the given closure to each element in the union
     * of partitions.
     *
     * @param \Closure $closure
     */
    public function apply(\Closure $closure)
    {
        foreach ($this->partitions as $partition) {
            $partition->apply($closure);
        }
    }

    /**
     * Each element will be replaced by the value returned by the given
     * closure, which is itself given the original value.
     *
     * @param \Closure $closure
     */
    public function map(\Closure $closure)
    {
        foreach ($this->partitions as $partition) {
            $partition->map($closure);
        }
    }

    /**
     * Return a new instance of this collection with only the elements from this
     * instance which satisft the given filter.
     *
     * @param \Closure $closure
     */
    public function filter(\Closure $closure)
    {
        return new static(array_filter($this->getElements(), $closure));
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $count = 0;
        foreach ($this->partitions as $partition) {
            $count+= $partition->count();
        }

        return $count;
    }

    public function keys()
    {
        return array_keys($this->getElements());
    }

    /**
     * {@inheritDoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->getElements());
    }

    /**
     * {@inheritDoc}
     */
    public function offsetExists($offset)
    {
        $this->assertSinglePartition(__METHOD__);

        return $this->getPrimaryPartition()->exists($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetGet($offset)
    {
        $this->assertSinglePartition(__METHOD__);

        return $this->getPrimaryPartition()->get($offset);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetSet($offset, $value)
    {
        $this->assertSinglePartition(__METHOD__);
        $this->validateElement($value);

        if (null === $offset) {
            $this->getPrimaryPartition()->add($value);
            return;
        }

        $this->getPrimaryPartition()->set($offset, $value);
    }

    /**
     * {@inheritDoc}
     */
    public function offsetUnset($offset)
    {
        $this->assertSinglePartition(__METHOD__);

        return $this->getPrimaryPartition()->remove($offset);
    }

    /**
     * Asssert that the collection has a single partition
     *
     * @param string $methodName
     * @throws \InvalidArgumentException
     */
    protected function assertSinglePartition($method)
    {
        if (count($this->partitions) === 1) {
            return;
        }

        throw new \InvalidArgumentException(sprintf(
            'Method "%s" requires a collection with a single partition. This table has "%s"',
            $method, count($this->partitions)
        ));
    }

    protected function validateElement($element)
    {
    }
}
