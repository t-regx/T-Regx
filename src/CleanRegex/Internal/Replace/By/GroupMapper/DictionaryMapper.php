<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use TRegx\CleanRegex\Internal\InvalidArgument;
use TRegx\CleanRegex\Internal\Type\ValueType;
use TRegx\CleanRegex\Match\Details\Detail;

class DictionaryMapper implements GroupMapper
{
    /** @var array */
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
        $this->validateMap($map);
    }

    public function map(string $occurrence, Detail $initialDetail): ?string
    {
        if (\array_key_exists($occurrence, $this->map)) {
            return $this->map[$occurrence];
        }
        return null;
    }

    private function validateMap(array $map): void
    {
        foreach ($map as $replacement) {
            if (!\is_string($replacement)) {
                throw InvalidArgument::typeGiven("Invalid replacement map value. Expected string", new ValueType($replacement));
            }
        }
    }
}
