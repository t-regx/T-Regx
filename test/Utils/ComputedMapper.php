<?php
namespace Test\Utils;

use TRegx\CleanRegex\Replace\GroupMapper\GroupMapper;

class ComputedMapper implements GroupMapper
{
    /** @var callable */
    private $mapper;

    function __construct(callable $mapper)
    {
        $this->mapper = $mapper;
    }

    public function map(string $subject): ?string
    {
        return call_user_func($this->mapper, $subject);
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
    }
}
