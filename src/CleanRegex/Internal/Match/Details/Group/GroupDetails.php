<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupKey\GroupSignature;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;

class GroupDetails
{
    /** @var GroupHandle */
    private $handle;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var GroupSignature */
    private $signature;

    public function __construct(GroupHandle $handle, GroupKey $group, MatchAllFactory $allFactory, GroupSignature $signature)
    {
        $this->handle = $handle;
        $this->group = $group;
        $this->allFactory = $allFactory;
        $this->signature = $signature;
    }

    public function group(): GroupKey
    {
        return $this->group;
    }

    public function all(): array
    {
        return \array_values($this->allFactory->getRawMatches()->getGroupTexts($this->handle->groupHandle($this->group)));
    }

    public function nameOrIndex()
    {
        return $this->group->nameOrIndex();
    }

    public function name(): ?string
    {
        return $this->signature->name();
    }

    public function index(): int
    {
        return $this->signature->index();
    }
}
