<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Match\Details\Detail;

class NoReplacementMapper implements DetailGroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return null;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
