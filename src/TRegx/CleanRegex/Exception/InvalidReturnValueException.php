<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class InvalidReturnValueException extends PatternException
{
    /**
     * @param $returnValue
     * @param $methodName
     * @param $expectedReturnType
     */
    public function __construct($returnValue, $methodName, $expectedReturnType)
    {
        $type = Type::asString($returnValue);
        parent::__construct("Invalid $methodName() callback return type. Expected $expectedReturnType, but $type given");
    }

    public static function forFlatMap($value): self
    {
        return new self($value, 'flatMap', 'array');
    }

    public static function forFilter($value): self
    {
        return new self($value, 'filter', 'bool');
    }

    public static function forGroupByCallback($value): self
    {
        return new self($value, 'groupByCallback', 'int|string');
    }
}
