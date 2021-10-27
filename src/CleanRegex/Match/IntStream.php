<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use IteratorAggregate;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\IntStream\NthIntStreamElement;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
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

class IntStream implements IteratorAggregate
{
    /** @var Upstream */
    protected $stream;
    /** @var NthIntStreamElement */
    protected $nth;
    /** @var Subject */
    private $subject;

    public function __construct(Upstream $stream, NthIntStreamElement $nth, Subject $subject)
    {
        $this->stream = $stream;
        $this->nth = $nth;
        $this->subject = $subject;
    }

    public function asInt(): IntStream
    {
        return $this;
    }

    public function all(): array
    {
        try {
            return $this->stream->all();
        } catch (UnmatchedStreamException $exception) {
            return [];
        }
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->all(), 0, $limit);
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
            return new PresentOptional($this->stream->first());
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
            throw new InvalidArgumentException("Negative index: $index");
        }
        return $this->nth->optional($index);
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->all() as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function count(): int
    {
        try {
            return \count($this->stream->all());
        } catch (UnmatchedStreamException $exception) {
            return 0;
        }
    }

    public function getIterator(): Iterator
    {
        try {
            return new ArrayIterator($this->stream->all());
        } catch (UnmatchedStreamException $exception) {
            return new \EmptyIterator();
        }
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(new MapStream($this->stream, $mapper));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMapStream($this->stream, new ArrayMergeStrategy(), new FlatFunction($mapper, 'flatMap')));
    }

    public function flatMapAssoc(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMapStream($this->stream, new AssignStrategy(), new FlatFunction($mapper, 'flatMapAssoc')));
    }

    public function distinct(): FluentMatchPattern
    {
        return $this->next(new UniqueStream($this->stream));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(new FilterStream($this->stream, new Predicate($predicate, 'filter')));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(new ValuesStream($this->stream));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(new KeyStream($this->stream));
    }

    public function groupByCallback(callable $groupMapper): FluentMatchPattern
    {
        return $this->next(new GroupByCallbackStream($this->stream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    private function next(Upstream $stream): FluentMatchPattern
    {
        return new FluentMatchPattern($stream, $this->subject);
    }
}
