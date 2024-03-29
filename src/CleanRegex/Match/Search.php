<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Amount;
use TRegx\CleanRegex\Internal\Match\ArrayFunction;
use TRegx\CleanRegex\Internal\Match\Flat\DictionaryFunction;
use TRegx\CleanRegex\Internal\Match\Flat\ListFunction;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\GroupedTexts;
use TRegx\CleanRegex\Internal\Match\Limit;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\SearchBase;
use TRegx\CleanRegex\Internal\Match\SearchItems;
use TRegx\CleanRegex\Internal\Match\SearchOnly;
use TRegx\CleanRegex\Internal\Match\Stream\Base\StreamBase;
use TRegx\CleanRegex\Internal\Match\Stream\Base\TextStream;
use TRegx\CleanRegex\Internal\Pcre\Legacy\ApiBase;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class Search implements \Countable, \IteratorAggregate
{
    /** @var Subject */
    private $subject;
    /** @var SearchBase */
    private $searchBase;
    /** @var StreamBase */
    private $streamBase;
    /** @var SearchOnly */
    private $searchOnly;
    /** @var Amount */
    private $amount;
    /** @var SearchItems */
    private $searchItems;
    /** @var GroupedTexts */
    private $grouped;

    /**
     * @deprecated
     */
    public function __construct(Definition $definition, Subject $subject)
    {
        $this->subject = $subject;
        $this->searchBase = new SearchBase($definition, $subject);
        $this->streamBase = new StreamBase(new ApiBase($definition, $subject));
        $this->searchOnly = new SearchOnly($this->searchBase);
        $this->amount = new Amount($this->searchBase);
        $this->searchItems = new SearchItems($this->searchBase);
        $this->grouped = new GroupedTexts($definition, $subject);
    }

    /**
     * @deprecated
     */
    public function test(): bool
    {
        return $this->amount->atLeastOne();
    }

    /**
     * @deprecated
     */
    public function fails(): bool
    {
        return $this->amount->none();
    }

    /**
     * @return string[]
     * @deprecated
     */
    public function all(): array
    {
        return $this->searchBase->matchAllTexts();
    }

    /**
     * @deprecated
     */
    public function first(): string
    {
        $text = $this->searchBase->matchFirstOrNull();
        if ($text === null) {
            throw new SubjectNotMatchedException();
        }
        return $text;
    }

    /**
     * @deprecated
     */
    public function findFirst(): Optional
    {
        $text = $this->searchBase->matchFirstOrNull();
        if ($text === null) {
            return new EmptyOptional();
        }
        return new PresentOptional($text);
    }

    /**
     * @param int $limit
     * @return string[]
     * @deprecated
     */
    public function only(int $limit): array
    {
        return $this->searchOnly->get(new Limit($limit));
    }

    /**
     * @deprecated
     */
    public function nth(int $index): string
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative nth: $index");
        }
        $texts = \array_values($this->searchBase->matchAllTexts());
        if (\array_key_exists($index, $texts)) {
            return $texts[$index];
        }
        throw NoSuchNthElementException::forSubject($index, \count($texts));
    }

    /**
     * @deprecated
     */
    public function forEach(callable $consumer): void
    {
        foreach ($this as $text) {
            $consumer($text);
        }
    }

    /**
     * @deprecated
     */
    public function map(callable $mapper): array
    {
        $mapped = [];
        foreach ($this as $text) {
            $mapped[] = $mapper($text);
        }
        return $mapped;
    }

    /**
     * @return string[]
     * @deprecated
     */
    public function filter(callable $predicate): array
    {
        return $this->searchItems->filter(new Predicate($predicate, 'filter'));
    }

    /**
     * @deprecated
     */
    public function flatMap(callable $mapper): array
    {
        return $this->searchItems->flatMap(new ListFunction(new ArrayFunction($mapper, 'flatMap')));
    }

    /**
     * @deprecated
     */
    public function toMap(callable $mapper): array
    {
        return $this->searchItems->flatMap(new DictionaryFunction(new ArrayFunction($mapper, 'toMap')));
    }

    /**
     * @return string[]
     * @deprecated
     */
    public function distinct(): array
    {
        return \array_values(\array_unique($this->searchBase->matchAllTexts()));
    }

    /**
     * @deprecated
     */
    public function count(): int
    {
        return $this->amount->intValue();
    }

    /**
     * @return \Iterator|iterable<string>
     * @deprecated
     */
    public function getIterator(): \Iterator
    {
        return new \ArrayIterator($this->searchBase->matchAllTexts());
    }

    /**
     * @deprecated
     */
    public function stream(): Stream
    {
        return new Stream(new TextStream($this->streamBase));
    }

    /**
     * @param string|int $nameOrIndex
     * @return string[][]
     * @deprecated
     */
    public function groupBy($nameOrIndex): array
    {
        return $this->grouped->groupedBy(GroupKey::of($nameOrIndex));
    }

    /**
     * @param callable $groupMapper
     * @return string[][]
     * @deprecated
     */
    public function groupByCallback(callable $groupMapper): array
    {
        return $this->grouped(new GroupByFunction('groupByCallback', $groupMapper));
    }

    private function grouped(GroupByFunction $function): array
    {
        $result = [];
        foreach ($this as $text) {
            $result[$function->apply($text)][] = $text;
        }
        return $result;
    }

    /**
     * @deprecated
     */
    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $text) {
            $accumulator = $reducer($accumulator, $text);
        }
        return $accumulator;
    }

    /**
     * @deprecated
     */
    public function subject(): string
    {
        return $this->subject->asString();
    }
}
