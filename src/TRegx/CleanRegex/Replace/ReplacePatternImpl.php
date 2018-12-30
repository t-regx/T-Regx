<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\Map\MapReplacePattern;
use TRegx\CleanRegex\Replace\Map\MapReplacePatternImpl;
use TRegx\CleanRegex\Replace\Map\MapReplacer;
use TRegx\SafeRegex\preg;

class ReplacePatternImpl implements ReplacePattern
{
    const WHOLE_MATCH = 0;

    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    /** @var int */
    private $limit;

    public function __construct(Pattern $pattern, string $subject, int $limit)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        return preg::replace($this->pattern->pattern, $replacement, $this->subject, $this->limit);
    }

    public function callback(callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker(
            $this->pattern,
            new SubjectableImpl($this->subject),
            $this->limit
        );
        return $invoker->invoke($callback);
    }

    public function by(): MapReplacePattern
    {
        return new MapReplacePatternImpl(
            new MapReplacer(
                $this->pattern,
                new SubjectableImpl($this->subject),
                $this->limit),
            self::WHOLE_MATCH
        );
    }
}
