<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class MatchesPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function matches(): bool
    {
        return preg::match($this->pattern->pattern, $this->subject) === 1;
    }

    public function fails(): bool
    {
        return !$this->matches();
    }
}
