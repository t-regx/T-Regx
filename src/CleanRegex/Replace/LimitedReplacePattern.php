<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Replace\Counting\AtLeastCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtMostCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\ExactCountingStrategy;

class LimitedReplacePattern extends ReplacePatternImpl
{
    public function exactly(): SpecificReplacePattern
    {
        return $this->replacePattern(new ExactCountingStrategy($this->definition, $this->subject, $this->limit));
    }

    public function atLeast(): SpecificReplacePattern
    {
        return $this->replacePattern(new AtLeastCountingStrategy($this->limit));
    }

    public function atMost(): SpecificReplacePattern
    {
        return $this->replacePattern(new AtMostCountingStrategy($this->definition, $this->subject, $this->limit));
    }
}
