<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupClose implements Entity
{
    use PatternEntity;

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->resetFlags();
    }

    public function pattern(): string
    {
        return ')';
    }
}
