<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Exception;
use Throwable;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;

class FluentOptionalWorker implements NotMatchedWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var string */
    private $subject;

    public function __construct(NotMatchedMessage $message, string $subject)
    {
        $this->message = $message;
        $this->subject = $subject;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName, $this->message))->create($this->subject);
    }

    public function orElse(callable $producer)
    {
        return $producer();
    }

    public function noFirstElementException(): Exception
    {
        return SubjectNotMatchedException::withMessage($this->message, new Subject($this->subject));
    }
}
