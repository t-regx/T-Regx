<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;

class NoReplacementMapper implements GroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return null;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
