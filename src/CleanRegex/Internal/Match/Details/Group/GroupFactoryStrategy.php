<?php
namespace TRegx\CleanRegex\Internal\Match\Details\Group;

use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Group\MatchedGroup;
use TRegx\CleanRegex\Match\Details\Group\NotMatchedGroup;

interface GroupFactoryStrategy
{
    public function createMatched(Subject          $subject,
                                  GroupDetails     $details,
                                  GroupEntry       $groupEntry,
                                  SubstitutedGroup $substitutedGroup): MatchedGroup;

    public function createUnmatched(Subject                  $subject,
                                    GroupDetails             $details,
                                    NotMatchedOptionalWorker $worker): NotMatchedGroup;
}
