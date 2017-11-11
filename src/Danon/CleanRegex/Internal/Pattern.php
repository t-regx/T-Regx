<?php
namespace Danon\CleanRegex\Internal;

class Pattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    private $flags;

    /** @var string */
    public $originalPattern;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = (new PatternDelimiterer())->delimiter($pattern);
        $this->flags = $flags;
        $this->originalPattern = $pattern;
    }
}
