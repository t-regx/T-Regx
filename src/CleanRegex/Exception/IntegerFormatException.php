<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Number\Base;

class IntegerFormatException extends \Exception implements PatternException
{
    public static function forGroup(GroupKey $group, string $value, Base $base): self
    {
        return new self("Expected to parse group $group, but '$value' is not a valid integer in base $base");
    }

    public static function forMatch(string $value, Base $base): self
    {
        return new self("Expected to parse '$value', but it is not a valid integer in base $base");
    }

    public static function forFluent(string $value, Base $base): self
    {
        return new self("Expected to parse fluent element '$value', but it is not a valid integer in base $base");
    }
}
