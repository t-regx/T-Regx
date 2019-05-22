<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\Pattern;
use function array_map;
use function is_string;

class CompositePatternMapper
{
    /** @var (Pattern|string)[] */
    private $patterns;

    public function __construct(array $patterns)
    {
        $this->patterns = $patterns;
    }

    public function createPatterns(): array
    {
        return array_map(function ($pattern) {
            return $this->map($pattern);
        }, $this->patterns);
    }

    /**
     * @param Pattern|string $pattern
     * @return InternalPattern
     */
    private function map($pattern): InternalPattern
    {
        return new InternalPattern($this->mapToString($pattern));
    }

    private function mapToString($pattern): string
    {
        if (is_string($pattern)) {
            return pattern($pattern)->delimiter();
        }
        if ($pattern instanceof Pattern) {
            return $pattern->delimiter();
        }
        throw $this->throwInvalidPatternType($pattern);
    }

    private function throwInvalidPatternType($pattern): InvalidArgumentException
    {
        $type = (new StringValue($pattern))->getString();
        return new InvalidArgumentException("CompositePattern accepts only type Pattern or string, but $type given");
    }
}
