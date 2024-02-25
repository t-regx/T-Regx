<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

/**
 * @deprecated
 */
class NonexistentGroupException extends \RuntimeException implements PatternException
{
    public function __construct(GroupKey $group)
    {
        parent::__construct("Nonexistent group: $group");
    }
}
