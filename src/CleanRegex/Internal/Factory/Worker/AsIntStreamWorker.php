<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Factory\Optional\NotMatchedOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Messages\NthAsIntMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\NthMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Model\GroupAware;
use TRegx\CleanRegex\Internal\Subject;
use TRegx\CleanRegex\Match\Details\NotMatched;

class AsIntStreamWorker implements StreamWorker
{
    /** @var NotMatched */
    private $notMatched;
    /** @var Subject */
    private $subject;

    public function __construct(GroupAware $groupAware, Subject $subject)
    {
        $this->notMatched = new NotMatched($groupAware, $subject);
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
        return new NotMatchedOptionalWorker(new FirstMatchAsIntMessage(), $this->subject, $this->notMatched, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new NotMatchedOptionalWorker(new NthAsIntMessage($nth, $total), $this->subject, $this->notMatched, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new NotMatchedOptionalWorker(new NthMatchAsIntMessage($nth), $this->subject, $this->notMatched, SubjectNotMatchedException::class);
    }
}
