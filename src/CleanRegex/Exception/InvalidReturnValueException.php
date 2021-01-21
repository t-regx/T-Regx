<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class InvalidReturnValueException extends \Exception implements PatternException
{
    public function __construct($returnValue, string $methodName, string $expectedReturnType)
    {
        $type = Type::asString($returnValue);
        parent::__construct("Invalid $methodName() callback return type. Expected $expectedReturnType, but $type given");
    }

    public static function forArrayReturning(string $method, $value): self
    {
        return new self($value, $method, 'array');
    }

    public static function forFilter($value): self
    {
        return new self($value, 'filter', 'bool');
    }

    public static function forGroupByCallback($value): self
    {
        return new self($value, 'groupByCallback', 'int|string');
    }

    public static function forOtherwise($value): self
    {
        return new self($value, 'otherwise', 'string');
    }
}
