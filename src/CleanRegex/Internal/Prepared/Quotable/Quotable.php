<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quotable;

interface Quotable
{
    public function quote(string $delimiter): string;
}
