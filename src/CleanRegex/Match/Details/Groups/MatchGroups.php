<?php
namespace TRegx\CleanRegex\Match\Details\Groups;

interface MatchGroups
{
    /**
     * @return (string|null)[]
     */
    public function texts(): array;

    /**
     * @return (int|null)[]
     */
    public function offsets(): array;

    /**
     * @return (int|null)[]
     */
    public function byteOffsets(): array;

    public function names(): array;

    public function count(): int;
}
