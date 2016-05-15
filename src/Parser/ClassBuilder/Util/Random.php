<?php namespace Pharser\Parser\ClassBuilder\Util;

/**
 * Utility functions for generating random data.
 *
 * @author Jack Price <jackprice@outlook.com>
 */
class Random
{
    /**
     * Generate a random string of length $length.
     *
     * @param int $length
     *
     * @return string
     */
    public static function string($length)
    {
        // Prepend a `_` to the string so that the result is always a valid PHP function / class name.
        return '_' . substr(
            str_shuffle('0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'),
            0,
            $length - 1
        );
    }
}
