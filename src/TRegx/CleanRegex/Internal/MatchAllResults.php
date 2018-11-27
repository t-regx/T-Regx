<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Model\Matches\IRawMatches;

class MatchAllResults
{
    /** @var IRawMatches */
    private $matches;
    /** @var string|int */
    private $group;

    public function __construct(IRawMatches $matches, $group)
    {
        $this->matches = $matches;
        $this->group = $group;
    }

    /**
     * @return (string|null)[]
     */
    public function all(): array
    {
        return $this->matches->getGroupTexts($this->group);
    }
}
