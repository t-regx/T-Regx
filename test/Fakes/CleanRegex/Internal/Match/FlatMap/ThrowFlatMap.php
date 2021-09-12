<?php
namespace Test\Fakes\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Nested;

class ThrowFlatMap implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        throw new \AssertionError("flatten() wasn't supposed to be called");
    }
}
