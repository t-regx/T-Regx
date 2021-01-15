<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\PatternLimit;
use TRegx\CleanRegex\Internal\Replace\By\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;

class ReplaceLimit implements PatternLimit
{
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;

    public function __construct(InternalPattern $pattern, string $subject)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
    }

    public function all(): LimitlessReplacePattern
    {
        return new LimitlessReplacePattern($this->specific(-1), $this->pattern, $this->subject);
    }

    public function first(): LimitedReplacePattern
    {
        return new LimitedReplacePattern($this->specific(1), $this->pattern, $this->subject, 1);
    }

    public function only(int $limit): LimitedReplacePattern
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return new LimitedReplacePattern($this->specific($limit), $this->pattern, $this->subject, $limit);
    }

    private function specific(int $limit): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($this->pattern, $this->subject, $limit, new DefaultStrategy(), new IgnoreCounting());
    }
}
