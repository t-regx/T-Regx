<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Exception;
use Throwable;
use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class SecondLevelFluentOptionalWorker extends FluentOptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(NotMatchedMessage $message)
    {
        parent::__construct($message, '');
        $this->message = $message;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        $factory = new SignatureExceptionFactory($exceptionClassName, $this->message);
        return $factory->createWithoutSubject();
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }

    public function noFirstElementException(): Exception
    {
        return NoSuchElementFluentException::withMessage($this->message);
    }
}
