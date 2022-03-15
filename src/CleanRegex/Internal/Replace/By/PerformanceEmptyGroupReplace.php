<?php
namespace TRegx\CleanRegex\Internal\Replace\By;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class PerformanceEmptyGroupReplace
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(Definition $definition, Subject $subject, int $limit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function replaceWithGroupOrEmpty(int $index): string
    {
        /**
         * T-Regx provides 5 strategies, when replacing occurrence with a
         * group that is unmatched: constant, ignore it, leave it empty,
         * invoke a callback or throw.
         *
         * Returning constant, ignoring, calling back or throwing requires
         * {@see preg_replace_callback}. However, replacing a group that's indexed
         * with an empty string, is possible with just {@see preg_replace}.
         */
        return preg::replace(
            $this->definition->pattern,
            "\\$index",
            $this->subject,
            $this->limit);
    }
}
