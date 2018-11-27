<?php
namespace TRegx\CleanRegex\Split;

use TRegx\CleanRegex\Exception\CleanRegex\MissingSplitDelimiterGroupException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Groups\Descriptor;
use TRegx\SafeRegex\preg;

class FilteredSplitPattern implements SplitPatternInterface
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
        $this->validateDelimiterGroupExists();
        return $this->split(true);
    }

    private function split(bool $includeDelimiter): array
    {
        $flag = $includeDelimiter ? PREG_SPLIT_DELIM_CAPTURE : 0;
        $subject = $this->subjectable->getSubject();
        return preg::split($this->pattern->pattern, $subject, -1, $flag | PREG_SPLIT_NO_EMPTY);
    }

    private function validateDelimiterGroupExists(): void
    {
        $descriptor = new Descriptor($this->pattern);
        if (!$descriptor->hasAnyGroup()) {
            throw new MissingSplitDelimiterGroupException();
        }
    }
}
