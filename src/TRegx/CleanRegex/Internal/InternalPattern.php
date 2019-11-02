<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;

class InternalPattern
{
    /** @var string */
    public $pattern;

    /** @var string */
    public $originalPattern;

    private function __construct(string $pattern, $originalPattern)
    {
        $this->pattern = $pattern;
        $this->originalPattern = $originalPattern;
    }

    public static function standard(string $pattern, string $flags = ''): InternalPattern
    {
        return new self((new Delimiterer())->delimiter($pattern) . $flags, $pattern);
    }

    public static function pcre(string $pattern): InternalPattern
    {
        return new self($pattern, $pattern);
    }
}
