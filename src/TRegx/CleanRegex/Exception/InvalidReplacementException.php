<?php
namespace TRegx\CleanRegex\Exception;

class InvalidReplacementException extends InvalidReturnValueException
{
    public function __construct($replacement)
    {
        parent::__construct($replacement, 'callback', 'string');
    }
}
