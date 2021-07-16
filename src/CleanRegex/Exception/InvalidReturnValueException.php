<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class InvalidReturnValueException extends \Exception implements PatternException
{
    public function __construct(string $methodName, string $expectedReturnType, Type $type)
    {
        parent::__construct("Invalid $methodName() callback return type. Expected $expectedReturnType, but $type given");
    }

    public static function forArrayReturning(string $method, Type $type): self
    {
        return new self($method, 'array', $type);
    }

    public static function forGroupByCallback(Type $type): self
    {
        return new self('groupByCallback', 'int|string', $type);
    }

    public static function forOtherwise(Type $type): self
    {
        return new self('otherwise', 'string', $type);
    }
}
