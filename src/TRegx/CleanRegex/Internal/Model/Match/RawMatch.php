<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

class RawMatch implements IRawMatch
{
    private const GROUP_WHOLE_MATCH = 0;

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
        return $this->match[self::GROUP_WHOLE_MATCH];
    }
}
