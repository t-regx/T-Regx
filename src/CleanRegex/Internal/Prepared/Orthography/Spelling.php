<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Prepared\Pattern\StringPattern;

interface Spelling extends StringPattern
{
    public function delimiter(): Delimiter;
}
