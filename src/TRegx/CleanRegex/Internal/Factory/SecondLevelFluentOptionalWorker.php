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
    /** @var string|null */
    private $subject;

    public function __construct(NotMatchedMessage $message)
    {
        parent::__construct($message, '');
        $this->message = $message;
        $this->subject = null;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        $factory = new SignatureExceptionFactory($exceptionClassName, $this->message);
        if ($this->subject === null) {
            return $factory->createWithoutSubject();
        }
        return $factory->create($this->subject);
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
