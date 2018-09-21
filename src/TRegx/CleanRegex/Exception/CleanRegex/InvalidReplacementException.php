<?php
namespace TRegx\CleanRegex\Exception\CleanRegex;

use TRegx\CleanRegex\Internal\StringValue;

class InvalidReplacementException extends CleanRegexException
{
    /**
     * @param mixed $replacement
     */
    public function __construct($replacement)
    {
        $type = (new StringValue($replacement))->getString();
        parent::__construct("Invalid replace callback return type. Expected string, but $type given");
    }
}
