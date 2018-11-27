<?php
namespace TRegx\CleanRegex\Match;

use ArrayIterator;
use Countable;
use EmptyIterator;
use Iterator;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\Subject\FirstMatchMessage;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\GroupLimit\GroupLimitFactory;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Base\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
use TRegx\CleanRegex\Internal\Match\MatchAll\LazyMatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Predicate;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\OffsetLimit\MatchOffsetLimitFactory;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Match\Details\Match;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\ForFirst\MatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\NotMatchedOptional;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Match\Offset\MatchOffsetLimit;

abstract class AbstractMatchPattern implements PatternLimit, Countable
{
    /** @var Base */
    protected $base;

    public function __construct(Base $base)
    {
        $this->base = $base;
    }

    abstract public function matches(): bool;

    public function fails(): bool
    {
        return !$this->matches();
    }

    public function all(): array
    {
        return $this->base->matchAll()->getAll();
    }

    /**
     * @param callable|null $callback
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $callback = null)
    {
        $match = $this->base->matchOffset();
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirst($this->base);
        }
        if ($callback !== null) {
            $factory = new LazyMatchAllFactory($this->base);
            $polyfill = new GroupPolyfillDecorator($match, $factory, 0);
            $matchObject = new Match($this->base, 0, $polyfill, $factory);
            return $callback($matchObject);
        }
        return $match->getText();
    }

    public function only(int $limit): array
    {
        return (new MatchOnly($this->base, $limit))->get();
    }

    public function forEach(callable $callback): void
    {
        foreach ($this->getMatchObjects() as $object) {
            $callback($object);
        }
    }

    public function iterate(callable $callback): void
    {
        $this->forEach($callback);
    }

    public function map(callable $callback): array
    {
        return array_map($callback, $this->getMatchObjects());
    }

    public function flatMap(callable $callback): array
    {
        return (new FlatMapper($this->getMatchObjects(), $callback))->get();
    }

    /**
     * @param callable $callback
     * @return Optional
     */
    public function forFirst(callable $callback): Optional
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->matched()) {
            $result = $callback($matches->getFirstMatchObject());
            return new MatchedOptional($result);
        }
        return new NotMatchedOptional(
            new NotMatchedOptionalWorker(
                new FirstMatchMessage(),
                $this->base,
                new NotMatched($matches, $this->base))
        );
    }

    /**
     * @param string|int $nameOrIndex
     * @return GroupLimit
     */
    public function group($nameOrIndex): GroupLimit
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return (new GroupLimitFactory($this->base, $nameOrIndex))->create();
    }

    public function offsets(): MatchOffsetLimit
    {
        return (new MatchOffsetLimitFactory($this->base, 0))->create();
    }

    abstract public function count(): int;

    public function iterator(): Iterator
    {
        $objects = $this->getMatchObjects();
        if (empty($objects)) {
            return new EmptyIterator();
        }
        return new ArrayIterator($objects);
    }

    public function filter(callable $predicate): FilteredMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator($this->base, new Predicate($predicate)));
    }

    /**
     * @return Match[]
     */
    protected function getMatchObjects(): array
    {
        return $this->base->matchAllOffsets()->getMatchObjects();
    }
}
