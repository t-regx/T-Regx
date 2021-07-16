<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Quotable\Quotable;
use TRegx\CleanRegex\Internal\Type;

interface Token
{
    public function formatAsQuotable(): Quotable;

    public function type(): Type;
}
