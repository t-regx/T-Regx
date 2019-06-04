<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

interface GroupMapper
{
    public function map(string $occurrence): ?string;

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void;
}
