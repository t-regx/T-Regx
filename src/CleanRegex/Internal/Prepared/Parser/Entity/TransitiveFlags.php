<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;

trait TransitiveFlags
{
    public function visit(Subpattern $subpattern): void
    {
    }
}
