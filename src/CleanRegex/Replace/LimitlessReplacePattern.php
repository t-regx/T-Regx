<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\InternalPattern;

class LimitlessReplacePattern extends ReplacePatternImpl
{
    public function __construct(SpecificReplacePattern $replacePattern, InternalPattern $pattern, string $subject)
    {
        parent::__construct($replacePattern, $pattern, $subject, -1);
    }
}
