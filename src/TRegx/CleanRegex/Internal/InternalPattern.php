<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Delimiter\Delimiterer;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\IdentityStrategy;
use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreIdentityStrategy;

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
        return new self((new Delimiterer(new IdentityStrategy()))->delimiter($pattern) . $flags, $pattern);
    }

    public static function pcre(string $pattern): InternalPattern
    {
        return new self((new Delimiterer(new PcreIdentityStrategy()))->delimiter($pattern), $pattern);
    }
}
