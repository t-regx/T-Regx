<?php
namespace Test\Utils\Stream;

use Test\Utils\Functions;
use TRegx\CleanRegex\Match\Stream;
use TRegx\CleanRegex\Pattern;

class ArrayStream
{
    public static function empty(): Stream
    {
        return Pattern::of('Foo')
            ->match('Foo')
            ->stream()
            ->filter(Functions::constant(false));
    }

    public static function unmatched(): Stream
    {
        return Pattern::of('(*FAIL)')
            ->match('subject')
            ->stream();
    }

    public static function of(array $elements): Stream
    {
        return Pattern::of('Foo')
            ->search('Foo')
            ->stream()
            ->toMap(Functions::constant($elements));
    }
}
