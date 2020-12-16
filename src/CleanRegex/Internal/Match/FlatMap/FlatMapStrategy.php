<?php
namespace TRegx\CleanRegex\Internal\Match\FlatMap;

interface FlatMapStrategy
{
    public function flatten(array $arrays): array;
}
