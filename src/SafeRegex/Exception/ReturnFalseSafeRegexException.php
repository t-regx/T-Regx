<?php
namespace SafeRegex\Exception;

class ReturnFalseSafeRegexException extends SafeRegexException
{
    /** @var mixed */
    private $returnValue;

    /**
     * @param string $methodName
     * @param mixed  $returnValue
     */
    public function __construct(string $methodName, $returnValue)
    {
        parent::__construct($methodName);
        $this->returnValue = $returnValue;

        parent::__construct("Invoking $methodName() resulted in 'false'.");
    }
}
