<?php
namespace TRegx\CleanRegex\Internal\Match\Groups\Strategy;

interface GroupVerifier
{
    public function groupExists($nameOrIndex): bool;
}
