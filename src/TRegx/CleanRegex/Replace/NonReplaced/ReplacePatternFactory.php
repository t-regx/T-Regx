<?php
namespace TRegx\CleanRegex\Replace\NonReplaced;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Replace\SpecificReplacePattern;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

class ReplacePatternFactory
{
    public function create(InternalPattern $pattern, string $subject, int $limit, ReplaceSubstitute $substitute): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($pattern, $subject, $limit, $substitute);
    }
}
