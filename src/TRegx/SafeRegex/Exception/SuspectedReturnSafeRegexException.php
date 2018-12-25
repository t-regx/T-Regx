<?php
namespace TRegx\SafeRegex\Exception;

class SuspectedReturnSafeRegexException extends SafeRegexException
{
    /** @var mixed */
    private $returnValue;

    public function __construct(string $methodName, string $returnValue)
    {
        parent::__construct($methodName, "Invoking $methodName() resulted in '$returnValue'.");
        $this->returnValue = $returnValue;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }
}
