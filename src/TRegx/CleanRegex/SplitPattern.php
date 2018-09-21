<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\SafeRegex\preg;

class SplitPattern
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

    /**
     * @return string[]
     */
    public function ex(): array
    {
        return preg::split($this->pattern->pattern, $this->subject);
    }

    /**
     * @return string[]
     */
    public function inc(): array
    {
        return preg::split($this->pattern->pattern, $this->subject, -1, PREG_SPLIT_DELIM_CAPTURE);
    }
}
