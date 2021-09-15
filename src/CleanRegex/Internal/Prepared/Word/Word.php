<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

interface Word
{
    public function quoted(string $delimiter): string;
}
