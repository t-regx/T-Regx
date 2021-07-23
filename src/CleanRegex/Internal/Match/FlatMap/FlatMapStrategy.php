<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Nested;

interface FlatMapStrategy
{
    public function flatten(Nested $nested): array;
}
