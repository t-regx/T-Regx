<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

interface StreamWorker
{
    public function undecorateWorker(): StreamWorker;

    public function noFirst(): OptionalWorker;

    public function unmatchedFirst(): OptionalWorker;

    public function noNth(int $nth, int $total): OptionalWorker;

    public function unmatchedNth(int $nth): OptionalWorker;
}
