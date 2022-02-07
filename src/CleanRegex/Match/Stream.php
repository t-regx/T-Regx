<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\NoSuchStreamElementException;
use TRegx\CleanRegex\Internal\Match\FlatFunction;
use TRegx\CleanRegex\Internal\Match\FlatMap\ArrayMergeStrategy;
use TRegx\CleanRegex\Internal\Match\FlatMap\AssignStrategy;
use TRegx\CleanRegex\Internal\Match\GroupByFunction;
use TRegx\CleanRegex\Internal\Match\PresentOptional;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Match\Stream\Base\UnmatchedStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\EmptyStreamException;
use TRegx\CleanRegex\Internal\Match\Stream\FilterStream;
use TRegx\CleanRegex\Internal\Match\Stream\FlatMapStream;
use TRegx\CleanRegex\Internal\Match\Stream\GroupByCallbackStream;
use TRegx\CleanRegex\Internal\Match\Stream\IntegerStream;
use TRegx\CleanRegex\Internal\Match\Stream\KeyStream;
use TRegx\CleanRegex\Internal\Match\Stream\MapStream;
use TRegx\CleanRegex\Internal\Match\Stream\RejectedOptional;
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\UniqueStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Match\Stream\ValuesStream;
use TRegx\CleanRegex\Internal\Match\StreamTerminal;
use TRegx\CleanRegex\Internal\Message\Stream\FromFirstStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\FromNthStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Predicate;
use TRegx\CleanRegex\Internal\Subject;

class Stream implements \Countable, \IteratorAggregate
{
    /** @var StreamTerminal */
    private $terminal;
    /** @var Upstream */
    private $upstream;
    /** @var Subject */
    private $subject;

    public function __construct(Upstream $upstream, Subject $subject)
    {
        $this->terminal = new StreamTerminal($upstream);
        $this->upstream = $upstream;
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
            $firstElement = $this->upstream->first();
        } catch (StramRejectedException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchStreamElementException::class, $exception->notMatchedMessage()));
        } catch (EmptyStreamException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchStreamElementException::class, new FromFirstStreamMessage()));
        }
        return new PresentOptional($consumer($firstElement));
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
        try {
            $elements = \array_values($this->upstream->all());
        } catch (UnmatchedStreamException $exception) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchStreamElementException::class, new SubjectNotMatched\FromNthStreamMessage($index)));
        }
        if (!\array_key_exists($index, $elements)) {
            return new RejectedOptional(new Rejection($this->subject, NoSuchStreamElementException::class, new FromNthStreamMessage($index, \count($elements))));
        }
        return new PresentOptional($elements[$index]);
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

    public function asInt(int $base = null): Stream
    {
        return $this->next(new IntegerStream($this->upstream, new Number\Base($base)));
    }

    public function groupByCallback(callable $groupMapper): Stream
    {
        return $this->next(new GroupByCallbackStream($this->upstream, new GroupByFunction('groupByCallback', $groupMapper)));
    }

    private function next(Upstream $upstream): Stream
    {
        return new Stream($upstream, $this->subject);
    }

    public function reduce(callable $reducer, $accumulator)
    {
        foreach ($this as $detail) {
            $accumulator = $reducer($accumulator, $detail);
        };
        return $accumulator;
    }
}
