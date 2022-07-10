<?php
namespace TRegx\CleanRegex\Internal\Prepared\Pattern;

use TRegx\CleanRegex\Internal\Flags;

interface StringPattern
{
    public function pattern(): string;

    public function flags(): Flags;
}
