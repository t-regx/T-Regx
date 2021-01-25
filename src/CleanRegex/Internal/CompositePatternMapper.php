<?php
namespace TRegx\CleanRegex\Internal;

use InvalidArgumentException;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
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
        try {
            return \array_map([$this, 'mapToString'], $this->patterns);
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
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
