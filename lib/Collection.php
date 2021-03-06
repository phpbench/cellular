<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\Cellular;

use DTL\Cellular\Exception\InvalidCollectionTypeException;

/**
 * Base partitioned collection.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Collection implements \IteratorAggregate, \Countable, \ArrayAccess
{
    /**
     * @var Partition[]
     */
    private $partitions = array();

    /**
     * Create a new collection.
     *
     * @param mixed[] $elements
     *
     * @return Collection
     */
    public static function create(array $elements = array())
    {
        return new static($elements);
    }

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
     * Ensure that partitions are cloned when this collection is cloned.
     */
    public function __clone()
    {
        foreach ($this->partitions as $index => $partition) {
            $this->partitions[$index] = clone $partition;
        }
    }

    /**
     * Copy (clone) this collection.
     *
     * @return Collection
     */
    public function copy()
    {
        return clone $this;
    }

    /**
     * Return the primary partition.
     *
     * NOTE: There should always be a primary partition.
     *
     * @return Parition
     */
    public function getPrimaryPartition()
    {
        if (!isset($this->partitions[0])) {
            throw new \RuntimeException(sprintf(
                'Collection "%s" has no primary partition. Partition indexes: [%s]',
                get_class($this), implode(', ', array_keys($this->partitions))
            ));
        }

        return $this->partitions[0];
    }

    /**
     * Return all partitions.
     *
     * @return Partition[]
     */
    public function getPartitions()
    {
        return $this->partitions;
    }

    /**
     * Return all the elements from all partitions as an associative array.
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
     * Asssert that the collection has a single partition.
     *
     * @param string $methodName
     *
     * @throws \RuntimeException
     */
    protected function assertSinglePartition($method)
    {
        if (count($this->partitions) === 1) {
            return;
        }

        throw new \RuntimeException(sprintf(
            'Method "%s" requires a collection with a single partition. This table has "%s"',
            $method, count($this->partitions)
        ));
    }

    /**
     * Called each time an element is added to the collection.
     *
     * Should throw an InvalidCollectionTypeException if the element is
     * not valid.
     *
     * Override, for example, to enforce type
     *
     * @param mixed $element
     *
     * @throws InvalidCollectionTypeException
     */
    protected function validateElement($element)
    {
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
     * Aggregate the partions back to a single partition using
     * the given closure.
     *
     * The closure will be passed each partition in turn and can
     * modify a new instance of this collection. The partition from
     * the new instance will become the new partition for this collection.
     *
     * @param \Closure $closure
     *
     * @return Collection
     */
    public function aggregate(\Closure $closure)
    {
        $newInstance = new static(array());
        foreach ($this->partitions as $partition) {
            $collection = new static($partition->getElements());
            $closure($collection, $newInstance);
        }

        $this->partitions = array($newInstance->getPrimaryPartition());

        return $this;
    }

    /**
     * Return the first element.
     *
     * @return mixed
     */
    public function first()
    {
        $elements = $this->getElements();
        if (!$elements) {
            return;
        }
        $elements = array_values($elements);

        return $elements[0];
    }

    /*
     * Apply the given closure to each element in the union
     * of partitions.
     *
     * @param \Closure $closure
     */
    public function each(\Closure $closure)
    {
        foreach ($this->partitions as $partition) {
            $partition->each($closure);
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
     * Clear the collection.
     */
    public function clear()
    {
        $this->partitions = array(new Partition(array()));
    }

    /**
     * Filter elements in this collection.
     *
     * The closure should return `true` for elements which should
     * be retained.
     *
     * @param \Closure $closure
     */
    public function filter(\Closure $closure)
    {
        foreach ($this as $key => $element) {
            if (false === $closure($element, $key)) {
                unset($this[$key]);
            }
        }

        return $this;
    }

    /**
     * {@inheritDoc}
     */
    public function count()
    {
        $count = 0;
        foreach ($this->partitions as $partition) {
            $count += $partition->count();
        }

        return $count;
    }

    /**
     * Return the array keys of the combined paritions.
     *
     * @return string[]
     */
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
}
