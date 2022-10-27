<?php
namespace TRegx\CleanRegex\Exception;

class UnevenCutException extends \RuntimeException implements PatternException
{
    public function __construct(bool $patternNotMatched)
    {
        if ($patternNotMatched) {
            parent::__construct("Expected the pattern to make exactly 1 cut, but the pattern doesn't match the subject");
        } else {
            parent::__construct("Expected the pattern to make exactly 1 cut, but 2 or more cuts were matched");
        }
    }
}
