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
            return InternalPattern::pcre($pattern->delimited());
        }
        $type = Type::asString($pattern);
        throw new InvalidArgumentException("CompositePattern only accepts type PatternInterface or string, but $type given");
    }
}
