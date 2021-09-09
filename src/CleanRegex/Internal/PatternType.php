<?php
namespace TRegx\CleanRegex\Internal;

class PatternType implements Type
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function __toString(): string
    {
        return "pattern ($this->pattern)";
    }
}
