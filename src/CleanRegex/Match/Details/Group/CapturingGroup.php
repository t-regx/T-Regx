<?php
namespace TRegx\CleanRegex\Match\Details\Group;

use TRegx\CleanRegex\Match\Optional;

interface CapturingGroup extends Element
{
    public function matched(): bool;

    public function equals(string $expected): bool;

    public function or(string $substitute): string;

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

    /**
     * @deprecated
     */
    public function map(callable $mapper): Optional;
}
