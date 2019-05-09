<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\ReplacePattern;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;

class ReplacePattern§
{
    public function create(InternalPattern $pattern, string $subject, int $limit, NonReplacedStrategy $strategy): ReplacePattern
    {
        return new ReplacePatternImpl($pattern, $subject, $limit, $strategy);
    }
}
