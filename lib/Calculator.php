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
 * Calculator.
 *
 * @author Daniel Leech <daniel@dantleech.com>
 */
class Calculator
{

    /**
     * Return the sum of all the given values.
     *
     * @param array $values
     * @return mixed
     */
    public static function sum(array $values = array())
    {
        $sum = 0;
        foreach (self::getValues($values) as $value) {
            $sum += $value;
        }

        return $sum;
    }

    /**
     * Return the lowest value contained within the given values
     *
     * @param array $values
     * @return mixed
     */
    public static function min(array $values = array())
    {
        $min = null;
        foreach (self::getValues($values) as $value) {
            if (null === $min || $value < $min) {
                $min = $value;
            }
        }

        return $min;
    }

    /**
     * Return the highest value contained within the given values
     *
     * @param array $values
     * @return mixed
     */
    public static function max(array $values = array())
    {
        $max = null;
        foreach (self::getValues($values) as $value) {
            if (null === $max || $value > $max) {
                $max = $value;
            }
        }

        return $max;
    }

    /**
     * Return the average value of the given values
     *
     * @param array $values
     * @return mixed
     */
    public static function avg(array $values = array())
    {
        if (empty($values)) {
            return 0;
        }

        $sum = self::sum($values);
        $count = count($values);

        return $sum / $count;
    }

    /**
     * Return the median value of the given values
     *
     * @param array $values
     * @param boolean $ceil
     * @return mixed
     */
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

    private static function getValues(array $values)
    {
        $result = array();
        foreach ($values as $value) {
            if ($value instanceof Cell) {
                $result[] = $value->getValue();
                continue;
            }

            if ($value instanceof Cellular) {
                foreach ($value->getValues() as $cellValue) {
                    $result[] = $cellValue;
                }
                continue;
            }

            if (is_numeric($value)) {
                $result[] = $value;
                continue;
            }

            throw new \InvalidArgumentException(sprintf(
                'Values must be either of type Cellular, Cell or they must be numeric. Got "%s"',
                is_object($value) ? get_class($value) : gettype($value)
            ));
        }

        return $result;
    }
}
