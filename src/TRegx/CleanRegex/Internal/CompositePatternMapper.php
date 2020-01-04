<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\PatternInterface;

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
        return \array_map([$this, 'mapToString'], $this->patterns);
    }

    private function mapToString($pattern): InternalPattern
    {
        if (\is_string($pattern)) {
            return InternalPattern::standard($pattern);
        }
        if ($pattern instanceof PatternInterface) {
            return InternalPattern::pcre($pattern->delimiter());
        }
        throw $this->throwInvalidPatternType($pattern);
    }

    private function throwInvalidPatternType($pattern): InvalidArgumentException
    {
        $type = Type::asString($pattern);
        return new InvalidArgumentException("CompositePattern accepts only type Pattern or string, but $type given");
    }
}
