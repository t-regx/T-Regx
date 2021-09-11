<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

class NotMatchedOptionalWorker implements OptionalWorker
{
    /** @var Subject */
    private $subject;
    /** @var NotMatched */
    private $notMatched;
    /** @var string */
    private $fallbackClassname;
    /** @var SignatureExceptionFactory */
    private $exceptionFactory;

    public function __construct(NotMatchedMessage $message,
                                Subject           $subject,
                                NotMatched        $notMatched,
                                string            $fallbackClassname)
    {
        $this->subject = $subject;
        $this->notMatched = $notMatched;
        $this->fallbackClassname = $fallbackClassname;
        $this->exceptionFactory = new SignatureExceptionFactory($message);
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
