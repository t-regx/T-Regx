<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;

class NotMatchedFluentOptionalWorker implements NotMatchedWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var string|null */
    private $subject;

    public function __construct(NotMatchedMessage $message, string $subject = null)
    {
        $this->message = $message;
        $this->subject = $subject;
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
}
