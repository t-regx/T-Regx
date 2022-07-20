<?php
namespace TRegx\CleanRegex\Internal\Match;

interface Intable
{
    public function toInt(int $base = 10): int;
}
