<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Match\Details\Intable;
use TRegx\CleanRegex\Match\Optional;

interface CapturingGroup extends Intable
{
    public function text(): string;

    public function toInt(int $base = 10): int;

    public function isInt(int $base = 10): bool;

    public function matched(): bool;

    public function equals(string $expected): bool;

    public function or(string $substitute): string;

    public function name(): ?string;

    /**
     * @return int|string
     */
    public function usedIdentifier();

    public function offset(): int;

    public function tail(): int;

    public function length(): int;

    public function byteOffset(): int;

    public function byteTail(): int;

    public function textByteLength(): int;

    public function subject(): string;

    public function all(): array;

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string;

    /**
     * @deprecated
     */
    public function map(callable $mapper): Optional;
}
