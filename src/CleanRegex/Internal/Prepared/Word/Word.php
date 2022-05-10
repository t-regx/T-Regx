<?php
namespace TRegx\CleanRegex\Internal\Prepared\Word;

interface Word
{
    public function escaped(string $delimiter): string;
}
