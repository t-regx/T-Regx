<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Group\FirstGroupMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstGroupSubjectMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitAll;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFirst;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Details\Group\GroupFacade;
use TRegx\CleanRegex\Internal\Match\Details\Group\MatchGroupFactoryStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\EagerMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedGroupOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\Groups\Strategy\MatchAllGroupVerifier;
use TRegx\CleanRegex\Match\Offset\OffsetLimit;

class GroupLimit implements PatternLimit
{
    /** @var GroupLimitAll */
    private $allFactory;
    /** @var GroupLimitFirst */
    private $firstFactory;
    /** @var MatchOffsetLimitFactory */
    private $offsetLimitFactory;
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;

    public function __construct(GroupLimitAll $allFactory, GroupLimitFirst $firstFactory, MatchOffsetLimitFactory $offsetLimitFactory, Base $base, $nameOrIndex)
    {
        $this->allFactory = $allFactory;
        $this->firstFactory = $firstFactory;
        $this->offsetLimitFactory = $offsetLimitFactory;
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
    }

    public function offsets(): OffsetLimit
    {
        return $this->offsetLimitFactory->create();
    }

    /**
     * @param callable $consumer
     * @return Optional
     */
    public function forFirst(callable $consumer): Optional
    {
        $first = $this->base->matchOffset();
        if ($first->hasGroup($this->nameOrIndex)) {
            $group = $first->getGroup($this->nameOrIndex);
            if ($group !== null) {
                $groupFacade = new GroupFacade($first, $this->base, $this->nameOrIndex,
                    new MatchGroupFactoryStrategy(),
                    new LazyMatchAllFactory($this->base));
                $matchGroup = $groupFacade->createGroup($first);
                return new MatchedOptional($consumer($matchGroup));
            }
        } else {
            $verifier = new MatchAllGroupVerifier($this->base->getPattern());
            if (!$verifier->groupExists($this->nameOrIndex)) {
                throw new NonexistentGroupException($this->nameOrIndex);
            }
        }
        [$default, $message] = $first->matched()
            ? [GroupNotMatchedException::class, new FirstGroupMessage($this->nameOrIndex)]
            : [SubjectNotMatchedException::class, new FirstGroupSubjectMessage($this->nameOrIndex)];

        return new NotMatchedGroupOptional(
            new NotMatchedOptionalWorker($message, $this->base, new NotMatched($first, $this->base)),
            $default
        );
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     */
    public function first(callable $consumer = null)
    {
        $first = $this->firstFactory->getFirstForGroup();
        if ($consumer === null) {
            return $first->getGroup($this->nameOrIndex);
        }
        $matchGroup = (new GroupFacade($first, $this->base, $this->nameOrIndex, new MatchGroupFactoryStrategy(), new LazyMatchAllFactory($this->base)))->createGroup($first);
        return $consumer($matchGroup);
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return $this->allFactory->getAllForGroup()->getGroupTexts($this->nameOrIndex);
    }

    /**
     * @param int $limit
     * @return (string|null)[]
     */
    public function only(int $limit): array
    {
        $matches = $this->allFactory->getAllForGroup();
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return \array_slice($matches->getGroupTexts($this->nameOrIndex), 0, $limit);
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->all());
    }

    /**
     * @param callable $mapper
     * @return mixed[]
     */
    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->getMatchGroupObjects());
    }

    /**
     * @param callable $mapper
     * @return mixed[]
     */
    public function flatMap(callable $mapper): array
    {
        return (new FlatMapper($this->getMatchGroupObjects(), $mapper))->get();
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->getMatchGroupObjects() as $group) {
            $consumer($group);
        }
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            $this->getMatchGroupObjects(),
            new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), $this->base->getSubject()));
    }

    private function getMatchGroupObjects(): array
    {
        $matches = $this->allFactory->getAllForGroup();
        $groupFacade = new GroupFacade($matches, $this->base, $this->nameOrIndex,
            new MatchGroupFactoryStrategy(),
            new EagerMatchAllFactory($matches));
        return $groupFacade->createGroups($matches);
    }
}
