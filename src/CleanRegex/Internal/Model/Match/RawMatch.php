<?php
namespace TRegx\CleanRegex\Internal\Model\Match;

/**
 * @deprecated
 */
class RawMatch
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
        return $this->match[0];
    }
}
