<?php
namespace TRegx\CleanRegex\Match;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;

interface MatchPatternInterface extends \Countable, \IteratorAggregate
{
    public function all(): array;

    public function only(int $limit): array;

    /**
     * @param callable|null $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null);

    public function nth(int $index);

    public function forEach(callable $consumer): void;

    /**
     * @param callable $consumer
     * @return Optional
     */
    public function findFirst(callable $consumer): Optional;

    public function count(): int;

    /**
     * @param callable $mapper
     * @return MatchPatternInterface|array
     */
    public function map(callable $mapper);

    /**
     * @param callable $predicate
     * @return MatchPatternInterface|array
     */
    public function filter(callable $predicate);

    public function asInt(int $base = null): FluentMatchPattern;

    /**
     * @param callable $mapper
     * @return MatchPatternInterface|array
     */
    public function flatMap(callable $mapper);

    /**
     * @param callable $mapper
     * @return MatchPatternInterface|array
     */
    public function flatMapAssoc(callable $mapper);

    /**
     * @return MatchPatternInterface|array
     */
    public function distinct();

    /**
     * @param callable $groupMapper
     * @return GroupByPattern|array
     */
    public function groupByCallback(callable $groupMapper);
}
