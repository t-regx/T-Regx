<?php
namespace Test\Utils;

use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;

class NoReplacementMapper implements GroupMapper
{
    public function map(string $occurrence): ?string
    {
        return null;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
