<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Messages\FirstFluentMatchMessage;
use TRegx\CleanRegex\Internal\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Messages\NthFluentMessage;
use TRegx\CleanRegex\Internal\Messages\Subject\NthMatchFluentMessage;

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
