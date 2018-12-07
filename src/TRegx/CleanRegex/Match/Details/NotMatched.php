<?php
namespace TRegx\CleanRegex\Match\Details;

use TRegx\CleanRegex\Internal\GroupNameValidator;
use TRegx\CleanRegex\Internal\Model\IRawWithGroups;
use TRegx\CleanRegex\Internal\Subjectable;
use function array_filter;
use function array_values;
use function count;

class NotMatched implements Details
{
    /** @var IRawWithGroups */
    private $matches;
    /** @var Subjectable */
    private $subject;

    public function __construct(IRawWithGroups $matches, Subjectable $subject)
    {
        $this->matches = $matches;
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
        return array_values(array_filter($this->matches->getGroupKeys(), '\is_string'));
    }

    public function groupsCount(): int
    {
        $indexedGroups = array_filter($this->matches->getGroupKeys(), '\is_int');
        return count($indexedGroups) - 1;
    }

    /**
     * @param string|int $nameOrIndex
     * @return bool
     */
    public function hasGroup($nameOrIndex): bool
    {
        (new GroupNameValidator($nameOrIndex))->validate();
        return $this->matches->hasGroup($nameOrIndex);
    }
}
