<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Exception;
use Throwable;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;

class PatternOptionalWorker implements OptionalWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var string */
    private $subject;
    /** @var string */
    private $optionalDefaultClass;

    public function __construct(NotMatchedMessage $message, string $subject, string $optionalDefaultClass)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->optionalDefaultClass = $optionalDefaultClass;
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName, $this->message))->create($this->subject);
    }

    public function noFirstElementException(): Exception
    {
        return SubjectNotMatchedException::withMessage($this->message, new Subject($this->subject));
    }

    public function chainWorker(): OptionalWorker
    {
        return new FluentOptionalWorker($this->message);
    }

    public function optionalDefaultClass(): string
    {
        return $this->optionalDefaultClass;
    }
}
