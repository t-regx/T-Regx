<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Details\Detail;

class IdentityMapper implements DetailGroupMapper
{
    public function map(string $occurrence, Detail $initialDetail): string
    {
        return $occurrence;
    }

    // @codeCoverageIgnoreStart
    public function useExceptionValues(string $occurrence, GroupKey $group, string $match): void
    {
    }
    // @codeCoverageIgnoreEnd
}
