<?php
namespace Danon\SafeRegex\Exception;

class ReturnFalseSafeRegexException extends SafeRegexException
{
    /** @var int */
    private $returnValue;

    public function __construct(string $methodName, mixed $returnValue)
    {
        parent::__construct($methodName);
        $this->returnValue = $returnValue;

        parent::__construct("Invoking $methodName() resulted in 'false'.");
    }
}
