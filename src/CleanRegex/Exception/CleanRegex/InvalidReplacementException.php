<?php
namespace CleanRegex\Exception\CleanRegex;

use CleanRegex\Internal\StringValue;

class InvalidReplacementException extends CleanRegexException
{
    /**
     * @param mixed $replacement
     */
    public function __construct($replacement)
    {
        parent::__construct('Invalid replace callback return type: ' . (new StringValue($replacement))->getString());
    }
}
