<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class PosixClose implements Entity
{
    use TransitiveFlags, QuotesRaw;

    public function raw(): string
    {
        return ']';
    }
}
