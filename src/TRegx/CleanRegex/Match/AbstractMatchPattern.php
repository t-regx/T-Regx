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
use TRegx\CleanRegex\Internal\Match\Adapter\Base;
use TRegx\CleanRegex\Internal\Match\Adapter\FilteredBaseDecorator;
use TRegx\CleanRegex\Internal\Match\Details\MatchFactory;
use TRegx\CleanRegex\Internal\Match\Filter;
use TRegx\CleanRegex\Internal\Match\FlatMapper;
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
    private const GROUP_WHOLE_MATCH = 0;
    private const FIRST_MATCH = 0;

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
        $matches = $this->base->matchAll();
        return $matches[self::GROUP_WHOLE_MATCH];
    }

    /**
     * @param callable|null $callback
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $callback = null)
    {
        $matches = $this->performMatchAll();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            throw SubjectNotMatchedException::forFirst($this->base->getSubject());
        }
        if ($callback !== null) {
            return $callback(new Match($this->base->getSubject(), self::FIRST_MATCH, $matches));
        }
        list($value, $offset) = $matches[self::GROUP_WHOLE_MATCH][self::FIRST_MATCH];
        return $value;
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
        $results = [];
        foreach ($this->getMatchObjects() as $object) {
            $results[] = $callback($object);
        }
        return $results;
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
        $matches = $this->performMatchAll();
        $subject = $this->base->getSubject();
        if (empty($matches[self::GROUP_WHOLE_MATCH])) {
            return new NotMatchedOptional(new NotMatchedOptionalWorker(new FirstMatchMessage(), $subject, new NotMatched($matches, $subject)));
        }
        $result = $callback(new Match($subject, self::FIRST_MATCH, $matches));
        return new MatchedOptional($result);
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

    public function filter(callable $filterCallback): FilteredMatchPattern
    {
        return new FilteredMatchPattern(new FilteredBaseDecorator($this->base, new Filter($filterCallback)));
    }

    /**
     * @return Match[]
     */
    protected function getMatchObjects(): array
    {
        return MatchFactory::fromMatchAll($this->base, $this->performMatchAll());
    }

    private function performMatchAll(): array
    {
        return $this->base->matchAllOffsets();
    }
}
