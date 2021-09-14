<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\AsIntStreamWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\MatchStreamWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\NextStreamWorkerDecorator;
use TRegx\CleanRegex\Internal\Factory\Worker\OffsetsWorker;
use TRegx\CleanRegex\Internal\GroupKey\GroupIndex;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\MatchFirst;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchIntStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\MatchStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\OffsetLimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\MatchPatternHelpers;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Model\Match\RawMatchOffset;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Match\Details\Detail;
use TRegx\CleanRegex\Match\Details\MatchDetail;
use TRegx\CleanRegex\Match\Details\NotMatched;

abstract class AbstractMatchPattern implements MatchPatternInterface
{
    use MatchPatternHelpers;

    /** @var Base */
    protected $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, MatchAllFactory $factory)
    {
        $this->base = $base;
        $this->groupAware = new LightweightGroupAware($this->base->definition());
        $this->allFactory = $factory;
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
        $first = new MatchFirst($this->base, $this->allFactory);
        if ($consumer === null) {
            return $first->matchDetails()->text();
        }
        return $consumer($first->matchDetails());
    }

    public function findFirst(callable $consumer): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new PresentOptional($consumer($this->findFirstDetail($match)));
        }
        return new EmptyOptional(new NotMatchedOptionalWorker(
            new FirstMatchMessage(),
            $this->base,
            new NotMatched($this->groupAware, $this->base),
            SubjectNotMatchedException::class));
    }

    private function findFirstDetail(RawMatchOffset $match): Detail
    {
        $firstIndex = $match->getIndex();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, $firstIndex);
        return MatchDetail::create($this->base, $firstIndex, 1, $polyfill, $this->allFactory, $this->base->getUserData());
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
        foreach (\array_values($this->getDetailObjects()) as $index => $object) {
            $consumer($object, $index);
        }
    }

    public function map(callable $mapper): array
    {
        return \array_values(\array_map($mapper, $this->getDetailObjects()));
    }

    public function filter(callable $predicate): array
    {
        return \array_values(\array_map(static function (Detail $detail): string {
            return $detail->text();
        }, \array_filter($this->getDetailObjects(), [new Predicate($predicate, 'filter'), 'test'])));
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

    public function offsets(): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new OffsetLimitStream($this->base, new GroupIndex(0), $this->groupAware),
            new NextStreamWorkerDecorator(new MatchStreamWorker(), new OffsetsWorker($this->base)));
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

    public function asInt(int $base = null): FluentMatchPattern
    {
        return new FluentMatchPattern(
            new MatchIntStream(new StreamBase($this->base), new Number\Base($base)),
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
        return $factory->mapToDetailObjects($this->base->matchAllOffsets());
    }
}
