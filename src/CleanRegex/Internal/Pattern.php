<?php
namespace CleanRegex\Internal;

use CleanRegex\Internal\Delimiter\Delimiterer;

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
        $this->pattern = (new Delimiterer())->delimiter($pattern);
        $this->flags = $flags;
        $this->originalPattern = $pattern;
    }
}
