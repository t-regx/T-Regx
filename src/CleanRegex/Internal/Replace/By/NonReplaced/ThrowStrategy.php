<?php
namespace TRegx\CleanRegex\Internal\Replace\By\NonReplaced;

use TRegx\CleanRegex\Internal\ClassName;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\StringSubject;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\Detail;

class ThrowStrategy implements SubjectRs, MatchRs
{
    /** @var ClassName */
    private $className;
    /** @var NotMatchedMessage */
    private $message;

    public function __construct(string $className, NotMatchedMessage $message)
    {
        $this->className = new ClassName($className);
        $this->message = $message;
    }

    public function substitute(Subject $subject): string
    {
        throw $this->className->throwable($this->message, $subject);
    }

    public function substituteGroup(Detail $detail): string
    {
        throw $this->className->throwable($this->message, new StringSubject($detail->subject()));
    }
}
