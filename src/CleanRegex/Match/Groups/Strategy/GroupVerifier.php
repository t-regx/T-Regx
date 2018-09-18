<?php
namespace CleanRegex\Match\Groups\Strategy;

interface GroupVerifier
{
    public function groupExists(string $pattern, $nameOrIndex): bool;
}
