<?php
namespace TRegx\CleanRegex\Internal\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\SafeRegex\preg;

class Counter
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var int */
    private $pregLimit;

    public function __construct(Definition $definition, Subject $subject, int $pregLimit)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->pregLimit = $pregLimit;
    }

    public function limitedAmount(): int
    {
        preg::replace($this->definition->pattern, '', $this->subject->asString(), $this->pregLimit, $count);
        return $count;
    }
}
