<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class TerminatingEscape implements Entity
{
    use TransitiveFlags, QuotesRaw;

    public function raw(): string
    {
        return '\\';
    }
}
