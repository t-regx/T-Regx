<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupOpenConditional implements Entity
{
    use PatternEntity;

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->pushFlagsIdentity();
        $subpattern->pushFlagsIdentity();
    }

    public function pattern(): string
    {
        return '(?(';
    }
}
