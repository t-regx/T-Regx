<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\InternalExceptionMessage;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\SubjectableImpl;

class ThrowStrategy implements NonReplacedStrategy
{
    /** @var string */
    private $className;
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(string $className, NotMatchedMessage $message)
    {
        $this->className = $className;
        $this->message = $message;
    }

    public function replacementResult(string $subject): ?string
    {
        throw (new SignatureExceptionFactory($this->className, $this->message))
            ->create(new SubjectableImpl($subject));
    }

    public static function internalException(): ThrowStrategy
    {
        return new self(InternalCleanRegexException::class, new InternalExceptionMessage());
    }
}
