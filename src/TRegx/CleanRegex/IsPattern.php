<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\PatternVerifier;

class IsPattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(InternalPattern $pattern)
    {
        $this->pattern = $pattern;
    }

    public function valid(): bool
    {
        return (new ValidPattern($this->pattern->originalPattern))->isValid();
    }

    public function usable(): bool
    {
        return (new ValidPattern($this->pattern->pattern))->isValid();
    }

    public function delimitered(): bool
    {
        (new PatternVerifier($this->pattern->pattern))->verify();
        return (new DelimiterParser())->isDelimitered($this->pattern->originalPattern);
    }
}
