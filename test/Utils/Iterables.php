<?php
namespace Test\Utils;

use ArrayIterator;
use Iterator;
use MultipleIterator;

class Iterables
{
    public static function zip(array ...$iterables): Iterator
    {
        $iterator = new MultipleIterator();
        foreach ($iterables as $iterable) {
            $iterator->attachIterator(new ArrayIterator($iterable));
        }
        return $iterator;
    }
}
