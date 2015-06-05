<?php

namespace DTL\Cellular;

interface GroupInterface
{
    /**
     * Return the groups to which this instance belongs.
     *
     * @return string[]
     */
    public function getGroups();

    /**
     * Set the groups to which this instance belongs.
     */
    public function setGroups(array $groups);

    /**
     * Return true if this instance is in the given group.
     *
     * @param string $group
     * @return boolean
     */
    public function inGroup($group);
}
