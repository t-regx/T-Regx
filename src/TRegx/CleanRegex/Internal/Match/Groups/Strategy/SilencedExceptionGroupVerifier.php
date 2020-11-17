<?php
namespace TRegx\CleanRegex\Internal\Match\Groups\Strategy;

class SilencedExceptionGroupVerifier implements GroupVerifier
{
    public function groupExists($nameOrIndex): bool
    {
        return true;
    }
}
