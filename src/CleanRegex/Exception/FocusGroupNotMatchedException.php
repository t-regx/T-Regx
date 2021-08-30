<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class FocusGroupNotMatchedException extends GroupNotMatchedException
{
    public function __construct(string $subject, GroupKey $group)
    {
        parent::__construct("Expected to replace focused group $group, but the group was not matched");
    }
}
