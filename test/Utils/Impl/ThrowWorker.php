<?php
namespace Test\Utils\Impl;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;
use TRegx\CleanRegex\Internal\Factory\Worker\StreamWorker;

class ThrowWorker implements StreamWorker
{
    public function undecorateWorker(): StreamWorker
    {
        return $this;
    }

    public function noFirst(): OptionalWorker
    {
        throw new \AssertionError("Failed to assert that StreamWorker wasn't used");
    }

    public function unmatchedFirst(): OptionalWorker
    {
        throw new \AssertionError("Failed to assert that StreamWorker wasn't used");
    }

    public function noNth(int $nth, int $total): OptionalWorker
    {
        throw new \AssertionError("Failed to assert that StreamWorker wasn't used");
    }

    public function unmatchedNth(int $nth): OptionalWorker
    {
        throw new \AssertionError("Failed to assert that StreamWorker wasn't used");
    }
}
