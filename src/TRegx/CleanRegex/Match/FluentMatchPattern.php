<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NoSuchElementFluentMessage;
use TRegx\CleanRegex\Internal\Exception\NoFirstSwitcherException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\FluentInteger;
use TRegx\CleanRegex\Internal\Match\Switcher\ArrayOnlySwitcher;
use TRegx\CleanRegex\Internal\Match\Switcher\ArraySwitcher;
use TRegx\CleanRegex\Internal\Match\Switcher\FlatMappingSwitcher;
use TRegx\CleanRegex\Internal\Match\Switcher\GroupByCallbackSwitcher;
use TRegx\CleanRegex\Internal\Match\Switcher\MappingSwitcher;
use TRegx\CleanRegex\Internal\Match\Switcher\Switcher;
use TRegx\CleanRegex\Match\FindFirst\MatchedOptional;
use TRegx\CleanRegex\Match\FindFirst\Optional;

class FluentMatchPattern implements MatchPatternInterface
{
    /** @var Switcher */
    private $switcher;
    /** @var NotMatchedFluentOptionalWorker */
    private $firstWorker;

    public function __construct(Switcher $switcher, NotMatchedFluentOptionalWorker $firstWorker)
    {
        $this->switcher = $switcher;
        $this->firstWorker = $firstWorker;
    }

    public function all(): array
    {
        return $this->switcher->all();
    }

    public function only(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return \array_slice($this->switcher->all(), 0, $limit);
    }

    /**
     * @param callable|null $consumer
     * @return string|mixed
     * @throws NoSuchElementFluentException
     */
    public function first(callable $consumer = null)
    {
        try {
            $firstElement = $this->switcher->first();
            return $consumer ? $consumer($firstElement) : $firstElement;
        } catch (NoFirstSwitcherException $exception) {
            throw NoSuchElementFluentException::withMessage($this->firstWorker->getMessage());
        }
    }

    public function findFirst(callable $consumer): Optional
    {
        try {
            return new MatchedOptional($consumer($this->switcher->first()));
        } catch (NoFirstSwitcherException $exception) {
            return new NotMatchedFluentOptional($this->firstWorker);
        }
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
        $elements = \array_values($this->switcher->all());
        if (\array_key_exists($index, $elements)) {
            return new MatchedOptional($elements[$index]);
        }
        return new NotMatchedFluentOptional(new NotMatchedFluentOptionalWorker(new NoSuchElementFluentMessage($index, \count($elements))));
    }

    public function forEach(callable $consumer): void
    {
        foreach ($this->switcher->all() as $key => $value) {
            $consumer($value, $key);
        }
    }

    public function count(): int
    {
        return \count($this->switcher->all());
    }

    public function iterator(): Iterator
    {
        return new ArrayIterator($this->switcher->all());
    }

    public function map(callable $mapper): FluentMatchPattern
    {
        return $this->next(new MappingSwitcher($this->switcher, $mapper));
    }

    public function flatMap(callable $mapper): FluentMatchPattern
    {
        return $this->next(new FlatMappingSwitcher($this->switcher, $mapper));
    }

    public function distinct(): FluentMatchPattern
    {
        return $this->next(new ArrayOnlySwitcher($this->switcher, '\array_unique'));
    }

    public function filter(callable $predicate): FluentMatchPattern
    {
        return $this->next(new ArraySwitcher(\array_values(\array_filter($this->switcher->all(), $predicate))));
    }

    public function values(): FluentMatchPattern
    {
        return $this->next(new ArrayOnlySwitcher($this->switcher, '\array_values'));
    }

    public function keys(): FluentMatchPattern
    {
        return $this->next(new ArraySwitcher(\array_keys($this->switcher->all())));
    }

    public function asInt(): FluentMatchPattern
    {
        return $this->map([FluentInteger::class, 'parse']);
    }

    public function groupByCallback(callable $groupMapper): FluentMatchPattern
    {
        return $this->next(new GroupByCallbackSwitcher($this->switcher, $groupMapper));
    }

    private function next(Switcher $switcher): FluentMatchPattern
    {
        return new FluentMatchPattern($switcher, $this->firstWorker);
    }
}
