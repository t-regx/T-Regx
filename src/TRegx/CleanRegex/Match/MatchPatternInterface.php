<?php
namespace TRegx\CleanRegex\Match;

use Iterator;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\FindFirst\Optional;

interface MatchPatternInterface
{
    public function all(): array;

    public function only(int $limit): array;

    /**
     * @param callable|null $consumer
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $consumer = null);

    public function forEach(callable $consumer): void;

    /**
     * @param callable $consumer
     * @return Optional
     */
    public function findFirst(callable $consumer): Optional;

    public function count(): int;

    public function iterator(): Iterator;

    /**
     * @param callable $mapper
     * @return MatchPatternInterface|array
     */
    public function map(callable $mapper);

    /**
     * @param callable $mapper
     * @return MatchPatternInterface|array
     */
    public function flatMap(callable $mapper);

    /**
     * @return MatchPatternInterface|array
     */
    public function distinct();

    /**
     * @param callable $predicate
     * @return MatchPatternInterface|array
     */
    public function filter(callable $predicate);
}
