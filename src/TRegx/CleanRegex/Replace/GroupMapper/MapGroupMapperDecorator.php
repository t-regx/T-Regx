<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

class MapGroupMapperDecorator implements GroupMapper
{
    /** @var GroupMapper */
    private $mapper;
    /** @var callable */
    private $mappingFunction;

    public function __construct(GroupMapper $mapper, callable $mappingFunction)
    {
        $this->mapper = $mapper;
        $this->mappingFunction = $mappingFunction;
    }

    public function map(?string $occurrence): ?string
    {
        $occurrence = $this->mapper->map($occurrence);
        if ($occurrence === null) {
            return null;
        }
        $mapper = $this->mappingFunction;
        return $mapper($occurrence);
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
        $this->mapper->useExceptionValues($occurrence, $nameOrIndex, $match);
    }
}
