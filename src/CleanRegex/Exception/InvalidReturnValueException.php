<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type\Type;

class InvalidReturnValueException extends \RuntimeException implements PatternException
{
    public function __construct(string $methodName, string $expectedReturnType, Type $type)
    {
        parent::__construct("Invalid $methodName() callback return type. Expected $expectedReturnType, but $type given");
    }
}
