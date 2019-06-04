<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;

class LazyMessageThrowStrategy implements ReplaceSubstitute
{
    /** @var string */
    private $className;

    /** @var NotMatchedMessage */
    private $message = null;

    public function __construct(string $className)
    {
        $this->className = $className;
    }

    public function substitute(string $subject): ?string
    {
        $className = $this->className;
        throw new $className($this->message->getMessage());
    }

    public static function internalException(): LazyMessageThrowStrategy
    {
        return new self(InternalCleanRegexException::class);
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
        $this->message = $message;
    }
}
