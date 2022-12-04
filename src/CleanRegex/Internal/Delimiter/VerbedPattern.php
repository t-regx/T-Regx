<?php
namespace TRegx\CleanRegex\Internal\Delimiter;

use TRegx\CleanRegex\Internal\PatternPrefix;

class VerbedPattern
{
    /** @var string */
    private $pattern;
    /** @var int */
    private $prefixLength;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
        $this->prefixLength = $this->prefixLength(new PatternPrefix($pattern));
    }

    private function prefixLength(PatternPrefix $patternPrefix): int
    {
        return \array_sum(\array_map('\strLen', $patternPrefix->pcreVerbs()));
    }

    public function verbs(): string
    {
        return \subStr($this->pattern, 0, $this->prefixLength);
    }

    public function expression(): string
    {
        return \subStr($this->pattern, $this->prefixLength);
    }
}
