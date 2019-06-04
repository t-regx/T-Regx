<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

class IdentityMapper implements GroupMapper
{
    public function map(string $occurrence): ?string
    {
        return $occurrence;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
