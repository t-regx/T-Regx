<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class PosixOpen implements Entity
{
    use TransitiveFlags, QuotesRaw;

    public function raw(): string
    {
        return '[';
    }
}
