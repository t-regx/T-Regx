<?php
namespace TRegx\SafeRegex\Internal\Errors;

use TRegx\SafeRegex\Internal\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\CompileErrorFactory;
use TRegx\SafeRegex\Internal\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Internal\Errors\Errors\RuntimeErrorFactory;

class ErrorsCleaner
{
    public function clear(): void
    {
        $error = $this->getError();
        if ($error->occurred()) {
            $error->clear();
        }
    }

    public function getError(): HostError
    {
        $compile = CompileErrorFactory::getLast();
        $runtime = RuntimeErrorFactory::getLast();

        if ($runtime->occurred() && $compile->occurred()) {
            return new BothHostError($compile, $runtime);
        }

        if ($compile->occurred()) {
            return $compile;
        }
        if ($runtime->occurred()) {
            return $runtime;
        }

        return new EmptyHostError();
    }
}
