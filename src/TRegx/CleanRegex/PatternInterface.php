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
     * {@documentary:test}
     */
    public function fails(string $subject): bool;

    /**
     * {@documentary:test}
     */
    public function match(string $subject): MatchPattern;

    /**
     * {@documentary:test}
     */
    public function replace(string $subject): ReplaceLimit;

    /**
     * {@documentary:test}
     */
    public function remove(string $subject): RemoveLimit;

    /**
     * {@documentary:test}
     */
    public function forArray(array $haystack): ForArrayPatternImpl;

    /**
     * {@documentary:test}
     */
    public function split(string $subject): array;

    /**
     * {@documentary:test}
     */
    public function count(string $subject): int;

    /**
     * {@documentary:test}
     */
    public function valid(): bool;

    /**
     * {@documentary:test}
     */
    public function delimiter(): string;
}
