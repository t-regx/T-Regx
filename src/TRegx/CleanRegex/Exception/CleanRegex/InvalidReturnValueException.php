<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Internal\StringValue;

class InvalidReturnValueException extends CleanRegexException
{
    /**
     * @param $returnValue
     */
    public function __construct($returnValue)
    {
        $type = (new StringValue($returnValue))->getString();
        parent::__construct("Invalid replace callback return type. Expected array, but $type given");
    }
}
