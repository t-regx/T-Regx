<?php
namespace TRegx\CleanRegex\Internal\Prepared\Format;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;

interface TokenValue
{
    public function formatAsQuotable(): Quotable;
}
