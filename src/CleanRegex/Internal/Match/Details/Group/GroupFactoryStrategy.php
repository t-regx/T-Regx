<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;
use TRegx\CleanRegex\Match\Details\NotMatched;

interface GroupFactoryStrategy
{
    public function matched(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted): MatchedGroup;

    public function notMatched(Subject $subject, GroupDetails $details, NotMatched $notMatched): NotMatchedGroup;
}
