<?php
namespace TRegx\CleanRegex\Internal\Replace\Counting;

interface CountingStrategy
{
    public function applyReplaced(int $replaced): void;
}
