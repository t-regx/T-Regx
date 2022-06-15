<?php
namespace TRegx\CleanRegex\Internal\Match\Flat;

interface FlatFunction
{
    public function flatMap(array $values): array;

    public function apply($value): array;

    public function mapKey($key);
}
