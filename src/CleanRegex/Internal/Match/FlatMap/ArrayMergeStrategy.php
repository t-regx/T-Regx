<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Arrays;

class ArrayMergeStrategy implements FlatMapStrategy
{
    public function flatten(array $arrays): array
    {
        return Arrays::flatten($arrays);
    }
}
