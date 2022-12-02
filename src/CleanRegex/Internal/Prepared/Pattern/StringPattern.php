<?php
namespace TRegx\CleanRegex\Internal\Prepared\Pattern;

use TRegx\CleanRegex\Internal\Prepared\Parser\SubpatternFlags;

interface StringPattern
{
    public function pattern(): string;

    public function subpatternFlags(): SubpatternFlags;
}
