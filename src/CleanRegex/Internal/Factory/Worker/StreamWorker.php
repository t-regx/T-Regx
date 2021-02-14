<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

interface StreamWorker
{
    public function undecorateWorker(): StreamWorker;

    public function noFirstOptionalWorker(): OptionalWorker;

    public function unmatchedOptionalWorker(): OptionalWorker;
}
