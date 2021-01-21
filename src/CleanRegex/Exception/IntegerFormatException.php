<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class IntegerFormatException extends \Exception implements PatternException
{
    public static function forGroup($nameOrIndex, string $value): self
    {
        $group = Type::group($nameOrIndex);
        return new self("Expected to parse group $group, but '$value' is not a valid integer");
    }

    public static function forMatch(string $value): self
    {
        return new self("Expected to parse '$value', but it is not a valid integer");
    }

    public static function forFluent(string $value): self
    {
        return new self("Expected to parse fluent element '$value', but it is not a valid integer");
    }
}
