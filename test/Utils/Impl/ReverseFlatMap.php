<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Arrays;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;

class ReverseFlatMap implements FlatMapStrategy
{
    public function flatten(array $arrays): array
    {
        return array_reverse(Arrays::flatten($arrays));
    }
}
