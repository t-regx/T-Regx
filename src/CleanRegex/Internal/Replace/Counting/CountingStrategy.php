<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

interface CountingStrategy
{
    public function count(int $replaced): void;
}
