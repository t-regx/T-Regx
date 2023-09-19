<?php
namespace Regex;

use Regex\Internal\GroupKey;

final class GroupException extends RegexException
{
    public function __construct(GroupKey $group, string $verb)
    {
        parent::__construct("Capturing group $verb: $group.");
    }
}
