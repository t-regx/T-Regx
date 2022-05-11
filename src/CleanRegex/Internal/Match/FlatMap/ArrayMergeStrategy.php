<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Nested;

class ArrayMergeStrategy implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        $array = $nested->asArray();
        if (empty($array)) {
            return [];
        }
        return \array_merge(...\array_map('array_values', $array));
    }

    public function firstKey($key): int
    {
        return 0;
    }
}
