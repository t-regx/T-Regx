<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class MatchAllResults
{
    /** @var IRawMatchesOffset */
    private $matches;
    /** @var string|int */
    private $group;

    public function __construct(IRawMatchesOffset $matches, $group)
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
