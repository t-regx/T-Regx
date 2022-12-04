<?php
namespace TRegx\CleanRegex\Internal;

class PatternPrefix
{
    /** @var array */
    private $match;

    public function __construct(string $pattern)
    {
        \preg_match_all("/\(\*([A-Z_]+)=?[0-9]*\)/A", $pattern, $this->match);
    }

    public function pcreVerbs(): array
    {
        return $this->match[0];
    }

    public function internalOptions(): array
    {
        return $this->match[1];
    }
}
