<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\GroupMapper;
use TRegx\CleanRegex\Match\Details\Detail;

class IgnoreMessages implements DetailGroupMapper
{
    /** @var GroupMapper */
    private $mapper;

    public function __construct(GroupMapper $mapper)
    {
        $this->mapper = $mapper;
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return $this->mapper->map($occurrence, $initialDetail);
    }

    public function useExceptionValues(string $occurrence, GroupKey $group, string $match): void
    {
    }
}
