<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Message\Stream\FromFirstStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\FromNthStreamMessage;
use TRegx\CleanRegex\Internal\Message\Stream\SubjectNotMatched;

class MatchStreamWorker implements StreamWorker
{
    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirst(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FromFirstStreamMessage(), NoSuchElementFluentException::class);
    }

    public function unmatchedFirst(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new SubjectNotMatched\FromFirstStreamMessage(), NoSuchElementFluentException::class);
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FromNthStreamMessage($nth, $total), NoSuchElementFluentException::class);
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new SubjectNotMatched\FromNthStreamMessage($nth), NoSuchElementFluentException::class);
    }
}
