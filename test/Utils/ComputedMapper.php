<?php
namespace Test\Utils;

use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;

class ComputedMapper implements GroupMapper
{
    /** @var callable */
    private $mapper;

    public function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function map(string $subject, Detail $initialDetail): ?string
    {
        return call_user_func($this->mapper, $subject, $initialDetail);
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
