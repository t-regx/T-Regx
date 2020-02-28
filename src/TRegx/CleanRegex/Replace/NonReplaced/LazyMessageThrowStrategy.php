<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Match\Details\Match;

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
        return $this->doSubstitute();
    }

    public function substituteGroup(Match $match): ?string
    {
        return $this->doSubstitute();
    }

    private function doSubstitute(): string
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
