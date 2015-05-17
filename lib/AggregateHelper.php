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
 * Utility class for aggregate functions.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class AggregateHelper
{
    public static function sum(array $values = array())
    {
        $sum = 0;
        foreach ($values as $value) {
            $sum += $value;
        }

        return $sum;
    }

    public static function min(array $values = array())
    {
        $min = null;
        foreach ($values as $value) {
            if (null === $min || $value < $min) {
                $min = $value;
            }
        }

        return $min;
    }

    public static function max(array $values = array())
    {
        $max = null;
        foreach ($values as $value) {
            if (null === $max || $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    public static function avg(array $values = array())
    {
        if (empty($values)) {
            return 0;
        }

        $sum = self::sum($values);
        $count = count($values);

        return $sum / $count;
    }

    public static function median(array $values = array(), $ceil = false)
    {
        if (empty($values)) {
            return 0;
        }

        sort($values);
        $medianIndex = count($values) / 2;

        if ($ceil) {
            $medianIndex = ceil($medianIndex);
        } else {
            $medianIndex = floor($medianIndex);
        }

        return $values[$medianIndex];
    }
}
