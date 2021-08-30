<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

class MatchGroupFactoryStrategy implements GroupFactoryStrategy
{
    public function matched(Subject $subject, GroupDetails $details, GroupEntry $entry, SubstitutedGroup $substituted): MatchedGroup
    {
        return new MatchedGroup($subject, $details, $entry, $substituted);
    }

    public function notMatched(Subject $subject, GroupDetails $details, NotMatchedOptionalWorker $worker): NotMatchedGroup
    {
        return new NotMatchedGroup($subject, $details, $worker);
    }
}
