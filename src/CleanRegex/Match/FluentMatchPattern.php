<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NthFluentMessage;
use TRegx\CleanRegex\Internal\Exception\NoFirstStreamException;
use TRegx\CleanRegex\Internal\Exception\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\StreamWorker;
use TRegx\CleanRegex\Internal\Match\FindFirst\EmptyOptional;
use TRegx\CleanRegex\Internal\Match\FindFirst\OptionalImpl;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\FluentInteger;
use TRegx\CleanRegex\Internal\Match\FluentPredicate;
use TRegx\CleanRegex\Internal\Match\Stream\ArrayOnlyStream;
use TRegx\CleanRegex\Internal\Match\Stream\FilterStream;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeysStream;
use TRegx\CleanRegex\Internal\Match\Stream\MappingStream;
use TRegx\CleanRegex\Internal\Match\Stream\Stream;

class FluentMatchPattern implements MatchPatternInterface
{
    /** @var Stream */
    private $stream;
    /** @var StreamWorker */
    private $worker;

    public function __construct(Stream $stream, StreamWorker $worker)
    {
        $this->stream = $stream;
        $this->worker = $worker;
    }

    public function all(): array
    {
        return $this->stream->all();
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->stream->all(), 0, $limit);
    }

    /**
     * @param callable|null $consumer
     * @return mixed
     */
    public function first(callable $consumer = null)
    {
        return $this->findFirst($consumer ?? static function ($argument) {
                return $argument;
            })
            ->orThrow();
    }

    public function findFirst(callable $consumer): Optional
    {
        try {
            $firstElement = $this->stream->first();
        } catch (NoFirstStreamException $exception) {
            return new EmptyOptional($this->worker->noFirstOptionalWorker());
        } catch (UnmatchedStreamException $exception) {
            return new EmptyOptional($this->worker->unmatchedOptionalWorker());
        }
        return new OptionalImpl($consumer($firstElement));
    }

    public function nth(int $index)
    {
        return $this->findNth($index)->orThrow();
    }

    public function findNth(int $index): Optional
    {
        if ($index < 0) {
            throw new InvalidArgumentException("Negative index: $index");
        }
        $elements = \array_values($this->stream->all());
        if (\array_key_exists($index, $elements)) {
            return new OptionalImpl($elements[$index]);
        }
        return new EmptyOptional(new ArgumentlessOptionalWorker(
            new NthFluentMessage($index, \count($elements)),
            NoSuchElementFluentException::class));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->stream->all() as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function count(): int
    {
        return \count($this->stream->all());
    }

    public function getIterator(): Iterator
    {
        return new ArrayIterator($this->stream->all());
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(new MappingStream($this->stream, $mapper));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMappingStream($this->stream, new ArrayMergeStrategy(), $mapper, 'flatMap'));
    }

    public function flatMapAssoc(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMappingStream($this->stream, new AssignStrategy(), $mapper, 'flatMapAssoc'));
    }

    public function distinct(): FluentMatchPattern
    {
        return $this->next(new ArrayOnlyStream($this->stream, '\array_unique'));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(new FilterStream($this->stream, new FluentPredicate($predicate, 'filter')));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(new ArrayOnlyStream($this->stream, '\array_values'));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(new KeysStream($this->stream));
    }

    public function asInt(): FluentMatchPattern
    {
        return $this->map([FluentInteger::class, 'parse']);
    }

    public function groupByCallback(callable $groupMapper): FluentMatchPattern
    {
        return $this->next(new GroupByCallbackStream($this->stream, $groupMapper));
    }

    private function next(Stream $stream): FluentMatchPattern
    {
        return new FluentMatchPattern($stream, $this->worker->undecorateWorker());
    }
}
