<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

class TerminatingEscape implements Entity
{
    use TransitiveFlags;

    public function quotable(): Quotable
    {
        throw new TrailingBackslashException();
    }
}
