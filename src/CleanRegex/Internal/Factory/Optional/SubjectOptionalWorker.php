<?php
namespace TRegx\CleanRegex\Internal\Factory\Optional;

use Throwable;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\Subject;

class SubjectOptionalWorker implements OptionalWorker
{
    /** @var SignatureExceptionFactory */
    private $exceptionFactory;
    /** @var Subject */
    private $subject;
    /** @var string */
    private $defaultExceptionClassname;

    public function __construct(NotMatchedMessage $message, Subject $subject, string $defaultExceptionClassname)
    {
        $this->exceptionFactory = new SignatureExceptionFactory($message);
        $this->subject = $subject;
        $this->defaultExceptionClassname = $defaultExceptionClassname;
    }

    public function arguments(): array
    {
        return [];
    }

    public function throwable(?string $exceptionClassname): Throwable
    {
        return $this->exceptionFactory->create($exceptionClassname ?? $this->defaultExceptionClassname, $this->subject);
    }
}
