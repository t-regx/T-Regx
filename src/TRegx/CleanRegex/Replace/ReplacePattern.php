<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\SafeRegex\preg;

class ReplacePattern
{
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
}
