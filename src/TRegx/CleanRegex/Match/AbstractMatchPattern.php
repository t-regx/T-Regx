<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use EmptyIterator;
use Iterator;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchAsArrayMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchIntMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Factory\FluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchFirst;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Match\Stream\AsArrayStream;
use TRegx\CleanRegex\Internal\Match\Stream\BaseStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchStream;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\LazyRawWithGroups;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Model\MatchObjectFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\Optional;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

abstract class AbstractMatchPattern implements MatchPatternInterface, PatternLimit
{
    /** @var Base */
    protected $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    abstract public function test(): bool;

    public function fails(): bool
    {
        return !$this->test();
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

    public function findFirst(callable $consumer): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new MatchedOptional($consumer($this->findFirstDetails($match)));
        }
        return new NotMatchedOptional(new NotMatchedOptionalWorker(new FirstMatchMessage(), $this->base, new NotMatched(new LazyRawWithGroups($this->base), $this->base)));
    }

    private function findFirstDetails(RawMatchOffset $match): Match
    {
        $allFactory = new LazyMatchAllFactory($this->base);
        return (new MatchObjectFactory($this->base, 1, $this->base->getUserData()))
            ->create(0, new GroupPolyfillDecorator($match, $allFactory, 0), $allFactory);
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

    public function getIterator(): Iterator
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
        $stream = new BaseStream($this->base);
        return new FluentMatchPattern(
            new MatchStream($stream, $this->base, $this->base->getUserData(), $stream),
            new FluentOptionalWorker(new FirstFluentMessage(), $this->base->getSubject())
        );
    }

    public function asInt(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new IntStream(new BaseStream($this->base)),
            new FluentOptionalWorker(new FirstMatchIntMessage(), $this->base->getSubject())
        );
    }

    public function asArray(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new AsArrayStream(new BaseStream($this->base), $this->base),
            new FluentOptionalWorker(new FirstMatchAsArrayMessage(), $this->base->getSubject())
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
