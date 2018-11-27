<?php
namespace TRegx\CleanRegex\Internal\Model\Adapter;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Model\IRawMatches;
use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;
use TRegx\CleanRegex\Internal\Model\IRawMatchOffset;

class RawMatchesToFirstMatchAdapter implements IRawMatchOffset
{
    /** @var IRawMatches */
    private $matches;

    public function __construct(IRawMatchesOffset $matches)
    {
        $this->matches = $matches;
    }

    public function matched(): bool
    {
        return $this->matches->matched();
    }

    public function getMatch(): string
    {
        $all = $this->matches->getAll();
        return $all[0];
    }

    public function hasGroup($nameOrIndex): bool
    {
        throw new InternalCleanRegexException();
    }

    public function getGroup($nameOrIndex): ?string
    {
        throw new InternalCleanRegexException();
    }

    public function getGroupOffset($nameOrIndex): ?int
    {
        throw new InternalCleanRegexException();
    }

    /**
     * @return (string|int)[]
     */
    public function getGroupKeys(): array
    {
        throw new InternalCleanRegexException();
    }
}
