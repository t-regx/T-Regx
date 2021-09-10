<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;

class TerminatingEscape implements Entity
{
    use TransitiveFlags;

    public function word(): Word
    {
        throw new TrailingBackslashException();
    }
}
