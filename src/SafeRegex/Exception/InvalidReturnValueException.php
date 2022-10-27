<?php
namespace TRegx\SafeRegex\Exception;

class InvalidReturnValueException extends \RuntimeException implements PregException
{
    /** @var string */
    private $methodName;
    /** @var string|string[] */
    private $pattern;

    public function __construct($pattern, string $methodName, $returnType)
    {
        parent::__construct("Invalid $methodName() callback return type. Expected type that can be cast to string, but $returnType given");
        $this->methodName = $methodName;
        $this->pattern = $pattern;
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
}
