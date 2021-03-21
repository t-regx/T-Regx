<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Replace\By\GroupMapper\DetailGroupMapper;
use TRegx\CleanRegex\Match\Details\Detail;

class ComputedMapper implements DetailGroupMapper
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        return ($this->mapper)($occurrence, $initialDetail);
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
