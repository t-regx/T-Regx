<?php
namespace Test\Fakes\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\FlatMapStrategy;
use TRegx\CleanRegex\Internal\Nested;

class ReverseFlatMap implements FlatMapStrategy
{
    /** @var ArrayMergeStrategy */
    private $strategy;

    public function __construct()
    {
        $this->strategy = new ArrayMergeStrategy();
    }

    public function flatten(Nested $nested): array
    {
        return \array_reverse($this->strategy->flatten($nested));
    }
}
