<?php
namespace TRegx\SafeRegex\Exception;

class SuspectedReturnPregException extends PregException
{
    /** @var mixed */
    private $returnValue;

    public function __construct(string $methodName, $pattern, string $returnValue)
    {
        parent::__construct($methodName, $pattern, "Invoking $methodName() resulted in '$returnValue'.");
        $this->returnValue = $returnValue;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }
}
