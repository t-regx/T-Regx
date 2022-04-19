<?php
namespace TRegx\CleanRegex\Match\Details;

interface Intable
{
    public function toInt(int $base = 10): int;
}
