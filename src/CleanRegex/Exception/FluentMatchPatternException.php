<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class FluentMatchPatternException extends \Exception implements PatternException
{
    public static function forInvalidInteger(Type $type): self
    {
        return new self("Invalid data types passed to asInt() method. Expected integer|string, but $type given");
    }
}
