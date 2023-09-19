<?php
namespace Regex;

use Regex\Internal\GroupKey;

final class GroupException extends RegexException
{
    public function __construct(GroupKey $group)
    {
        parent::__construct("Capturing group does not exist: $group.");
    }
}
