<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Messages\NthOffsetMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\NthMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Subject;

class OffsetsWorker implements StreamWorker
{
    /** @var Subject */
    private $subject;

    public function __construct(Subject $subject)
    {
        $this->subject = $subject;
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
        return new SubjectOptionalWorker(new FirstMatchOffsetMessage(), $this->subject, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthOffsetMessage($nth, $total), $this->subject, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthMatchOffsetMessage($nth), $this->subject, SubjectNotMatchedException::class);
    }
}
