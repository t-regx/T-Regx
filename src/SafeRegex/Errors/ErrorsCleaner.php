<?php
namespace SafeRegex\Errors;

use SafeRegex\Errors\Errors\BothHostError;
use SafeRegex\Errors\Errors\EmptyHostError;
use SafeRegex\Errors\Errors\PhpHostError;
use SafeRegex\Errors\Errors\RuntimeError;

class ErrorsCleaner
{
    public function clear()
    {
        $error = $this->getError();

        if ($error->occurred()) {
            $error->clear();
        }
    }

    private function getError(): HostError
    {
        $php = PhpHostError::get();
        $runtime = RuntimeError::get();

        if ($runtime->occurred() && $php->occurred()) {
            return new BothHostError($php, $runtime);
        }

        if ($php->occurred()) return $php;
        if ($runtime->occurred()) return $runtime;

        return new EmptyHostError();
    }
}
