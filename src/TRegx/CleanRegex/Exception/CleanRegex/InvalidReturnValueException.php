<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Internal\StringValue;

class InvalidReturnValueException extends CleanRegexException
{
    /**
     * @param $returnValue
     * @param $methodName
     * @param $expectedReturnType
     */
    public function __construct($returnValue, $methodName, $expectedReturnType)
    {
        $type = (new StringValue($returnValue))->getString();
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
}
