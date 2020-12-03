<?php
namespace Test\Utils;

use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Match\Details\Detail;

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
