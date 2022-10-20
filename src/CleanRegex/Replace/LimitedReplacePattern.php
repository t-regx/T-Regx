<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtLeastCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtMostCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\ExactCountingStrategy;

class LimitedReplacePattern extends ReplacePatternImpl
{
    public function exactly(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new ExactCountingStrategy($this->definition, $this->subject, $this->limit, 'exactly'));
    }

    public function atLeast(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new AtLeastCountingStrategy($this->limit, 'at least'));
    }

    public function atMost(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new AtMostCountingStrategy($this->definition, $this->subject, $this->limit, 'at most'));
    }
}
