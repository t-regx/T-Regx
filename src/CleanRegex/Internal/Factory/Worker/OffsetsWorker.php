<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Messages\NthOffsetMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\NthMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

class OffsetsWorker implements StreamWorker
{
    /** @var Subject */
    private $subject;
    /** @var NotMatched */
    private $notMatched;

    public function __construct(GroupAware $groupAware, Subject $subject)
    {
        $this->subject = $subject;
        $this->notMatched = new NotMatched($groupAware, $subject);
    }

    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirst(): OptionalWorker
    {
        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function unmatchedFirst(): OptionalWorker
    {
        return new NotMatchedOptionalWorker(new FirstMatchOffsetMessage(), $this->subject, $this->notMatched, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthOffsetMessage($nth, $total), $this->subject, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new NotMatchedOptionalWorker(new NthMatchOffsetMessage($nth), $this->subject, $this->notMatched, SubjectNotMatchedException::class);
    }
}
