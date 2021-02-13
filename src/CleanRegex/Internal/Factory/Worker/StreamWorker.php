<?php
namespace TRegx\CleanRegex\Internal\Factory\Worker;

use TRegx\CleanRegex\Internal\Factory\Optional\OptionalWorker;

interface StreamWorker
{
    public function chainWorker(): StreamWorker;

    public function noFirstOptionalWorker(): OptionalWorker;

    public function unmatchedOptionalWorker(): OptionalWorker;
}
