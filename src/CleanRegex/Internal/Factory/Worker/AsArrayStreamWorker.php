<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NthAsArrayMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchAsArrayMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthMatchAsArrayMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;

class AsArrayStreamWorker implements StreamWorker
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
        return new SubjectOptionalWorker(new FirstMatchAsArrayMessage(), $this->subjectable, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthAsArrayMessage($nth, $total), $this->subjectable, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthMatchAsArrayMessage($nth), $this->subjectable, SubjectNotMatchedException::class);
    }
}
