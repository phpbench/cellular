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
     *
     * @param string[] $groups
     */
    public function setGroups(array $groups);

    /**
     * Return true if this instance is in the given group.
     *
     * @param string $group
     *
     * @return bool
     */
    public function inGroup($group);
}
