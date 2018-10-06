<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;

class InternalPattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    public $originalPattern;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = (new Delimiterer(new DelimiterParser(new FlagsValidator())))->delimiter($pattern) . $flags;
        $this->originalPattern = $pattern;
    }
}
