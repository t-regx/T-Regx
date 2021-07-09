<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Flags;

interface Orthography
{
    public function delimiter(): string;

    public function undevelopedInput(): string;

    public function pattern(): string;

    public function flags(): Flags;
}
