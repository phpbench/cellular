<?php

namespace DTL\Cellular;

trait GroupTrait
{
    protected $groups = array();

    /**
     * {@inheritDoc}
     */
    public function getGroups()
    {
        return $this->groups;
    }

    /**
     * {@inheritDoc}
     */
    public function setGroups(array $groups)
    {
        $this->groups = $groups;
    }

    /**
     * {@inheritDoc}
     */
    public function inGroup($group)
    {
        return in_array($group, $this->groups);
    }
}
