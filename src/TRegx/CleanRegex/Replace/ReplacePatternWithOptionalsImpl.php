<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\CleanRegex\NotReplacedException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Match\ForFirst\Optional;
use TRegx\CleanRegex\Replace\Map\MapReplacePattern;

class ReplacePatternWithOptionalsImpl implements ReplacePattern, Optional
{
    /** @var ReplacePattern */
    private $replacePattern;
    /** @var InternalPattern */
    private $pattern;
    /** @var string */
    private $subject;
    /** @var int */
    private $limit;

    public function __construct(ReplacePattern $replacePattern, InternalPattern $pattern, string $subject, int $limit)
    {
        $this->replacePattern = $replacePattern;
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function with(string $replacement): string
    {
        return $this->replacePattern->with($replacement);
    }

    public function withReferences(string $replacement): string
    {
        return $this->replacePattern->withReferences($replacement);
    }

    public function callback(callable $callback): string
    {
        return $this->replacePattern->callback($callback);
    }

    public function by(): MapReplacePattern
    {
        return $this->replacePattern->by();
    }

    public function orThrow(string $exceptionClassName = NotReplacedException::class): ReplacePattern
    {
        return $this->replacePattern;
    }

    public function orReturn($default): ReplacePattern
    {
        return $this->replacePattern;
    }

    public function orElse(callable $producer): ReplacePattern
    {
        return $this->replacePattern;
    }
}
