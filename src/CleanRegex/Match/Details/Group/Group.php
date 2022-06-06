<?php
namespace TRegx\CleanRegex\Match\Details\Group;

interface Group extends Element
{
    public function matched(): bool;

    public function equals(string $expected): bool;

    public function or(string $substitute): string;

    public function index(): int;

    public function name(): ?string;

    /**
     * @return int|string
     */
    public function usedIdentifier();

    public function all(): array;

    /**
     * @deprecated
     */
    public function substitute(string $replacement): string;
}
