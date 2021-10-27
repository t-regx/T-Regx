<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched\FromFirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class GroupEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var NotMatched */
    private $notMatched;
    /** @var Rejection */
    private $rejection;

    public function __construct(GroupAware $groupAware, Subject $subject, GroupKey $group)
    {
        $this->notMatched = new NotMatched($groupAware, $subject);
        $this->rejection = new Rejection($subject, GroupNotMatchedException::class, new FromFirstMatchMessage($group));
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        $this->rejection->throw($exceptionClassName);
    }
}
