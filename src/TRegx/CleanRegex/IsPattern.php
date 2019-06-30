<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Delimiter\DelimiterParser;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

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

    public function delimited(): bool
    {
        preg::match($this->pattern->pattern, '');
        return (new DelimiterParser())->isDelimited($this->pattern->originalPattern);
    }
}
