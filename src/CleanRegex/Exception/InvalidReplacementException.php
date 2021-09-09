<?php
namespace TRegx\CleanRegex\Exception;

use TRegx\CleanRegex\Internal\Type\Type;

class InvalidReplacementException extends InvalidReturnValueException
{
    public function __construct(Type $replacementType)
    {
        parent::__construct('callback', 'string', $replacementType);
    }
}
