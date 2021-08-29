<?php
namespace TRegx\CleanRegex\Internal\Factory;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subject;

class GroupExceptionFactory
{
    /** @var Subject */
    private $subject;
    /** @var GroupKey */
    private $group;

    public function __construct(Subject $subject, GroupKey $group)
    {
        $this->subject = $subject;
        $this->group = $group;
    }

    public function create(string $method): GroupNotMatchedException
    {
        return GroupNotMatchedException::forMethod($this->subject, $this->group, $method);
    }
}
