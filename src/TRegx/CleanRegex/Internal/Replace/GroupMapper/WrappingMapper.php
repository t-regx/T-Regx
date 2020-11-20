<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

class WrappingMapper implements GroupMapper
{
    /** @var GroupMapper */
    private $first;
    /** @var Wrapper */
    private $mapperWrapper;

    public function __construct(GroupMapper $first, Wrapper $mapperWrapper)
    {
        $this->first = $first;
        $this->mapperWrapper = $mapperWrapper;
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return $this->mapperWrapper->map($this->first, $occurrence, $initialDetail);
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
        $this->first->useExceptionValues($occurrence, $nameOrIndex, $match);
    }
}
