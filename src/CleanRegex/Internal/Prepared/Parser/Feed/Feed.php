<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Feed;

interface Feed
{
    public function firstLetter(): string;

    public function stringLengthBeforeAny(string $characters): int;

    public function stringBefore(string $breakpoint): Span;

    public function commit(string $string): void;

    public function commitSingle(): void;

    public function empty(): bool;

    public function head(): string;

    public function hasTwoLetters(): bool;

    public function startsWith(string $infix): bool;

    public function subString(int $length): string;

    public function content(): string;
}
