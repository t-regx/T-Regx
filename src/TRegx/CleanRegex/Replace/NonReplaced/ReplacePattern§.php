<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

class ReplacePattern§
{
    public function create(InternalPattern $pattern, string $subject, int $limit, NonReplacedStrategy $strategy): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($pattern, $subject, $limit, $strategy);
    }
}
