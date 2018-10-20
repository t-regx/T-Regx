<?php
namespace TRegx\CleanRegex\Internal\Model;

class RawMatch
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

    public function getMatch()
    {
        return $this->match[self::GROUP_WHOLE_MATCH];
    }
}
