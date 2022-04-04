<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use Exception;
use Throwable;
use TRegx\CleanRegex\Internal\Message\Message;

abstract class StreamRejectedException extends Exception
{
    /** @var Message */
    protected $exceptionMessage;

    public function __construct(Message $message)
    {
        parent::__construct();
        $this->exceptionMessage = $message;
    }

    public abstract function throwable(): Throwable;

    public function notMatchedMessage(): Message
    {
        return $this->exceptionMessage;
    }
}
