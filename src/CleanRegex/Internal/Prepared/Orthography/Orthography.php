<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Condition;
use TRegx\CleanRegex\Internal\Flags;

interface Orthography
{
    public function spelling(Condition $condition): Spelling;

    public function flags(): Flags;
}
