<?php
namespace TRegx\CleanRegex\Internal\Prepared\Pattern;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

class EmptyFlagPattern implements StringPattern
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern)
    {
        $this->pattern = $pattern;
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    public function subpatternFlags(): SubpatternFlags
    {
        return SubpatternFlags::empty();
    }
}
