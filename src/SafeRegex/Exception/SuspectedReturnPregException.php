<?php
namespace TRegx\SafeRegex\Exception;

class SuspectedReturnPregException extends \RuntimeException implements PregException
{
    /** @var string */
    private $methodName;
    /** @var string|string[] */
    private $pattern;
    /** @var mixed */
    private $returnValue;

    public function __construct(string $methodName, $pattern, $returnValue)
    {
        parent::__construct("Invoking $methodName() resulted in '$returnValue'.");
        $this->methodName = $methodName;
        $this->pattern = $pattern;
        $this->returnValue = $returnValue;
    }

    public function getInvokingMethod(): string
    {
        return $this->methodName;
    }

    /**
     * @return string|string[]
     */
    public function getPregPattern()
    {
        return $this->pattern;
    }

    public function getReturnValue()
    {
        return $this->returnValue;
    }
}
