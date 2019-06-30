<?php
namespace TRegx\CleanRegex\Internal\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\CleanRegex\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedFluentOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\MatchPatternInterface;

class FluentMatchPattern implements MatchPatternInterface
{
    /** @var array */
    private $elements;
    /** @var NotMatchedFluentOptionalWorker */
    private $worker;

    public function __construct(array $elements, NotMatchedWorker $worker)
    {
        $this->elements = $elements;
        $this->worker = $worker;
    }

    public function all(): array
    {
        return $this->elements;
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit $limit");
        }
        return array_slice($this->elements, 0, $limit);
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     * @throws NoFirstElementFluentException
     */
    public function first(callable $consumer = null)
    {
        if (empty($this->elements)) {
            throw new NoFirstElementFluentException();
        }
        return reset($this->elements);
    }

    public function forFirst(callable $consumer): Optional
    {
        if (empty($this->elements)) {
            return new NotMatchedFluentOptional($this->worker);
        }
        return new MatchedOptional($consumer(reset($this->elements)));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->elements as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function iterate(callable $consumer): void
    {
        $this->forEach($consumer);
    }

    public function count(): int
    {
        return count($this->elements);
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->elements);
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(array_map($mapper, $this->elements));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next((new FlatMapper($this->elements, $mapper))->get());
    }

    public function unique(): FluentMatchPattern
    {
        return $this->next(array_values(array_unique($this->elements)));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(array_values(array_filter($this->elements, $predicate)));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(array_values($this->elements));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(array_keys($this->elements));
    }

    private function next(array $elements): FluentMatchPattern
    {
        return new FluentMatchPattern($elements, $this->worker);
    }
}
