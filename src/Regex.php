<?php
namespace Regex;

interface Regex
{
    public function test(string $subject): bool;

    public function count(string $subject): int;

    public function first(string $subject): Detail;

    public function firstOrNull(string $subject): ?Detail;

    /**
     * @return string[]
     */
    public function search(string $subject): array;

    /**
     * @return string[]|null[]
     */
    public function searchGroup(string $subject, $nameOrIndex): array;

    public function match(string $subject): Matcher;

    /**
     * @return Detail[]|\Iterator
     */
    public function matchPartial(string $subject): \Iterator;

    public function replace(string $subject, string $replacement, int $limit = -1): string;

    /**
     * @return string[]|int[]
     */
    public function replaceCount(string $subject, string $replacement, int $limit = -1): array;

    public function replaceGroup(string $subject, $nameOrIndex, int $limit = -1): string;

    public function replaceCallback(string $subject, callable $replacer, int $limit = -1): string;

    /**
     * @return string[]|null[]
     */
    public function split(string $subject, int $maxSplits = -1): array;

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function filter(array $subjects): array;

    /**
     * @param string[] $subjects
     * @return string[]
     */
    public function reject(array $subjects): array;

    /**
     * @return string[]|null[]
     */
    public function groupNames(): array;

    /**
     * @param string|int $nameOrIndex
     */
    public function groupExists($nameOrIndex): bool;

    public function groupCount(): int;

    public function delimited(): string;

    public function __toString(): string;
}
