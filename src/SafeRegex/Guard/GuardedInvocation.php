<?php
namespace SafeRegex\Guard;

use Exception;

class GuardedInvocation
{
    /** @var mixed */
    private $result;
    /** @var Exception|null */
    private $exception;

    /**
     * @param mixed          $result
     * @param Exception|null $exception
     */
    public function __construct($result, ?Exception $exception)
    {
        $this->result = $result;
        $this->exception = $exception;
    }

    public function getException(): ?Exception
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
