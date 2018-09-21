<?php
namespace TRegx\SafeRegex\Errors;

use TRegx\SafeRegex\Errors\Errors\BothHostError;
use TRegx\SafeRegex\Errors\Errors\CompileError;
use TRegx\SafeRegex\Errors\Errors\EmptyHostError;
use TRegx\SafeRegex\Errors\Errors\RuntimeError;

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
        $compile = CompileError::getLast();
        $runtime = RuntimeError::getLast();

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
