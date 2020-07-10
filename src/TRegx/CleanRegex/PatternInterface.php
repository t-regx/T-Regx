<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPatternImpl;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Replace\ReplaceLimit;

interface PatternInterface
{
    /**
     * {@documentary:test}
     */
    public function test(string $subject): bool;

    /**
     * {@documentary:fails}
     */
    public function fails(string $subject): bool;

    /**
     * {@documentary:match}
     */
    public function match(string $subject): MatchPattern;

    /**
     * {@documentary:replace}
     */
    public function replace(string $subject): ReplaceLimit;

    /**
     * {@documentary:remove}
     */
    public function remove(string $subject): RemoveLimit;

    /**
     * {@documentary:forArray}
     */
    public function forArray(array $haystack): ForArrayPatternImpl;

    /**
     * {@documentary:split}
     */
    public function split(string $subject): array;

    /**
     * {@documentary:count}
     */
    public function count(string $subject): int;

    /**
     * {@documentary:valid}
     */
    public function valid(): bool;

    /**
     * {@documentary:delimited}
     */
    public function delimited(): string;
}
