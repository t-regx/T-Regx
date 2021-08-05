<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\AsIntStreamWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\MatchStreamWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\NextStreamWorkerDecorator;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\FindFirst\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FindFirst\OptionalImpl;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchFirst;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\Stream\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamBase;
use TRegx\CleanRegex\Internal\MatchPatternHelpers;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\CleanRegex\Match\Details\NotMatched;

abstract class AbstractMatchPattern implements MatchPatternInterface, PatternLimit
{
    use MatchPatternHelpers;

    /** @var Base */
    protected $base;
    /** @var GroupAware */
    private $groupAware;

    public function __construct(Base $base)
    {
        $this->base = $base;
        $this->groupAware = new LightweightGroupAware($this->base->getPattern());
    }

    abstract public function test(): bool;

    public function fails(): bool
    {
        return !$this->test();
    }

    public function all(): array
    {
        return \array_values($this->base->matchAll()->getTexts());
    }

    /**
     * @param null|callable $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null)
    {
        $first = new MatchFirst($this->base, new LazyMatchAllFactory($this->base->getUnfilteredBase()));
        if ($consumer === null) {
            return $first->matchDetails()->text();
        }
        return $consumer($first->matchDetails());
    }

    public function findFirst(callable $consumer): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new OptionalImpl($consumer($this->findFirstDetail($match)));
        }
        return new EmptyOptional(new NotMatchedOptionalWorker(
            new FirstMatchMessage(),
            $this->base,
            new NotMatched($this->groupAware, $this->base),
            SubjectNotMatchedException::class));
    }

    private function findFirstDetail(RawMatchOffset $match): Detail
    {
        $allFactory = new LazyMatchAllFactory($this->base->getUnfilteredBase());
        $firstIndex = $match->getIndex();

        return new MatchDetail(
            $this->base,
            $firstIndex,
            1,
            new GroupPolyfillDecorator(new FalseNegative($match), $allFactory, $firstIndex),
            $allFactory,
            $this->base->getUserData());
    }

    public function only(int $limit): array
    {
        return (new MatchOnly($this->base, $limit))->get();
    }

    public function nth(int $index): string
    {
        if ($index < 0) {
            throw new InvalidArgumentException("Negative nth: $index");
        }
        $texts = \array_values($this->base->matchAll()->getTexts());
        if (\array_key_exists($index, $texts)) {
            return $texts[$index];
        }
        if (empty($texts)) {
            throw SubjectNotMatchedException::forNth($this->base, $index);
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->getDetailObjects() as $object) {
            $consumer($object);
        }
    }

    public function map(callable $mapper): array
    {
        return \array_values(\array_map($mapper, $this->getDetailObjects()));
    }

    public function filter(callable $predicate): array
    {
        return \array_values(\array_map(static function (Detail $detail) {
            return $detail->text();
        }, \array_filter($this->getDetailObjects(), $predicate)));
    }

    public function flatMap(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMap');;
        return (new ArrayMergeStrategy())->flatten($function->map($this->getDetailObjects()));
    }

    public function flatMapAssoc(callable $mapper): array
    {
        $function = new FlatFunction($mapper, 'flatMapAssoc');;
        return (new AssignStrategy())->flatten($function->map($this->getDetailObjects()));
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
        return new GroupLimit($this->base, $this->groupAware, GroupKey::of($nameOrIndex));
    }

    public function offsets(): OffsetLimit
    {
        return new OffsetLimit($this->base, $this->groupAware, new GroupIndex(0), true);
    }

    abstract public function count(): int;

    public function getIterator(): Iterator
    {
        return new ArrayIterator(\array_values($this->getDetailObjects()));
    }

    public abstract function remaining(callable $predicate): RemainingMatchPattern;

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchStream(new StreamBase($this->base), $this->base, $this->base->getUserData(), new LazyMatchAllFactory($this->base)),
            new MatchStreamWorker());
    }

    public function asInt(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchIntStream(new StreamBase($this->base)),
            new NextStreamWorkerDecorator(new MatchStreamWorker(), new AsIntStreamWorker($this->base)));
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupByPattern
     */
    public function groupBy($nameOrIndex): GroupByPattern
    {
        return new GroupByPattern($this->base, GroupKey::of($nameOrIndex));
    }

    public function groupByCallback(callable $groupMapper): array
    {
        $result = [];
        foreach ($this->getDetailObjects() as $detail) {
            $key = $groupMapper($detail);
            $result[$key][] = $detail->text();
        }
        return $result;
    }

    /**
     * @return Detail[]
     */
    protected function getDetailObjects(): array
    {
        $factory = new DetailObjectFactory($this->base, $this->base->getUserData());
        return $this->base->matchAllOffsets()->getDetailObjects($factory);
    }
}
