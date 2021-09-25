<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Message\FirstFluentMatchMessage;
use TRegx\CleanRegex\Internal\Message\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Message\NthFluentMessage;
use TRegx\CleanRegex\Internal\Message\Subject\NthMatchFluentMessage;

class MatchStreamWorker implements StreamWorker
{
    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirst(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FirstFluentMessage(), NoSuchElementFluentException::class);
    }

    public function unmatchedFirst(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FirstFluentMatchMessage(), NoSuchElementFluentException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new NthFluentMessage($nth, $total), NoSuchElementFluentException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new NthMatchFluentMessage($nth), NoSuchElementFluentException::class);
    }
}
