<?php
namespace TRegx\CleanRegex\Replace\Callback;

use TRegx\CleanRegex\Match\Details\ReplaceMatch;

class MatchStrategy implements ReplaceCallbackArgumentStrategy
{
    public function mapArgument(ReplaceMatch $match): ReplaceMatch
    {
        return $match;
    }
}
