<?php

namespace Danon\CleanRegex;

class Pattern
{
    /** @var string */
    private $pattern;

    public function __construct(string $pattern = null)
    {
        $this->pattern = $pattern;
    }
}
