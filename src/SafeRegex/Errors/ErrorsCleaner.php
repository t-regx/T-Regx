<?php
namespace SafeRegex\Errors;

use SafeRegex\Errors\Errors\BothHostError;
use SafeRegex\Errors\Errors\EmptyHostError;
use SafeRegex\Errors\Errors\PhpHostError;
use SafeRegex\Errors\Errors\PregHostError;

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
        $preg = PregHostError::get();

        if ($preg->occurred() && $php->occurred()) {
            return new BothHostError($php, $preg);
        }

        if ($php->occurred()) return $php;
        if ($preg->occurred()) return $preg;

        return new EmptyHostError();
    }
}
