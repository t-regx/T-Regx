<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\SafeRegex\preg;

class CountPattern
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

    public function count(): int
    {
        return preg::match_all($this->pattern->pattern, $this->subjectable->getSubject());
    }
}
