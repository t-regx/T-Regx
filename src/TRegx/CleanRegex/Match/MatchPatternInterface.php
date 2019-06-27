<?php
namespace TRegx\CleanRegex\Match;

use Iterator;
use TRegx\CleanRegex\Exception\CleanRegex\SubjectNotMatchedException;
use TRegx\CleanRegex\Match\ForFirst\Optional;

interface MatchPatternInterface
{
    public function all(): array;

    public function only(int $limit): array;

    /**
     * @param callable|null $callback
     * @return string|mixed
     * @throws SubjectNotMatchedException
     */
    public function first(callable $callback = null);

    public function forEach(callable $callback): void;

    public function iterate(callable $callback): void;

    /**
     * @param callable $callback
     * @return Optional
     */
    public function forFirst(callable $callback): Optional;

    public function count(): int;

    public function iterator(): Iterator;

    /**
     * @param callable $callback
     * @return MatchPatternInterface|array
     */
    public function map(callable $callback);

    /**
     * @param callable $callback
     * @return MatchPatternInterface|array
     */
    public function flatMap(callable $callback);

    /**
     * @return MatchPatternInterface|array
     */
    public function unique();

    /**
     * @param callable $predicate
     * @return MatchPatternInterface|array
     */
    public function filter(callable $predicate);
}
