<?php

namespace DTL\Cellular;

trait AttributeTrait
{
    private $attributes = array();

    /**
     * {@inheritDoc}
     */
    public function getAttribute($name)
    {
        if (!isset($this->attributes[$name])) {
            throw new \InvalidArgumentException(sprintf(
                'Attribute with name "%s" does not exist. Known attributes: "%s"',
                $name, implode('", "', $this->attributes)
            ));
        }

        return $this->attributes[$name];
    }

    /**
     * {@inheritDoc}
     */
    public function hasAttribute($name)
    {
        return array_key_exists($name, $this->attributes);
    }

    /**
     * {@inheritDoc}
     */
    public function setAttribute($name, $value)
    {
        $this->attributes[$name] = $value;
    }

    /**
     * {@inheritDoc}
     */
    public function setAttributes(array $attributes)
    {
        $this->attributes = $attributes;
    }

    /**
     * {@inheritDoc}
     */
    public function getAttributes()
    {
        return $this->attributes;
    }
}
