<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

class InvalidReplacementException extends InvalidReturnValueException
{
    public function __construct($replacement)
    {
        parent::__construct($replacement, 'callback', 'string');
    }
}
