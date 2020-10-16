<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPattern;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Replace\ReplaceLimit;

interface PatternInterface
{
    /**
     * {@documentary:test}
     *
     * @param string $subject
     *
     * @return bool
     */
    public function test(string $subject): bool;

    /**
     * {@documentary:fails}
     *
     * @param string $subject
     *
     * @return bool
     */
    public function fails(string $subject): bool;

    /**
     * {@documentary:match}
     *
     * @param string $subject
     *
     * @return MatchPattern
     */
    public function match(string $subject): MatchPattern;

    /**
     * {@documentary:replace}
     *
     * @param string $subject
     *
     * @return ReplaceLimit
     */
    public function replace(string $subject): ReplaceLimit;

    /**
     * {@documentary:remove}
     *
     * @param string $subject
     *
     * @return RemoveLimit
     */
    public function remove(string $subject): RemoveLimit;

    /**
     * {@documentary:forArray}
     *
     * @param string[] $haystack
     *
     * @return ForArrayPattern
     */
    public function forArray(array $haystack): ForArrayPattern;

    /**
     * {@documentary:split}
     *
     * @param string $subject
     *
     * @return array
     */
    public function split(string $subject): array;

    /**
     * {@documentary:count}
     *
     * @param string $subject
     *
     * @return int
     */
    public function count(string $subject): int;

    /**
     * {@documentary:valid}
     *
     * @return bool
     */
    public function valid(): bool;

    /**
     * {@documentary:delimited}
     */
    public function delimited(): string;
}
