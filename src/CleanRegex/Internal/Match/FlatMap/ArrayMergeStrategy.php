<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Arrays;
use TRegx\CleanRegex\Internal\Nested;

class ArrayMergeStrategy implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        return Arrays::flatten($nested->asArray());
    }
}
