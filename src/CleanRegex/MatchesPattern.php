<?php
namespace CleanRegex;

use CleanRegex\Internal\Pattern;
use SafeRegex\preg;

class MatchesPattern
{
    /** @var Pattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(Pattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function matches(): bool
    {
        return preg::match($this->pattern->pattern, $this->subject) === 1;
    }
}
