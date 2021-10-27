<?php
namespace TRegx\CleanRegex\Match;

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
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\UniqueStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\Stream\ValuesStream;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

class IntStream implements \Countable, \IteratorAggregate
{
    /** @var StreamTerminal */
    private $terminal;
    /** @var Upstream */
    protected $upstream;
    /** @var NthIntStreamElement */
    protected $nth;
    /** @var Subject */
    private $subject;

    public function __construct(Upstream $upstream, NthIntStreamElement $nth, Subject $subject)
    {
        $this->terminal = new StreamTerminal($upstream);
        $this->upstream = $upstream;
        $this->nth = $nth;
        $this->subject = $subject;
    }

    public function all(): array
    {
        return $this->terminal->all();
    }

    public function only(int $limit): array
    {
        return $this->terminal->only($limit);
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

    public function first(callable $consumer = null)
    {
        if ($consumer === null) {
            return $this->firstOptional()->orThrow();
        }
        return $this->firstOptional()->map($consumer)->orThrow();
    }

    public function findFirst(callable $consumer): Optional
    {
        return $this->firstOptional()->map($consumer);
    }

    private function firstOptional(): Optional
    {
        try {
            return new PresentOptional($this->upstream->first());
        } catch (StramRejectedException $exception) {
            return new RejectedOptional($exception->rejection());
        }
    }

    public function nth(int $index): int
    {
        return $this->findNth($index)->orThrow();
    }

    public function findNth(int $index): Optional
    {
        if ($index < 0) {
            throw new \InvalidArgumentException("Negative index: $index");
        }
        return $this->nth->optional($index);
    }

    public function asInt(): IntStream
    {
        return $this;
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
        return $this->next(new ValuesStream($this->upstream));
    }

    public function keys(): Stream
    {
        return $this->next(new KeyStream($this->upstream));
    }

    public function groupByCallback(callable $groupMapper): Stream
    {
        return $this->next(new GroupByCallbackStream($this->upstream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    private function next(Upstream $upstream): Stream
    {
        return new Stream($upstream, $this->subject);
    }
}
