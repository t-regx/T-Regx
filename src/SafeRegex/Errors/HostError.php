<?php
namespace SafeRegex\Errors;

interface HostError
{
    public function occurred(): bool;

    public function clear(): void;
}
