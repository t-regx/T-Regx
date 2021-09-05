<?php
namespace TRegx\CleanRegex\Internal\Prepared\Orthography;

use TRegx\CleanRegex\Internal\Delimiter\Delimiter;
use TRegx\CleanRegex\Internal\Flags;

interface Spelling
{
    public function delimiter(): Delimiter;

    public function undevelopedInput(): string;

    public function pattern(): string;

    public function flags(): Flags;
}
