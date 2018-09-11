<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern as InternalPattern;
use SafeRegex\preg;

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
}
