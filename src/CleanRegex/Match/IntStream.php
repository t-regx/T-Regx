<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Internal\EmptyOptional;
use TRegx\CleanRegex\Internal\Index;
use TRegx\CleanRegex\Internal\Limit;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\FilterStream;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeyStream;
use TRegx\CleanRegex\Internal\Match\Stream\LimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\SkipStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\StreamTerminal;
use TRegx\CleanRegex\Internal\Match\Stream\UniqueStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\Stream\ValueStream;
use TRegx\CleanRegex\Internal\Predicate;

class IntStream implements \Countable, \IteratorAggregate
{
    /** @var StreamTerminal */
    private $terminal;
    /** @var Upstream */
    private $upstream;
    /** @var NthIntStreamElement */
    private $nth;

    public function __construct(Upstream $upstream, NthIntStreamElement $nth)
    {
        $this->terminal = new StreamTerminal($upstream);
        $this->upstream = $upstream;
        $this->nth = $nth;
    }

    public function all(): array
    {
        return $this->terminal->all();
    }

    public function forEach(callable $consumer): void
    {
        $this->terminal->forEach($consumer);
    }

    public function count(): int
    {
        return $this->terminal->count();
    }

    public function getIterator(): \Iterator
    {
        return $this->terminal->getIterator();
    }

    public function reduce(callable $reducer, $accumulator)
    {
        return $this->terminal->reduce($reducer, $accumulator);
    }

    public function first(callable $consumer = null)
    {
        if ($consumer === null) {
            return $this->firstValue();
        }
        return $consumer($this->firstValue());
    }

    private function firstValue()
    {
        try {
            [$key, $value] = $this->upstream->first();
            return $value;
        } catch (StreamRejectedException $exception) {
            throw $exception->throwable();
        }
    }

    public function findFirst(callable $consumer): Optional
    {
        return $this->firstOptional()->map($consumer);
    }

    private function firstOptional(): Optional
    {
        try {
            [$key, $value] = $this->upstream->first();
        } catch (StreamRejectedException $exception) {
            return new EmptyOptional();
        }
        return new PresentOptional($value);
    }

    public function nth(int $index): int
    {
        return $this->nth->value(new Index($index));
    }

    public function findNth(int $index): Optional
    {
        return $this->nth->optional(new Index($index));
    }

    public function map(callable $mapper): Stream
    {
        return $this->next(new MapStream($this->upstream, $mapper));
    }

    public function flatMap(callable $mapper): Stream
    {
        return $this->next(new FlatMapStream($this->upstream, new ArrayMergeStrategy(), new FlatFunction($mapper, 'flatMap')));
    }

    public function flatMapAssoc(callable $mapper): Stream
    {
        return $this->next(new FlatMapStream($this->upstream, new AssignStrategy(), new FlatFunction($mapper, 'flatMapAssoc')));
    }

    public function distinct(): Stream
    {
        return $this->next(new UniqueStream($this->upstream));
    }

    public function filter(callable $predicate): Stream
    {
        return $this->next(new FilterStream($this->upstream, new Predicate($predicate, 'filter')));
    }

    public function values(): Stream
    {
        return $this->next(new ValueStream($this->upstream));
    }

    public function keys(): Stream
    {
        return $this->next(new KeyStream($this->upstream));
    }

    public function stream(): Stream
    {
        return new Stream($this->upstream);
    }

    public function groupByCallback(callable $groupMapper): Stream
    {
        return $this->next(new GroupByCallbackStream($this->upstream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    public function limit(int $limit): Stream
    {
        return $this->next(new LimitStream($this->upstream, new Limit($limit)));
    }

    public function skip(int $offset): Stream
    {
        if ($offset < 0) {
            throw new \InvalidArgumentException("Negative offset: $offset");
        }
        return $this->next(new SkipStream($this->upstream, $offset));
    }

    private function next(Upstream $upstream): Stream
    {
        return new Stream($upstream);
    }
}
