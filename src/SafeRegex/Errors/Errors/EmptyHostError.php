<?php
namespace SafeRegex\Errors\Errors;

use SafeRegex\Errors\HostError;

class EmptyHostError implements HostError
{
    public function occurred(): bool
    {
        return false;
    }

    public function clear(): void
    {
    }
}
