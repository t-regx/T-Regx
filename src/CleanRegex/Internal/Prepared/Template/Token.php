<?php
namespace TRegx\CleanRegex\Internal\Prepared\Template;

use TRegx\CleanRegex\Internal\Prepared\Condition\Condition;
use TRegx\CleanRegex\Internal\Prepared\Word\Word;
use TRegx\CleanRegex\Internal\Type\Type;

interface Token extends Condition
{
    public function formatAsQuotable(): Word;

    public function type(): Type;
}
