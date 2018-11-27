<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

interface IRawMatchGroupable
{
    public function matched(): bool;

    public function hasGroup($nameOrIndex): bool;

    public function getGroup($nameOrIndex): ?string;

    public function getGroupByteOffset($nameOrIndex): ?int;
}
