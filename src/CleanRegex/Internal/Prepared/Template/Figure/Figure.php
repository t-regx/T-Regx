<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

interface Figure
{
    public function phrase(): Phrase;

    public function suitable(string $candidate): bool;
}
