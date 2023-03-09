<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\Match\Amount;
use TRegx\CleanRegex\Internal\Match\ArrayFunction;
use TRegx\CleanRegex\Internal\Match\Flat\DictionaryFunction;
use TRegx\CleanRegex\Internal\Match\Flat\ListFunction;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\GroupedDetails;
use TRegx\CleanRegex\Internal\Match\Limit;
use TRegx\CleanRegex\Internal\Match\MatchItems;
use TRegx\CleanRegex\Internal\Match\MatchOnly;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\SearchBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\DetailStream;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Model\DetailObjectFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Model\LightweightGroupAware;
use TRegx\CleanRegex\Internal\Pcre\DeprecatedMatchDetail;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\CleanRegex\Internal\Pcre\Legacy\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Pcre\Legacy\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\MatchAllFactory;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Prime\MatchPrime;
use TRegx\CleanRegex\Internal\Pcre\Legacy\RawMatchOffset;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

class Matcher implements Structure, \Countable, \IteratorAggregate
{
    /** @var Subject */
    private $subject;
    /** @var Base */
    private $base;
    /** @var GroupAware */
    private $groupAware;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var MatchOnly */
    private $matchOnly;
    /** @var GroupNames */
    private $groupNames;
    /** @var MatchItems */
    private $matchItems;
    /** @var Amount */
    private $amount;
    /** @var GroupedDetails */
    private $grouped;
    /** @var DetailObjectFactory */
    private $factory;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->subject = $subject;
        $this->base = new ApiBase($definition, $subject);
        $this->groupAware = new LightweightGroupAware($definition);
        $this->allFactory = new LazyMatchAllFactory($this->base);
        $this->matchOnly = new MatchOnly($definition, $subject, $this->base);
        $this->groupNames = new GroupNames($this->groupAware);
        $this->matchItems = new MatchItems($this->base, $subject);
        $this->amount = new Amount(new SearchBase($definition, $subject));
        $this->grouped = new GroupedDetails($definition, $subject);
        $this->factory = new DetailObjectFactory($subject);
    }

    public function test(): bool
    {
        return $this->amount->atLeastOne();
    }

    public function fails(): bool
    {
        return $this->amount->none();
    }

    /**
     * @return Detail[]
     */
    public function all(): array
    {
        return $this->stream()->all();
    }

    public function first(): Detail
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return $this->firstDetail($match);
        }
        throw new SubjectNotMatchedException();
    }

    public function findFirst(): Optional
    {
        $match = $this->base->matchOffset();
        if ($match->matched()) {
            return new PresentOptional($this->firstDetail($match));
        }
        return new EmptyOptional();
    }

    private function firstDetail(RawMatchOffset $match): Detail
    {
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, 0, $this->groupAware);
        return DeprecatedMatchDetail::create($this->subject, 0, $polyfill, $this->allFactory, new MatchPrime($match));
    }

    /**
     * @param int $limit
     * @return Detail[]
     */
    public function only(int $limit): array
    {
        return $this->matchOnly->get(new Limit($limit));
    }

    public function nth(int $index): Detail
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative nth: $index");
        }
        $texts = \array_values($this->base->matchAll()->getTexts());
        if (\array_key_exists($index, $texts)) {
            return $this->factory->mapToDetailObject($this->base->matchAllOffsets(), $index);
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this as $detail) {
            $consumer($detail);
        }
    }

    public function map(callable $mapper): array
    {
        $mapped = [];
        foreach ($this as $detail) {
            $mapped[] = $mapper($detail);
        }
        return $mapped;
    }

    /**
     * @return Detail[]
     */
    public function filter(callable $predicate): array
    {
        return $this->matchItems->filter(new Predicate($predicate, 'filter'));
    }

    public function flatMap(callable $mapper): array
    {
        return $this->matchItems->flatMap(new ListFunction(new ArrayFunction($mapper, 'flatMap')));
    }

    public function toMap(callable $mapper): array
    {
        return $this->matchItems->flatMap(new DictionaryFunction(new ArrayFunction($mapper, 'toMap')));
    }

    /**
     * @return Detail[]
     */
    public function distinct(): array
    {
        return \array_values(\array_unique($this->all()));
    }

    public function count(): int
    {
        return $this->amount->intValue();
    }

    /**
     * @return \Iterator|iterable<Detail>
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->getDetailObjects());
    }

    public function stream(): Stream
    {
        return new Stream(new DetailStream(new StreamBase($this->base), $this->subject, $this->allFactory, $this->groupAware));
    }

    /**
     * @param string|int $nameOrIndex
     * @return string[][]
     */
    public function groupBy($nameOrIndex): array
    {
        return $this->grouped->groupedBy(GroupKey::of($nameOrIndex));
    }

    /**
     * @param callable $groupMapper
     * @return Detail[][]
     */
    public function groupByCallback(callable $groupMapper): array
    {
        return $this->grouped(new GroupByFunction('groupByCallback', $groupMapper));
    }

    private function grouped(GroupByFunction $function): array
    {
        $result = [];
        foreach ($this as $detail) {
            $result[$function->apply($detail)][] = $detail;
        }
        return $result;
    }

    private function getDetailObjects(): array
    {
        return $this->factory->mapToDetailObjects($this->base->matchAllOffsets());
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $detail) {
            $accumulator = $reducer($accumulator, $detail);
        }
        return $accumulator;
    }

    public function subject(): string
    {
        return $this->subject->asString();
    }

    /**
     * @return (string|null)[]
     */
    public function groupNames(): array
    {
        return $this->groupNames->groupNames();
    }

    public function groupsCount(): int
    {
        return \count(\array_filter($this->groupAware->getGroupKeys(), '\is_int')) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function groupExists($nameOrIndex): bool
    {
        return $this->groupAware->hasGroup(GroupKey::of($nameOrIndex));
    }
}
