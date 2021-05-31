<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

class GroupOpenFlags implements Entity
{
    use QuotesRaw;

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

    public function raw(): string
    {
        return "(?$this->flags:";
    }
}
