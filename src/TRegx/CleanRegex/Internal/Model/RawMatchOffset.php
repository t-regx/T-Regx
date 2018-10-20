<?php
namespace TRegx\CleanRegex\Internal\Model;

use function array_key_exists;

class RawMatchOffset implements RawMatchGroupable
{
    /** @var array[] */
    private $match;

    public function __construct(array $match)
    {
        $this->match = $match;
    }

    public function matched(): bool
    {
        return !empty($this->match);
    }

    public function getMatch()
    {
        list($text, $offset) = $this->match[0];
        return $text;
    }

    public function hasGroup($nameOrIndex): bool
    {
        return array_key_exists($nameOrIndex, $this->match);
    }

    public function getGroup($nameOrIndex): ?string
    {
        list($text, $offset) = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $text;
    }

    public function getGroupOffset($nameOrIndex): ?int
    {
        list($text, $offset) = $this->match[$nameOrIndex];
        if ($offset === -1) {
            return null;
        }
        return $offset;
    }
}
