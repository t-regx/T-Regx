<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupClose implements Entity
{
    use QuotesRaw;

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->resetFlags();
    }

    public function raw(): string
    {
        return ')';
    }
}
