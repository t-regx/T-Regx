<?php
namespace TRegx\CleanRegex\Internal\Match\MatchAll;

use Exception;
use TRegx\CleanRegex\Internal\Model\IRawMatchesOffset;

class ExceptionMatchAllFactory implements MatchAllFactory
{
    public function getRawMatches(): IRawMatchesOffset
    {
        throw new Exception();
    }
}
