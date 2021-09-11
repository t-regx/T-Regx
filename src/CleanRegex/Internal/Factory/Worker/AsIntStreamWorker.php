<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Messages\NthAsIntMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\NthMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Subject;

class AsIntStreamWorker implements StreamWorker
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
        return new SubjectOptionalWorker(new FirstMatchAsIntMessage(), $this->subject, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthAsIntMessage($nth, $total), $this->subject, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthMatchAsIntMessage($nth), $this->subject, SubjectNotMatchedException::class);
    }
}
