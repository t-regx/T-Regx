<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type\Type;

class InvalidIntegerTypeException extends \Exception implements PatternException
{
    public static function forInvalidType(Type $type): self
    {
        return new self("Failed to parse value as integer. Expected integer|string, but $type given");
    }
}
