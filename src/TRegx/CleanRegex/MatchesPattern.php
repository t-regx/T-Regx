<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\SafeRegex\preg;

class MatchesPattern
{
    /** @var InternalPattern */
    private $pattern;
    /** @var Subjectable */
    private $subjectable;

    public function __construct(InternalPattern $pattern, Subjectable $subjectable)
    {
        $this->pattern = $pattern;
        $this->subjectable = $subjectable;
    }

    public function matches(): bool
    {
        return preg::match($this->pattern->pattern, $this->subjectable->getSubject()) === 1;
    }

    public function fails(): bool
    {
        return !$this->matches();
    }
}
