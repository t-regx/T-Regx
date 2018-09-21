<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;

class InternalPattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    public $originalPattern;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = (new Delimiterer())->delimiter($pattern) . $flags;
        $this->originalPattern = $pattern;
    }
}
