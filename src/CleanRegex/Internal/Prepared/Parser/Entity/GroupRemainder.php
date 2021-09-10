<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupRemainder implements Entity
{
    use PatternEntity;

    /** @var string */
    private $flags;

    public function __construct(string $flags)
    {
        $this->flags = $flags;
    }

    public function visit(Subpattern $subpattern): void
    {
        $subpattern->setFlags($this->flags);
    }

    public function pattern(): string
    {
        return "(?$this->flags)";
    }
}
