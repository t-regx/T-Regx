<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\FluentMatchPatternException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\InvalidReturnValueException;
use TRegx\CleanRegex\Exception\NoFirstElementFluentException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\NotMatchedWorker;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\NotMatchedFluentOptional;
use TRegx\CleanRegex\Match\FindFirst\Optional;

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
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->elements, 0, $limit);
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
        $firstElement = \reset($this->elements);
        return $consumer ? $consumer($firstElement) : $firstElement;
    }

    public function findFirst(callable $consumer): Optional
    {
        if (empty($this->elements)) {
            return new NotMatchedFluentOptional($this->worker);
        }
        return new MatchedOptional($consumer(\reset($this->elements)));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->elements as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function count(): int
    {
        return \count($this->elements);
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->elements);
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(\array_map($mapper, $this->elements));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next((new FlatMapper($this->elements, $mapper))->get());
    }

    public function distinct(): FluentMatchPattern
    {
        return $this->next(\array_values(\array_unique($this->elements)));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(\array_values(\array_filter($this->elements, $predicate)));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(\array_values($this->elements));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(\array_keys($this->elements));
    }

    public function asInt(): FluentMatchPattern
    {
        return $this->map(function ($value) {
            if (\is_int($value)) {
                return (int)$value;
            }
            if (!\is_string($value)) {
                throw FluentMatchPatternException::forInvalidInteger($value);
            }
            if (Integer::isValid($value)) {
                return (int)$value;
            }
            throw IntegerFormatException::forMatch($value);
        });
    }

    public function groupByCallback(callable $groupMapper): FluentMatchPattern
    {
        $map = [];
        foreach ($this->elements as $element) {
            $key = $groupMapper($element);
            if (\is_int($key) || \is_string($key)) {
                $map[$key][] = $element;
            } else {
                throw InvalidReturnValueException::forGroupByCallback($key);
            }
        }
        return $this->next($map);
    }

    private function next(array $elements): FluentMatchPattern
    {
        return new FluentMatchPattern($elements, $this->worker);
    }
}
