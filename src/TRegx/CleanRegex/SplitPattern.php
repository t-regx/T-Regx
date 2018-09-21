<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\Pattern as InternalPattern;
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

    public function split(): array
    {
        return preg::split($this->pattern->pattern, $this->subject);
    }

    public function separate(): array
    {
        return preg::split($this->pattern->pattern, $this->subject, -1, PREG_SPLIT_DELIM_CAPTURE);
    }
}
