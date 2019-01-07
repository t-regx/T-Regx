<?php
namespace TRegx\SafeRegex\Guard;

use TRegx\SafeRegex\Exception\SafeRegexException;

class GuardedInvocation
{
    /** @var mixed */
    private $result;
    /** @var SafeRegexException|null */
    private $exception;

    /**
     * @param mixed $result
     * @param SafeRegexException|null $exception
     */
    public function __construct($result, ?SafeRegexException $exception)
    {
        $this->result = $result;
        $this->exception = $exception;
    }

    public function getException(): ?SafeRegexException
    {
        return $this->exception;
    }

    public function hasException(): bool
    {
        return $this->exception !== null;
    }

    /**
     * @return mixed
     */
    public function getResult()
    {
        return $this->result;
    }
}
