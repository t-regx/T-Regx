<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Nested;

class ThrowFlatMap implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        throw new \AssertionError("flatten() wasn't supposed to be called");
    }
}
