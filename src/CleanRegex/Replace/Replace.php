<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Replace\SpecificReplacePatternImpl;
use TRegx\CleanRegex\Internal\Subject;

class Replace implements SpecificReplacePattern
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
    }

    public function with(string $replacement): string
    {
        return $this->specific(-1)->with($replacement);
    }

    public function withGroup($nameOrIndex): string
    {
        return $this->specific(-1)->withGroup($nameOrIndex);
    }

    public function withReferences(string $replacement): string
    {
        return $this->specific(-1)->withReferences($replacement);
    }

    public function callback(callable $callback): string
    {
        return $this->specific(-1)->callback($callback);
    }

    public function first(): LimitedReplacePattern
    {
        return new LimitedReplacePattern($this->specific(1), $this->definition, $this->subject, 1);
    }

    public function limit(int $limit): LimitedReplacePattern
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
