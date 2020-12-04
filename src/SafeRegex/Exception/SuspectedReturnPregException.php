<?php
namespace TRegx\SafeRegex\Exception;

class SuspectedReturnPregException extends PregException
{
    /** @var mixed */
    private $returnValue;

    public function __construct(string $methodName, $pattern, string $returnValue)
    {
        parent::__construct("Invoking $methodName() resulted in '$returnValue'.", $pattern, $methodName);
        $this->returnValue = $returnValue;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }
}
