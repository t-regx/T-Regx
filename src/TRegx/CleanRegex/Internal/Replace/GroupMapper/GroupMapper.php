<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

interface GroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string;

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void;
}
