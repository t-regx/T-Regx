<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Arrays;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Nested;

class ReverseFlatMap implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        return array_reverse(Arrays::flatten($nested->asArray()));
    }
}
