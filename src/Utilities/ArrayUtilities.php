<?php

namespace App\Utilities;

class ArrayUtilities
{
    /**
     * Return true if for all elements: e, $predicate(e) == true
     *
     * @param array $array
     * @param callable $predicate
     * @return false
     */
    public static function all(array $array, callable $predicate): bool
    {
        foreach ($array as $element) {
            if (!$predicate($element)) return false;
        }
        return true;
    }
}