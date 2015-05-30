<?php

/*
 * This file is part of the Table Data package
 *
 * (c) Daniel Leech <daniel@dantleech.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace DTL\DataTable;

/**
 * Represents an partition of a collection.
 *
 * Partitions are used internally for batching chainined transformations.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Partition
{
    /**
     * @var mixed[]
     */
    private $elements;

    /**
     * @param mixed[] $elements
     */
    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }

    /**
     * Return the frist element.
     *
     * @return mixed
     */
    public function first()
    {
        return reset($this->elements);
    }

    /**
     * Return the element at the given offset.
     *
     * @return mixed
     */
    public function get($offset)
    {
        if (!isset($this->elements[$offset])) {
            throw new \OutOfBoundsException(sprintf(
                'Offset "%s" does not exist. Known offsets: "%s"',
                $offset, implode('", "', array_keys($this->elements))
            ));
        }

        return $this->elements[$offset];
    }

    /**
     * Set the value at the given offset.
     *
     * @param string $offset
     * @param mixed $value
     */
    public function set($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    /**
     * Add a new value to the partition.
     *
     * @param mixed $value
     */
    public function add($value)
    {
        $this->elements[] = $value;
    }

    /**
     * Return true if the offset exists, otherwise false.
     *
     * @param mixed $offset
     *
     * @return bool
     */
    public function exists($offset)
    {
        return array_key_exists($offset, $this->elements);
    }

    /**
     * Remove the element at the given offset.
     *
     * @param mixed $offset
     */
    public function remove($offset)
    {
        unset($this->elements[$offset]);
    }

    /**
     * Return the number of elements in the partition.
     *
     * @return int
     */
    public function count()
    {
        return count($this->elements);
    }

    /**
     * Return the elements in the partition.
     *
     * @return mixed[]
     */
    public function getElements()
    {
        return $this->elements;
    }

    /**
     * Apply a usort to the elements in this partition.
     *
     * @param \Closure $closure
     */
    public function sort(\Closure $closure)
    {
        usort($this->elements, $closure);
    }

    /**
     * Apply a closure to each element.
     *
     * @param \Closure $closure
     */
    public function each(\Closure $closure)
    {
        foreach ($this->elements as $key => &$element) {
            $closure($element, $key);
        }
    }

    /**
     * Map a closure to each element.
     *
     * Each element will be replaced by the return value of the closure
     * which will be passed the elements current value.
     *
     * @param \Closure $closure
     */
    public function map(\Closure $closure)
    {
        foreach ($this->elements as $key => $element) {
            $this->elements[$key] = $closure($element, $key);
        }
    }

    /**
     * Evaluate the given clousre on each element.
     *
     * @param \Closure $closure
     * @param mixed $initialValue
     */
    public function evaluate(\Closure $closure, $initialValue = null)
    {
        $value = $initialValue;
        foreach ($this->elements as $element) {
            $value = $closure($element, $value);
        }

        return $value;
    }

    public function __clone()
    {
        foreach ($this->elements as $index => $element) {
            if (is_object($element)) {
                $this->elements[$index] = clone $element;
            }
        }
    }
}
