<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Internal\Type;

class FluentMatchPatternException extends CleanRegexException
{
    public static function forInvalidInteger($value): FluentMatchPatternException
    {
        $type = Type::asString($value);
        return new self("Invalid data types passed to `asInt()` method. Expected 'string' or 'int', but $type given");
    }
}
