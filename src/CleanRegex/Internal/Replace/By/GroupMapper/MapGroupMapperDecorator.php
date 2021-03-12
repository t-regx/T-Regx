<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Match\Details\Detail;

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

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        $occurrence = $this->mapper->map($occurrence, $initialDetail);
        if ($occurrence === null) {
            return null;
        }
        return ($this->mappingFunction)($occurrence);
    }
}
