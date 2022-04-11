<?php
namespace TRegx\CleanRegex\Internal;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class GroupEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var NotMatched */
    private $notMatched;
    /** @var GroupKey */
    private $group;

    public function __construct(GroupAware $groupAware, Subject $subject, GroupKey $group)
    {
        $this->notMatched = new NotMatched($groupAware, $subject);
        $this->group = $group;
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function orThrow(Throwable $throwable = null): void
    {
        if ($throwable === null) {
            throw GroupNotMatchedException::forFirst($this->group);
        }
        throw $throwable;
    }
}
