<?php
namespace TRegx\CleanRegex\Internal\Model;

interface RawMatchGroupable
{
    public function matched(): bool;

    public function hasGroup($nameOrIndex): bool;

    public function getGroup($nameOrIndex): ?string;

    public function getGroupOffset($nameOrIndex): ?int;
}
