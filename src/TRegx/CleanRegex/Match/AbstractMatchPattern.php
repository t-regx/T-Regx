<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use Countable;
use EmptyIterator;
use Iterator;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoFirstElementFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchIntMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchFirst;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchStream;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\Optional;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

abstract class AbstractMatchPattern implements MatchPatternInterface, PatternLimit, Countable
{
    /** @var Base */
    protected $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    public function all(): array
    {
        return $this->base->matchAll()->getTexts();
    }

    /**
     * @param null|callable $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null)
    {
        return (new MatchFirst($this->base))->invoke($consumer);
    }

    public function only(int $limit): array
    {
        return (new MatchOnly($this->base, $limit))->get();
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->getMatchObjects() as $object) {
            $consumer($object);
        }
    }

    public function map(callable $mapper): array
    {
        return \array_map($mapper, $this->getMatchObjects());
    }

    public function flatMap(callable $mapper): array
    {
        return (new FlatMapper($this->getMatchObjects(), $mapper))->get();
    }

    public function distinct(): array
    {
        return \array_values(\array_unique($this->all()));
    }

    /**
     * @param callable $consumer
     * @return Optional
     */
    public function findFirst(callable $consumer): Optional
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->matched()) {
            $result = $consumer($matches->getFirstMatchObject(new MatchObjectFactory($this->base, 1, $this->base->getUserData())));
            return new MatchedOptional($result);
        }
        return new NotMatchedOptional(
            new NotMatchedOptionalWorker(
                new FirstMatchMessage(),
                $this->base,
                new NotMatched($matches, $this->base))
        );
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return new GroupLimit($this->base, $nameOrIndex,
            new MatchOffsetLimit($this->base, $nameOrIndex, false));
    }

    public function offsets(): MatchOffsetLimit
    {
        return new MatchOffsetLimit($this->base, 0, true);
    }

    abstract public function count(): int;

    public function iterator(): Iterator
    {
        $objects = $this->getMatchObjects();
        if (empty($objects)) {
            return new EmptyIterator();
        }
        return new ArrayIterator($objects);
    }

    public function filter(callable $predicate): FilteredMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator($this->base, new Predicate($predicate)));
    }

    public function fluent(): FluentMatchPattern
    {
        $switcher = new BaseStream($this->base);
        return new FluentMatchPattern(
            new MatchStream($switcher, $this->base, $this->base->getUserData(), $switcher),
            new NotMatchedFluentOptionalWorker(new NoFirstElementFluentMessage(), $this->base->getSubject())
        );
    }

    public function asInt(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new IntStream(new BaseStream($this->base)),
            new NotMatchedFluentOptionalWorker(new FirstMatchIntMessage(), $this->base->getSubject())
        );
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupByPattern
     */
    public function groupBy($nameOrIndex): GroupByPattern
    {
        return new GroupByPattern($this->base, $nameOrIndex);
    }

    /**
     * @return Match[]
     */
    protected function getMatchObjects(): array
    {
        $factory = new MatchObjectFactory($this->base, -1, $this->base->getUserData());
        return $this->base->matchAllOffsets()->getMatchObjects($factory);
    }
}
