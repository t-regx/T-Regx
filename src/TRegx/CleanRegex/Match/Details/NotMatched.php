<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\GroupNames;
use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Subjectable;
use function array_filter;
use function count;

class NotMatched implements Details
{
    /** @var IRawWithGroups */
    private $match;
    /** @var Subjectable */
    private $subject;

    public function __construct(IRawWithGroups $match, Subjectable $subject)
    {
        $this->match = $match;
        $this->subject = $subject;
    }

    public function subject(): string
    {
        return $this->subject->getSubject();
    }

    /**
     * @return string[]
     */
    public function groupNames(): array
    {
        return (new GroupNames($this->match))->groupNames();
    }

    public function groupsCount(): int
    {
        $indexedGroups = array_filter($this->match->getGroupKeys(), '\is_int');
        return count($indexedGroups) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return $this->match->hasGroup($nameOrIndex);
    }
}
