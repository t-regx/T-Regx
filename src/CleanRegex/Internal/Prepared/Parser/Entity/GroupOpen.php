<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupOpen implements Entity
{
    use PatternEntity;

    /** @var string */
    private $suffix;

    public function __construct(string $suffix)
    {
        $this->suffix = $suffix;
    }

    public function pattern(): string
    {
        return '(' . $this->suffix;
    }

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->pushFlagsIdentity();
    }
}
