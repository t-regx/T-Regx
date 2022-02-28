<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupOpen implements Entity
{
    use PatternEntity;

    public function pattern(): string
    {
        return '(';
    }

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->pushFlagsIdentity();
    }
}
