<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\InternalCleanRegexException;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

class NextStreamWorkerDecorator implements StreamWorker
{
    /** @var FluentStreamWorker */
    private $worker;
    /** @var StreamWorker */
    private $currentWorker;

    public function __construct(StreamWorker $nextWorker, StreamWorker $currentWorker)
    {
        $this->worker = $nextWorker;
        $this->currentWorker = $currentWorker;
    }

    public function undecorateWorker(): StreamWorker
    {
        return $this->worker;
    }

    public function noFirst(): OptionalWorker
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

    public function unmatchedFirst(): OptionalWorker
    {
        return $this->currentWorker->unmatchedFirst();
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return $this->currentWorker->noNth($nth, $total);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return $this->currentWorker->unmatchedNth($nth);
    }
}
