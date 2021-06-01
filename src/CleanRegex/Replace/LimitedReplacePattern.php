<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtLeastCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtMostCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\CompositeCountingStrategy;

class LimitedReplacePattern extends ReplacePatternImpl
{
    public function exactly(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new CompositeCountingStrategy(
            new AtLeastCountingStrategy($this->limit, 'exactly'),
            new AtMostCountingStrategy($this->pattern, $this->subject, $this->limit, 'exactly')
        ));
    }

    public function atLeast(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new AtLeastCountingStrategy($this->limit, 'at least'));
    }

    public function atMost(): CompositeReplacePattern
    {
        return $this->replacePattern(new DefaultStrategy(), new AtMostCountingStrategy($this->pattern, $this->subject, $this->limit, 'at most'));
    }
}
