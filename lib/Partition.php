<?php

namespace DTL\DataTable;

class Partition
{
    private $elements;

    public function __construct(array $elements = array())
    {
        $this->elements = $elements;
    }

    public function first()
    {
        return reset($this->elements);
    }

    public function get($offset)
    {
        return $this->elements[$offset];
    }

    public function set($offset, $value)
    {
        $this->elements[$offset] = $value;
    }

    public function add($value)
    {
        if (null === $value) {
        throw new \Exception('asd');
        }
        $this->elements[] = $value;
    }

    public function exists($offset)
    {
        return array_key_exists($offset, $this->elements);
    }

    public function remove($offset)
    {
        unset($this->elements[$offset]);
    }

    public function count()
    {
        return count($this->elements);
    }

    public function getElements()
    {
        return $this->elements;
    }

    public function sort(\Closure $closure)
    {
        usort($this->elements, $closure);
    }

    public function apply(\Closure $closure)
    {
        foreach ($this->elements as $key => &$element) {
            $closure($element, $key);
        }
    }

    public function map(\Closure $closure)
    {
        foreach ($this->elements as $key => $element) {
            $this->elements[$key] = $closure($element, $key);
        }
    }

    public function evaluate(\Closure $closure, $initialValue = null)
    {
        $value = $initialValue;
        foreach ($this->elements as $element) {
            $value = $closure($element, $value);
        }

        return $value;
    }
}
