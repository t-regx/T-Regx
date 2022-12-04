<?php
namespace TRegx\CleanRegex\Internal\Prepared\Pattern;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

class SubpatternFlagsStringPattern implements StringPattern
{
    /** @var string */
    private $pattern;
    /** @var SubpatternFlags */
    private $flags;

    public function __construct(string $pattern, SubpatternFlags $flags)
    {
        $this->pattern = $pattern;
        $this->flags = $flags;
    }

    public function pattern(): string
    {
        return $this->pattern;
    }

    public function subpatternFlags(): SubpatternFlags
    {
        return $this->flags;
    }
}
