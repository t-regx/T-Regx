<?php
namespace Test\Utils;

use Exception;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\Matches\IRawMatchesOffset;

class ExceptionMatchAllFactory implements MatchAllFactory
{
    public function getRawMatches(): IRawMatchesOffset
    {
        throw new Exception();
    }
}
