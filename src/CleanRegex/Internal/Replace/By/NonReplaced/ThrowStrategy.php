<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use Throwable;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Match\Detail;

class ThrowStrategy implements SubjectRs, MatchRs
{
    /** @var Throwable|null */
    private $throwable;
    /** @var GroupKey */
    private $group;

    public function __construct(?Throwable $throwable, GroupKey $group)
    {
        $this->throwable = $throwable;
        $this->group = $group;
    }

    public function substitute(): string
    {
        throw $this->throwable();
    }

    public function substituteGroup(Detail $detail): string
    {
        throw $this->throwable();
    }

    private function throwable(): Throwable
    {
        if ($this->throwable === null) {
            return GroupNotMatchedException::forReplacement($this->group);
        }
        return $this->throwable;
    }
}
