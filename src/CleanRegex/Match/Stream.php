<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\FilterStream;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntegerStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeyStream;
use TRegx\CleanRegex\Internal\Match\Stream\LimitStream;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\NthStreamElement;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Match\Stream\SkipStream;
use TRegx\CleanRegex\Internal\Match\Stream\StreamRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\UniqueStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\Stream\ValueStream;
use TRegx\CleanRegex\Internal\Match\StreamTerminal;
use TRegx\CleanRegex\Internal\Message\Stream\FromFirstStreamMessage;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\FirstMatchMessage;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Predicate;

class Stream implements \Countable, \IteratorAggregate
{
    /** @var StreamTerminal */
    private $terminal;
    /** @var Upstream */
    private $upstream;
    /** @var NthStreamElement */
    private $nth;

    public function __construct(Upstream $upstream)
    {
        $this->terminal = new StreamTerminal($upstream);
        $this->upstream = $upstream;
        $this->nth = new NthStreamElement($upstream);
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

    public function reduce(callable $reducer, $accumulator)
    {
        return $this->terminal->reduce($reducer, $accumulator);
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
            [$key, $value] = $this->upstream->first();
            return new PresentOptional($value);
        } catch (StreamRejectedException $exception) {
            $message = $exception->notMatchedMessage();
        } catch (EmptyStreamException $exception) {
            $message = new FromFirstStreamMessage();
        } catch (UnmatchedStreamException $exception) {
            $message = new FirstMatchMessage();
        }
        return new RejectedOptional(new NoSuchStreamElementException($message));
    }

    public function nth(int $index)
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

    public function asInt(int $base = 10): Stream
    {
        return $this->next(new IntegerStream($this->upstream, new Numeral\Base($base)));
    }

    public function groupByCallback(callable $groupMapper): Stream
    {
        return $this->next(new GroupByCallbackStream($this->upstream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    public function limit(int $limit): Stream
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return $this->next(new LimitStream($this->upstream, $limit));
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
