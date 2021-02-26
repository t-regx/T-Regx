<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Exception\NoSuchNthElementException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Exception\Messages\NthAsIntMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\FirstMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\Subject\NthMatchAsIntMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;

class AsIntStreamWorker implements StreamWorker
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
        return new SubjectOptionalWorker(new FirstMatchAsIntMessage(), $this->subjectable, SubjectNotMatchedException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthAsIntMessage($nth, $total), $this->subjectable, NoSuchNthElementException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new SubjectOptionalWorker(new NthMatchAsIntMessage($nth), $this->subjectable, SubjectNotMatchedException::class);
    }
}
