<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Subject;

class MatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    public function matched(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted): MatchedGroup
    {
        return new MatchedGroup($subject, $details, $entry, $substituted);
    }

    public function notMatched(Subject $subject, GroupDetails $details): NotMatchedGroup
    {
        return new NotMatchedGroup($subject, $details);
    }
}
