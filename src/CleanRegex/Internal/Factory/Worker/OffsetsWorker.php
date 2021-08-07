<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NthOffsetMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthMatchOffsetMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;

class OffsetsWorker implements StreamWorker
{
    /** @var Subjectable */
    private $subjectable;

    public function __construct(Subjectable $subjectable)
    {
        $this->subjectable = $subjectable;
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
        return new SubjectOptionalWorker(new FirstMatchOffsetMessage(), $this->subjectable, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthOffsetMessage($nth, $total), $this->subjectable, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthMatchOffsetMessage($nth), $this->subjectable, SubjectNotMatchedException::class);
    }
}
