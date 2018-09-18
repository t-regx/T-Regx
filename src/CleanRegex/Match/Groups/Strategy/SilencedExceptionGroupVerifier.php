<?php
namespace CleanRegex\Match\Groups\Strategy;

class SilencedExceptionGroupVerifier implements GroupVerifier
{
    public function groupExists(string $pattern, $nameOrIndex): bool
    {
        return true;
    }
}
