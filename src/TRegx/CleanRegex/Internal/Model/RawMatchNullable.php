<?php
namespace TRegx\CleanRegex\Internal\Model;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;

class RawMatchNullable implements IRawMatch, IRawMatchGroupable
{
    /** @var array */
    private $match;

    public function __construct(array $match)
    {
        $this->match = $match;
    }

    public function matched(): bool
    {
        return !empty($this->match);
    }

    public function getMatch(): string
    {
        throw new InternalCleanRegexException();
    }

    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->match);
    }

    public function getGroup($nameOrIndex): ?string
    {
        $value = $this->match[$nameOrIndex];
        if ($value === null) {
            return null;
        }
        return $value;
    }

    public function getGroupOffset($nameOrIndex): ?int
    {
        $value = $this->match[$nameOrIndex];
        if ($value === null) {
            return null;
        }
        return $value;
    }
}
