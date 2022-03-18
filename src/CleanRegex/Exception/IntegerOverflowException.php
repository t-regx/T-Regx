<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Numeral\Base;

class IntegerOverflowException extends \Exception implements PatternException
{
    public static function forMatch(string $value, Base $base): self
    {
        return new self("Expected to parse '$value', but it exceeds integer size on this architecture in base $base");
    }

    public static function forGroup(GroupKey $group, string $value, Base $base): self
    {
        return new self("Expected to parse group $group, but '$value' exceeds integer size on this architecture in base $base");
    }

    public static function forStream(string $value, Base $base): self
    {
        return new self("Expected to parse stream element '$value', but it exceeds integer size on this architecture in base $base");
    }
}
