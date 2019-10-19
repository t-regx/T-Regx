<?php
namespace TRegx\CleanRegex\Replace\GroupMapper;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Type;

class DictionaryMapper implements GroupMapper
{
    /** @var array */
    private $map;

    public function __construct(array $map)
    {
        $this->map = $map;
        $this->validateMap($map);
    }

    public function map(string $occurrence): ?string
    {
        if (\array_key_exists($occurrence, $this->map)) {
            return $this->map[$occurrence];
        }
        return null;
    }

    public function useExceptionValues(string $occurrence, $nameOrIndex, string $match): void
    {
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
