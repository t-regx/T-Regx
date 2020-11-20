<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

class IdentityMapper implements GroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): string
    {
        return $occurrence;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
