<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\AutomaticDelimiter;

class InternalPattern
{
    /** @var string */
    public $pattern;
    /** @var string */
    public $originalPattern;

    private function __construct(string $pattern, string $originalPattern)
    {
        $this->pattern = $pattern;
        $this->originalPattern = $originalPattern;
    }

    public static function standard(string $pattern, string $flags): InternalPattern
    {
        TrailingBackslash::throwIfHas($pattern);
        return new self(AutomaticDelimiter::standard($pattern, $flags), $pattern);
    }

    public static function pcre(string $pattern): InternalPattern
    {
        return new self($pattern, $pattern);
    }
}
