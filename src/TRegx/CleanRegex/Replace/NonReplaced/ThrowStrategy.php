<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\SubjectableImpl;

class ThrowStrategy implements NonReplacedStrategy
{
    /** @var string */
    private $className;
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(string $exceptionClassName, NotMatchedMessage $message)
    {
        $this->className = $exceptionClassName;
        $this->message = $message;
    }

    public function replacementResult(string $subject): ?string
    {
        throw (new SignatureExceptionFactory($this->className, $this->message))
            ->create(new SubjectableImpl($subject));
    }
}
