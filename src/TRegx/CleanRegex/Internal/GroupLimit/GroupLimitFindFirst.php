<?php
namespace TRegx\CleanRegex\Internal\GroupLimit;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\LazyRawWithGroups;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\NotMatchedGroupOptional;
use TRegx\CleanRegex\Match\FindFirst\Optional;
use TRegx\CleanRegex\Match\Groups\Strategy\GroupVerifier;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;

class GroupLimitFindFirst
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var GroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->groupVerifier = new MatchAllGroupVerifier($this->base->getPattern());
    }

    public function getOptionalForGroup(callable $consumer): Optional
    {
        $first = $this->base->matchOffset();
        if ($this->matched($first)) {
            return $this->matchedOptional($first, $consumer);
        }
        if ($this->groupExists()) {
            return $this->notMatchedOptional($first);
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function matched(RawMatchOffset $first): bool
    {
        return $first->hasGroup($this->nameOrIndex) && $first->getGroup($this->nameOrIndex) !== null;
    }

    private function groupExists(): bool
    {
        return $this->groupVerifier->groupExists($this->nameOrIndex);
    }

    private function matchedOptional(RawMatchOffset $match, callable $consumer): MatchedOptional
    {
        return new MatchedOptional($consumer($this->facade($match)->createGroup($match)));
    }

    private function facade(RawMatchOffset $match): GroupFacade
    {
        return new GroupFacade($match, $this->base, $this->nameOrIndex,
            new MatchGroupFactoryStrategy(),
            new LazyMatchAllFactory($this->base));
    }

    private function notMatchedOptional(RawMatchOffset $first): NotMatchedGroupOptional
    {
        if ($first->matched()) {
            return $this->notMatched(GroupNotMatchedException::class, new FirstGroupMessage($this->nameOrIndex));
        }
        return $this->notMatched(SubjectNotMatchedException::class, new FirstGroupSubjectMessage($this->nameOrIndex));
    }

    private function notMatched(string $exception, NotMatchedMessage $message): NotMatchedGroupOptional
    {
        return new NotMatchedGroupOptional(
            new NotMatchedOptionalWorker(
                $message,
                $this->base,
                new NotMatched(new LazyRawWithGroups($this->base), $this->base)),
            $exception
        );
    }
}
