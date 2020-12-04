<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\PatternLimit;

interface ReplaceLimit extends PatternLimit
{
    public function all(): ReplacePattern;

    public function first(): ReplacePattern;

    public function only(int $limit): ReplacePattern;
}
