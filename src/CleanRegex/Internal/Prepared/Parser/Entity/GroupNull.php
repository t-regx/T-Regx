<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

class GroupNull implements Entity
{
    use TransitiveFlags, QuotesRaw;

    public function raw(): string
    {
        return '(?:)';
    }
}
