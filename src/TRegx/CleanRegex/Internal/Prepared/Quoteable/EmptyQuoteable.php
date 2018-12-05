<?php
namespace TRegx\CleanRegex\Internal\Prepared\Quoteable;

class EmptyQuoteable implements Quoteable
{
    public function quote(string $delimiter): string
    {
        return '';
    }
}
