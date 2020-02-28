<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;

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

    public function getText(): string
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function hasGroup($nameOrIndex): bool
    {
        return \array_key_exists($nameOrIndex, $this->match);
    }

    // TODO fix getGroup() and getGroupByteOffset()
    public function getGroup($nameOrIndex): ?string
    {
        $value = $this->match[$nameOrIndex];
        if ($value === null) {
            return null;
        }
        return $value;
    }

    public function getGroupByteOffset($nameOrIndex): ?int
    {
        $value = $this->match[$nameOrIndex];
        if ($value === null) {
            return null;
        }
        return $value;
    }
}
