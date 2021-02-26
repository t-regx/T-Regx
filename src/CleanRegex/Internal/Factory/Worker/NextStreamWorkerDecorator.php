<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Exception\Messages\NotMatchedMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\SubjectOptionalWorker;
use TRegx\CleanRegex\Internal\Subjectable;

class NextStreamWorkerDecorator implements StreamWorker
{
    /** @var FluentStreamWorker */
    private $worker;
    /** @var NotMatchedMessage */
    private $subjectMessage;
    /** @var Subjectable */
    private $subjectable;
    /** @var string */
    private $optionalDefaultClass;

    public function __construct(StreamWorker $nextWorker,
                                NotMatchedMessage $subjectMessage,
                                Subjectable $subjectable,
                                string $optionalDefaultClass)
    {
        $this->worker = $nextWorker;
        $this->subjectMessage = $subjectMessage;
        $this->subjectable = $subjectable;
        $this->optionalDefaultClass = $optionalDefaultClass;
    }

    public function undecorateWorker(): StreamWorker
    {
        return $this->worker;
    }

    public function noFirstOptionalWorker(): OptionalWorker
    {
        /*
         * It's not broken Liskov or interface segregation here, it's not that
         * this method is never called because of bad design. It's not supposed
         * to be called, but it's a T-Regx constraint, not language or
         * architecture constraint.
         *
         * PatternEmptyStreamWorker is supposed to be used as an early StreamWorker,
         * to throw close-to-subject exception message; slightly blurring the line
         * between MatchPattern and FluentMatchPattern.
         *
         * So it happens, that currently used Streams (array and int) that utilize
         * PatternEmptyStream never leave the stream empty, unless it was empty
         * to begin with. So using the array and int streams, noFirstOptionalWorker
         * is never going to be called. When there comes new stream (and I expect
         * that it will), then this method will have a chance to be called and
         * tested then.
         *
         * To design a stricter architecture that prohibits calling his method would
         * mean to wrap both the stream worker and the stream in another abstraction
         * before passing it to FluentMatchPattern.
         *
         * If this method is called, when there are no new streams, it's a sign of a bug.
         */

        // @codeCoverageIgnoreStart
        throw new InternalCleanRegexException();
        // @codeCoverageIgnoreEnd
    }

    public function unmatchedOptionalWorker(): OptionalWorker
    {
        return new SubjectOptionalWorker($this->subjectMessage, $this->subjectable, $this->optionalDefaultClass);
    }
}
