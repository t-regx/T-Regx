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
use TRegx\CleanRegex\Internal\Match\FindFirst\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FindFirst\OptionalImpl;
use TRegx\CleanRegex\Internal\Match\Groups\Strategy\MatchAllGroupVerifier;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Model\LazyRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class GroupLimitFindFirst
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(Base $base, $nameOrIndex)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
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
        return (new MatchAllGroupVerifier($this->base->getPattern()))->groupExists($this->nameOrIndex);
    }

    private function matchedOptional(RawMatchOffset $match, callable $consumer): OptionalImpl
    {
        $facade = new GroupFacade($match, $this->base, $this->nameOrIndex,
            new MatchGroupFactoryStrategy(),
            new LazyMatchAllFactory($this->base));

        return new OptionalImpl($consumer($facade->createGroup($match)));
    }

    private function notMatchedOptional(RawMatchOffset $first): EmptyOptional
    {
        if ($first->matched()) {
            return $this->notMatched(GroupNotMatchedException::class, new FirstGroupMessage($this->nameOrIndex));
        }
        return $this->notMatched(SubjectNotMatchedException::class, new FirstGroupSubjectMessage($this->nameOrIndex));
    }

    private function notMatched(string $exception, NotMatchedMessage $message): EmptyOptional
    {
        return new EmptyOptional(
            new NotMatchedOptionalWorker(
                $message,
                $this->base,
                new NotMatched(new LazyRawWithGroups($this->base), $this->base)),
            $exception
        );
    }
}
