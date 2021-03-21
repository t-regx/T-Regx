<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;

class ThrowFlatMap implements FlatMapStrategy
{
    public function flatten(array $arrays): array
    {
        throw new \AssertionError("flatten() wasn't supposed to be called");
    }
}
