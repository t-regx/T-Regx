<?php
namespace TRegx\CleanRegex\Match\Groups\Strategy;

interface GroupVerifier
{
    public function groupExists($nameOrIndex): bool;
}
