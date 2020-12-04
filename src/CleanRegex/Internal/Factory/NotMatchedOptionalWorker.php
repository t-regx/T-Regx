<?php
namespace TRegx\CleanRegex\Internal\Factory;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subjectable;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements NotMatchedWorker
{
    /** @var NotMatchedMessage */
    private $message;
    /** @var Subjectable */
    private $subject;
    /** @var NotMatched */
    private $notMatched;

    public function __construct(NotMatchedMessage $message, Subjectable $subject, NotMatched $notMatched)
    {
        $this->message = $message;
        $this->subject = $subject;
        $this->notMatched = $notMatched;
    }

    public function orThrow(string $exceptionClassName): Throwable
    {
        return (new SignatureExceptionFactory($exceptionClassName, $this->message))->create($this->subject->getSubject());
    }

    public function orElse(callable $producer)
    {
        return $producer($this->notMatched);
    }
}
