<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;

class IntegerOverflowException extends \Exception implements PatternException
{
    public static function forGroup(GroupKey $group, string $value): self
    {
        return new self("Expected to parse group $group, but '$value' exceeds integer size on this architecture");
    }

    public static function forMatch(string $value): self
    {
        return new self("Expected to parse '$value', but it exceeds integer size on this architecture");
    }

    public static function forFluent(string $value): self
    {
        return new self("Expected to parse fluent element '$value', but it exceeds integer size on this architecture");
    }
}
