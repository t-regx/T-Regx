<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Exception\NoSuchElementFluentException;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstFluentMessage;
use TRegx\CleanRegex\Internal\Exception\Messages\FirstMatchFluentMessage;
use TRegx\CleanRegex\Internal\Factory\Optional\ArgumentlessOptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

class MatchStreamWorker implements StreamWorker
{
    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirstOptionalWorker(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FirstFluentMessage(), NoSuchElementFluentException::class);
    }

    public function unmatchedOptionalWorker(): OptionalWorker
    {
        return new ArgumentlessOptionalWorker(new FirstMatchFluentMessage(), NoSuchElementFluentException::class);
    }
}
