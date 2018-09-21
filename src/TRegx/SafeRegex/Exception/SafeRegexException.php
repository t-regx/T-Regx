<?php
namespace TRegx\SafeRegex\Exception;

abstract class SafeRegexException extends \Exception
{
    /** @var string */
    private $methodName;

    public function __construct(string $methodName, string $message = null)
    {
        parent::__construct($message);
        $this->methodName = $methodName;
    }

    public function getInvokingMethod(): string
    {
        return $this->methodName;
    }
}
