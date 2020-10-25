<?php
namespace TRegx\SafeRegex\Exception;

use TRegx\RegexException;

abstract class PregException extends RegexException
{
    /** @var string */
    private $methodName;
    /** @var string|array */
    private $pattern;

    public function __construct(string $methodName, $pattern, ?string $message)
    {
        parent::__construct($message);
        $this->methodName = $methodName;
        $this->pattern = $pattern;
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
