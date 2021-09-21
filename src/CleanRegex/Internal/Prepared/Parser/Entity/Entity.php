<?php
namespace TRegx\CleanRegex\Internal\Prepared\Parser\Entity;

use TRegx\CleanRegex\Internal\Prepared\Parser\Subpattern;
use TRegx\CleanRegex\Internal\Prepared\Phrase\Phrase;

interface Entity
{
    public function visit(Subpattern $subpattern): void;

    public function phrase(): Phrase;
}
