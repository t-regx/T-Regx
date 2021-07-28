<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class NonexistentGroupException extends \Exception implements PatternException
{
    public function __construct(GroupKey $groupId)
    {
        parent::__construct("Nonexistent group: $groupId");
    }
}
