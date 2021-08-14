<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

interface DetailGroupMapper extends GroupMapper
{
    public function useExceptionValues(string $occurrence, GroupKey $group, string $match): void;
}
