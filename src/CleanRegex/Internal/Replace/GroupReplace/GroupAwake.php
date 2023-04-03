<?php
namespace TRegx\CleanRegex\Internal\Replace\GroupReplace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Subject;

class GroupAwake
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var array|null */
    private $matches;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function groupMatched(int $index, GroupKey $group): bool
    {
        return $this->matches()[$group->nameOrIndex()][$index][1] !== -1;
    }

    private function matches(): array
    {
        if ($this->matches === null) {
            $this->matches = $this->performedMatches();
        }
        return $this->matches;
    }

    private function performedMatches(): array
    {
        \preg_match_all($this->definition->pattern, $this->subject, $matches, \PREG_OFFSET_CAPTURE);
        if (\preg_last_error() !== \PREG_NO_ERROR) {
            \preg_match('//', '');
        }
        return $matches;
    }
}
