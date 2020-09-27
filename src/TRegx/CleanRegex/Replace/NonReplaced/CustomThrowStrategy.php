<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\SignatureExceptionFactory;
use TRegx\CleanRegex\Match\Details\Match;

class CustomThrowStrategy implements SubjectRs, MatchRs
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

    public function substitute(string $subject): string
    {
        return $this->throwWithSubject($subject);
    }

    public function substituteGroup(Match $match): string
    {
        return $this->throwWithSubject($match->subject());
    }

    private function throwWithSubject(string $subject): string
    {
        throw (new SignatureExceptionFactory($this->className, $this->message))->create($subject);
    }
}
