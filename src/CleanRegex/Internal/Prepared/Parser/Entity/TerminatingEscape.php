<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

class TerminatingEscape implements Entity
{
    use TransitiveFlags;

    public function phrase(): Phrase
    {
        throw new TrailingBackslashException();
    }
}
