<?php
namespace SafeRegex\Exception;

class SuspectedReturnSafeRegexException extends SafeRegexException
{
    /** @var mixed */
    private $returnValue;

    public function __construct(string $methodName, $returnValue)
    {
        parent::__construct("Invoking $methodName() resulted in 'false'.");
        $this->returnValue = $returnValue;
    }
}
