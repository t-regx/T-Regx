<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Exception\CleanRegex\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\Details\Match;

class CustomThrowStrategy implements ReplaceSubstitute
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

    public function substitute(string $subject): ?string
    {
        throw (new SignatureExceptionFactory($this->className, $this->message))
            ->create(new SubjectableImpl($subject));
    }

    public function substituteGroup(Match $match): ?string
    {
        return $this->substitute($match->subject());
    }

    public function useExceptionMessage(NotMatchedMessage $message): void
    {
    }
}
