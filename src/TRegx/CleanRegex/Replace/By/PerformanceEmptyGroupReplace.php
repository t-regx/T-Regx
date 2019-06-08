<?php
namespace TRegx\CleanRegex\Replace\By;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\SafeRegex\preg;

class PerformanceEmptyGroupReplace
{
    /** @var InternalPattern */
    private $pattern;
    /** @var Subjectable */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(InternalPattern $pattern, Subjectable $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function replaceWithGroupOrEmpty(int $index): string
    {
        return preg::replace(
            $this->pattern->pattern,
            "\\$index",
            $this->subject->getSubject(),
            $this->limit);
    }
}
