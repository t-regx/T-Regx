<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Replace\Counting\AtLeastCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\AtMostCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\ExactCountingStrategy;
use TRegx\CleanRegex\Internal\Replace\Counting\IgnoreCounting;
use TRegx\CleanRegex\Internal\Subject;

/**
 * @deprecated
 */
class Replace
{
    /** @var Definition */
    private $definition;
    /** @var Subject */
    private $subject;
    /** @var LimitedReplace */
    private $replace;

    public function __construct(Definition $definition, Subject $subject)
    {
        $this->definition = $definition;
        $this->subject = $subject;
        $this->replace = new LimitedReplace($this->definition, $this->subject, -1, new IgnoreCounting());
    }

    /**
     * @deprecated
     */
    public function with(string $replacement): string
    {
        return $this->replace->with($replacement);
    }

    /**
     * @deprecated
     */
    public function withGroup($nameOrIndex): string
    {
        return $this->replace->withGroup($nameOrIndex);
    }

    /**
     * @deprecated
     */
    public function withReferences(string $replacement): string
    {
        return $this->replace->withReferences($replacement);
    }

    /**
     * @deprecated
     */
    public function callback(callable $callback): string
    {
        return $this->replace->callback($callback);
    }

    /**
     * @deprecated
     */
    public function count(): int
    {
        return $this->replace->count();
    }

    /**
     * @deprecated
     */
    public function first(): LimitedReplace
    {
        return $this->exactly(1);
    }

    /**
     * @deprecated
     */
    public function exactly(int $amount): LimitedReplace
    {
        return new LimitedReplace($this->definition, $this->subject, $amount + 1, new ExactCountingStrategy($amount));
    }

    /**
     * @deprecated
     */
    public function atMost(int $maximum): LimitedReplace
    {
        return new LimitedReplace($this->definition, $this->subject, $maximum + 1, new AtMostCountingStrategy($maximum));
    }

    /**
     * @deprecated
     */
    public function atLeast(int $minimum): LimitedReplace
    {
        return new LimitedReplace($this->definition, $this->subject, -1, new AtLeastCountingStrategy($minimum));
    }

    /**
     * @deprecated
     */
    public function limit(int $limit): LimitedReplace
    {
        if ($limit < 0) {
            throw new \InvalidArgumentException("Negative limit: $limit");
        }
        return new LimitedReplace($this->definition, $this->subject, $limit, new IgnoreCounting());
    }
}
