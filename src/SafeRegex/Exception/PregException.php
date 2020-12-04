<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\RegexException;

abstract class PregException extends RegexException
{
    /** @var string|array */
    private $pattern;
    /** @var string */
    private $methodName;

    public function __construct(?string $message, $pattern, string $methodName)
    {
        parent::__construct($message);
        $this->pattern = $pattern;
        $this->methodName = $methodName;
    }

    public function getInvokingMethod(): string
    {
        return $this->methodName;
    }

    public function getPregPattern()
    {
        return $this->pattern;
    }
}
