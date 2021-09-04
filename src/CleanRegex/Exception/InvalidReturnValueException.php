<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type;

class InvalidReturnValueException extends \Exception implements PatternException
{
    public function __construct(string $methodName, string $expectedReturnType, Type $type)
    {
        parent::__construct("Invalid $methodName() callback return type. Expected $expectedReturnType, but $type given");
    }
}
