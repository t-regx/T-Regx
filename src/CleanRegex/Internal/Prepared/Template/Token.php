<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface Token
{
    public function formatAsQuotable(): Quotable;
}
