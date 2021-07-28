<?php
namespace TRegx\CleanRegex\Internal\Factory;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subjectable;

class GroupExceptionFactory
{
    /** @var Subjectable */
    private $subject;
    /** @var GroupKey */
    private $group;

    public function __construct(Subjectable $subject, GroupKey $group)
    {
        $this->subject = $subject;
        $this->group = $group;
    }

    public function create(string $method): GroupNotMatchedException
    {
        return GroupNotMatchedException::forMethod($this->subject, $this->group, $method);
    }
}
