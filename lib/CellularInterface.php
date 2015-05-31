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

/**
 * Cellular interface
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
interface CellularInterface
{
    /**
     * Return the values of all the cells contained in this collection.
     *
     * @param array $groups
     * @return array
     */
    public function getValues(array $groups = array());

    /**
     * Map a function to each cell value in this collection.
     *
     * @param \Closure $closure
     * @param array $groups
     * @return array
     */
    public function mapValues(\Closure $closure, array $groups = array());

    /**
     * Return the cells with the given groups.
     *
     * If no groups are given then all cells will be returned.
     *
     * @param array $groups
     */
    public function getCells(array $groups = array());
}
