<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\ExactCountingStrategy;

class LimitedReplacePattern extends ReplacePatternImpl
{
    public function exactly(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new ExactCountingStrategy($this->pattern, $this->subject, $this->limit));
    }
}
