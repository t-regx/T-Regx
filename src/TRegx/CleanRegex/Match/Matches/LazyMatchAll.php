<?php
namespace TRegx\CleanRegex\Match\Matches;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class LazyMatchAll implements Matches
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    /** @var null|array */
    private $matches = null;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function getMatches(): array
    {
        if ($this->matches === null) {
            $this->matches = $this->matchAll();
        }
        return $this->matches;
    }

    private function matchAll(): array
    {
        preg::match_all($this->pattern->pattern, $this->subject, $matches);
        return $matches;
    }
}
