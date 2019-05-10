<?php
namespace TRegx\CleanRegex\Composite;

use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\SafeRegex\preg;

class ChainedReplace
{
    /** @var Pattern[] */
    private $patterns;
    /** @var string */
    private $subject;
    /** @var NonReplacedStrategy */
    private $strategy;

    public function __construct(array $patterns, string $subject, NonReplacedStrategy $strategy)
    {
        $this->patterns = $patterns;
        $this->subject = $subject;
        $this->strategy = $strategy;
    }

    public function with(string $replacement): string
    {
        $subject = $this->subject;
        foreach ($this->patterns as $pattern) {
            $subject = preg::replace($pattern->pattern, $replacement, $subject);
        }
        return $subject;
    }

    public function callback(callable $callback): string
    {
        $subject = $this->subject;
        foreach ($this->patterns as $pattern) {
            $subject = $this->replaceNext($pattern, $subject, $callback);
        }
        return $subject;
    }

    private function replaceNext(Pattern $pattern, string $subject, callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker($pattern, new SubjectableImpl($subject), -1, $this->strategy);
        return $invoker->invoke($callback);
    }
}
