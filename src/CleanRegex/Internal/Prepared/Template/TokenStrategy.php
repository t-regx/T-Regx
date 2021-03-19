<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface TokenStrategy
{
    public function nextAsQuotable(): Quotable;
}
