<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\NthFluentMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

class FluentStreamWorker implements StreamWorker
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
        return new ArgumentlessOptionalWorker(new FirstFluentMessage(), NoSuchElementFluentException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new NthFluentMessage($nth, $total), NoSuchElementFluentException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new NthFluentMessage($nth, 0), NoSuchElementFluentException::class);
    }
}
