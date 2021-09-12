<?php
namespace Test\Fakes\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Match\Details\Detail;

class NoReplacementMapper implements DetailGroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return null;
    }

    public function useExceptionValues(string $occurrence, GroupKey $group, string $match): void
    {
    }
}
