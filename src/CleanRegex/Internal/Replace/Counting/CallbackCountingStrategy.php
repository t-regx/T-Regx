<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;

class CallbackCountingStrategy implements CountingStrategy
{
    /** @var callback */
    private $countReceiver;
    /** @var Subject */
    private $subject;

    public function __construct(callable $countReceiver, Subject $subject)
    {
        $this->countReceiver = $countReceiver;
        $this->subject = $subject;
    }

    public function count(int $replaced, GroupAware $groupAware): void
    {
        ($this->countReceiver)($replaced, new PatternStructure($this->subject, $groupAware));
    }
}
