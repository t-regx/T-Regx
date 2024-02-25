<?php
namespace TRegx\CleanRegex\Match;

/**
 * @deprecated
 */
interface Structure
{
    public function subject(): string;

    /**
     * @return (string|null)[]
     * @deprecated
     */
    public function groupNames(): array;

    /**
     * @deprecated
     */
    public function groupsCount(): int;

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function groupExists($nameOrIndex): bool;
}
