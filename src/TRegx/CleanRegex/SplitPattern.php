<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Split\FilteredSplitPattern;
use TRegx\SafeRegex\preg;

class SplitPattern
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

    public function filter(): FilteredSplitPattern
    {
        return new FilteredSplitPattern($this->pattern, $this->subjectable);
    }

    /**
     * @return string[]
     */
    public function ex(): array
    {
        return $this->split(false);
    }

    /**
     * @return string[]
     */
    public function inc(): array
    {
        return $this->split(true);
    }

    private function split(bool $includeDelimiter): array
    {
        $flag = $includeDelimiter ? PREG_SPLIT_DELIM_CAPTURE : 0;
        $subject = $this->subjectable->getSubject();
        return preg::split($this->pattern->pattern, $subject, -1, $flag);
    }
}
