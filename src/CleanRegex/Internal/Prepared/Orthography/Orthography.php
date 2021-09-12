<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Condition;

interface Orthography
{
    public function spelling(Condition $condition): Spelling;
}
