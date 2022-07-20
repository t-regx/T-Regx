<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Subject;

interface GroupFactoryStrategy
{
    public function matched(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted): MatchedGroup;

    public function notMatched(Subject $subject, GroupDetails $details): NotMatchedGroup;
}
