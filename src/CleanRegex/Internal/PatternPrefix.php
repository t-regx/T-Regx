<?php
namespace TRegx\CleanRegex\Internal;

class PatternPrefix
{
    /** @var array */
    private $matches;

    public function __construct(string $pattern)
    {
        \preg_match_all("/\(\*([A-Z_]+)=?[0-9]*\)/A", $pattern, $this->matches);
    }

    public function pcreVerbs(): array
    {
        return $this->matches[0];
    }

    public function internalOptions(): array
    {
        return $this->matches[1];
    }
}
