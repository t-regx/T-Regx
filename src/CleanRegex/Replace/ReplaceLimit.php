<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Replace\SpecificReplacePatternImpl;
use TRegx\CleanRegex\Internal\ReplaceLimitHelpers;
use TRegx\CleanRegex\Internal\Subject;

class ReplaceLimit implements ReplacePattern
{
    use ReplaceLimitHelpers;

    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function all(): LimitlessReplacePattern
    {
        return new LimitlessReplacePattern($this->specific(-1), $this->definition, $this->subject);
    }

    public function first(): LimitedReplacePattern
    {
        return new LimitedReplacePattern($this->specific(1), $this->definition, $this->subject, 1);
    }

    public function only(int $limit): LimitedReplacePattern
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return new LimitedReplacePattern($this->specific($limit), $this->definition, $this->subject, $limit);
    }

    private function specific(int $limit): SpecificReplacePattern
    {
        return new SpecificReplacePatternImpl($this->definition, $this->subject, $limit, new IgnoreCounting());
    }
}
