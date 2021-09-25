<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements OptionalWorker
{
    /** @var SignatureExceptionFactory */
    private $exceptionFactory;
    /** @var NotMatched */
    private $notMatched;
    /** @var Subject */
    private $subject;
    /** @var string */
    private $fallbackClassname;

    public function __construct(NotMatchedMessage $message, Subject $subject, NotMatched $notMatched, string $fallbackClassname)
    {
        $this->exceptionFactory = new SignatureExceptionFactory($message);
        $this->notMatched = $notMatched;
        $this->subject = $subject;
        $this->fallbackClassname = $fallbackClassname;
    }

    public function arguments(): array
    {
        return [$this->notMatched];
    }

    public function throwable(?string $exceptionClassname): Throwable
    {
        return $this->exceptionFactory->create($exceptionClassname ?? $this->fallbackClassname, $this->subject);
    }
}
