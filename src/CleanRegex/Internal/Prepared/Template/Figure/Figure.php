<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template\Figure;

use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;
use TRegx\CleanRegex\Internal\Type\Type;

interface Figure
{
    public function phrase(): Phrase;

    public function suitable(string $candidate): bool;

    public function type(): Type;
}
