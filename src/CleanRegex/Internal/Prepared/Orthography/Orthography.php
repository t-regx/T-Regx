<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Prepared\Condition\Condition;

interface Orthography
{
    public function spelling(Condition $condition): Spelling;
}
