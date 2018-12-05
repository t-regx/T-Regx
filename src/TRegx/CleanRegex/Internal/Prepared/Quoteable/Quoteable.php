<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

interface Quoteable
{
    public function quote(string $delimiter): string;
}
