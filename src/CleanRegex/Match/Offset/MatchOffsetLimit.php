<?php
namespace TRegx\CleanRegex\Match\Offset;

use InvalidArgumentException;
use Iterator;
use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Factory\SecondLevelFluentOptionalWorker;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\Groups\Strategy\MatchAllGroupVerifier;
use TRegx\CleanRegex\Internal\Match\Offset\MatchOffsetStream;
use TRegx\CleanRegex\Match\FluentMatchPattern;

class MatchOffsetLimit implements OffsetLimit, \IteratorAggregate
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var bool */
    private $isWholeMatch;
    /** @var MatchAllGroupVerifier */
    private $groupVerifier;

    public function __construct(Base $base, $nameOrIndex, bool $isWholeMatch)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->isWholeMatch = $isWholeMatch;
        $this->groupVerifier = new MatchAllGroupVerifier($this->base->getPattern());
    }

    public function first(): int
    {
        $rawMatch = $this->base->matchOffset();
        if ($rawMatch->hasGroup($this->nameOrIndex)) {
            $group = $rawMatch->getGroupByteOffset($this->nameOrIndex);
            if ($group !== null) {
                return $group;
            }
            throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
        }
        if (!$this->groupVerifier->groupExists($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if ($rawMatch->matched()) {
            throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
        }
        if ($this->isWholeMatch) {
            throw SubjectNotMatchedException::forFirstOffset($this->base);
        }
        throw SubjectNotMatchedException::forFirstGroupOffset($this->base, $this->nameOrIndex);
    }

    /**
     * @return (int|null)[]
     */
    public function all(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        return $matches->getLimitedGroupOffsets($this->nameOrIndex, -1);
    }

    public function getIterator(): Iterator
    {
        return new \ArrayIterator($this->all());
    }

    /**
     * @param int $limit
     * @return (int|null)[]
     */
    public function only(int $limit): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        return $matches->getLimitedGroupOffsets($this->nameOrIndex, $limit);
    }

    public function fluent(): FluentMatchPattern
    {
        return new FluentMatchPattern(new MatchOffsetStream($this), new SecondLevelFluentOptionalWorker(new FirstFluentMessage()));
    }
}
