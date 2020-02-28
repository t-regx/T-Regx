<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

class EmptyRawMatch implements IRawMatch, IRawMatchGroupable
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

    public function getGroupByteOffset($nameOrIndex): ?int
    {
        return null;
    }

    public function getText(): string
    {
        throw new InternalCleanRegexException();
    }
}
