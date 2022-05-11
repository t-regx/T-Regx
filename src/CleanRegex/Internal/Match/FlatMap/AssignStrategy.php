<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

use TRegx\CleanRegex\Internal\Nested;

class AssignStrategy implements FlatMapStrategy
{
    public function flatten(Nested $nested): array
    {
        $result = [];
        foreach ($nested->asArray() as $array) {
            foreach ($array as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }

    public function firstKey($key)
    {
        return $key;
    }
}
