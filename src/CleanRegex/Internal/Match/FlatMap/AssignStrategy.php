<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

class AssignStrategy implements FlatMapStrategy
{
    public function flatten(array $arrays): array
    {
        $result = [];
        foreach ($arrays as $array) {
            foreach ($array as $key => $value) {
                $result[$key] = $value;
            }
        }
        return $result;
    }
}
