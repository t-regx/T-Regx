<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\Pattern;

class CompositePatternMapper
{
    /** @var (Pattern|string)[] */
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function create(): array
    {
        return array_map(function ($pattern) {
            return $this->map($pattern);
        }, $this->patterns);
    }

    private function map($pattern): string
    {
        if (is_string($pattern)) {
            return pattern($pattern)->delimitered();
        }
        if ($pattern instanceof Pattern) {
            return $pattern->delimitered();
        }
        throw $this->throwInvalidPatternType($pattern);
    }

    private function throwInvalidPatternType($pattern): InvalidArgumentException
    {
        $type = (new StringValue($pattern))->getString();
        return new InvalidArgumentException("CompositePattern accepts only type Pattern or string, but $type given");
    }
}
