<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Match\Rejection;
use TRegx\CleanRegex\Internal\Message\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Match\Details\NotMatched;
use TRegx\CleanRegex\Match\Optional;

class SubjectEmptyOptional implements Optional
{
    use EmptyOptional;

    /** @var NotMatched */
    private $notMatched;
    /** @var Rejection */
    private $rejection;

    public function __construct(GroupAware $groupAware, Subject $subject, NotMatchedMessage $message)
    {
        $this->notMatched = new NotMatched($groupAware, $subject);
        $this->rejection = new Rejection($subject, SubjectNotMatchedException::class, $message);
    }

    public function orElse(callable $substituteProducer)
    {
        return $substituteProducer($this->notMatched);
    }

    public function orThrow(string $exceptionClassName = null): void
    {
        $this->rejection->throw($exceptionClassName);
    }
}
