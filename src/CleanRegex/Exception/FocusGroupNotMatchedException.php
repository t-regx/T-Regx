<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class FocusGroupNotMatchedException extends GroupNotMatchedException
{
    public function __construct(string $subject, GroupKey $groupId)
    {
        parent::__construct("Expected to replace focused group $groupId, but the group was not matched", $subject, $groupId);
    }
}
