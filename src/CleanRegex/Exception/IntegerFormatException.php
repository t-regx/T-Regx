<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Numeral\Base;

class IntegerFormatException extends \RuntimeException implements PatternException
{
    public static function forMatch(string $value, Base $base): self
    {
        return new self("Expected to parse '$value', but it is not a valid integer in base $base");
    }

    public static function forGroup(GroupKey $group, string $value, Base $base): self
    {
        return new self("Expected to parse group $group, but '$value' is not a valid integer in base $base");
    }

    public static function forStream(string $value, Base $base): self
    {
        return new self("Expected to parse stream element '$value', but it is not a valid integer in base $base");
    }
}
