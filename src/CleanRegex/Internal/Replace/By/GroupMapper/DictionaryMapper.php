<?php
namespace TRegx\CleanRegex\Internal\Replace\By\GroupMapper;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Type;
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
        foreach ($map as $occurrence => $replacement) {
            $this->validateOccurrence($occurrence);
            $this->validateReplacement($replacement);
        }
    }

    private function validateOccurrence($occurrence): void
    {
        if (!\is_string($occurrence)) {
            $value = Type::asString($occurrence);
            throw new InvalidArgumentException("Invalid replacement map key. Expected string, but $value given");
        }
    }

    private function validateReplacement($replacement): void
    {
        if (!\is_string($replacement)) {
            $value = Type::asString($replacement);
            throw new InvalidArgumentException("Invalid replacement map value. Expected string, but $value given");
        }
    }
}
