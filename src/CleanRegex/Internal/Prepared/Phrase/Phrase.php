<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

interface Phrase
{
    public function quoted(string $delimiter): string;
}
