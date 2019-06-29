<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;

class NotMatchedFluentOptionalWorker implements NotMatchedWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var Subjectable */
    private $subject;

    public function __construct(NotMatchedMessage $message, Subjectable $subject)
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
}
