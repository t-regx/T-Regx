<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupComment implements Entity
{
    use PatternEntity;

    /** @var string */
    private $comment;

    public function __construct(string $comment)
    {
        $this->comment = $comment;
    }

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->pushFlagsIdentity();
    }

    public function pattern(): string
    {
        return "(?#$this->comment";
    }
}
