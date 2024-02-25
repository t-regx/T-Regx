<?php
namespace TRegx\CleanRegex\Match;

/**
 * @deprecated
 */
interface Group extends Element
{
    /**
     * @deprecated
     */
    public function matched(): bool;

    /**
     * @deprecated
     */
    public function equals(string $expected): bool;

    /**
     * @deprecated
     */
    public function or(string $substitute): string;

    /**
     * @deprecated
     */
    public function index(): int;

    /**
     * @deprecated
     */
    public function name(): ?string;

    /**
     * @return int|string
     * @deprecated
     */
    public function usedIdentifier();

    /**
     * @deprecated
     */
    public function all(): array;
}
