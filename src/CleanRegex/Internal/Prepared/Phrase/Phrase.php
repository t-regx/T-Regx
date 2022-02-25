<?php
namespace TRegx\CleanRegex\Internal\Prepared\Phrase;

interface Phrase
{
    public function conjugated(string $delimiter): string;

    public function unconjugated(string $delimiter): string;
}
