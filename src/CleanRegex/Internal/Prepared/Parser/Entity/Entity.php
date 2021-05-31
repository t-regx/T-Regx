<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface Entity
{
    public function visit(Subpattern $subpattern): void;

    public function quotable(): Quotable;
}
