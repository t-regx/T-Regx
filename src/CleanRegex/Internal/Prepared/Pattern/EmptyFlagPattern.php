<?php
namespace TRegx\CleanRegex\Internal\Prepared\Pattern;

use TRegx\CleanRegex\Internal\Flags;

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

    public function flags(): Flags
    {
        return Flags::empty();
    }
}
