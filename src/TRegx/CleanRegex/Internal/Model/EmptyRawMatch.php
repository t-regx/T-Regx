<?php
namespace TRegx\CleanRegex\Internal\Model;


class EmptyRawMatch implements RawMatchGroupable
{
    public function matched(): bool
    {
        return false;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return false;
    }

    public function getGroup($nameOrIndex): ?string
    {
        return null;
    }

    public function getGroupOffset($nameOrIndex): ?int
    {
        return null;
    }
}
