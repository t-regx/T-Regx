<?php
namespace TRegx\CleanRegex\Internal\Factory;

interface OptionalWorker extends NotMatchedWorker
{
    public function noFirstElementException(): \Exception;

    public function chainWorker(): OptionalWorker;

    public function optionalDefaultClass(): string;
}
