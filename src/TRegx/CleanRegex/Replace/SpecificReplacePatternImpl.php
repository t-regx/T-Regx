<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\CleanRegex\MissingReplacementKeyException;
use TRegx\CleanRegex\Exception\CleanRegex\NotMatched\MissingReplacement\ForMatchMessage;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\Map\ByReplacePattern;
use TRegx\CleanRegex\Replace\Map\ByReplacePatternImpl;
use TRegx\CleanRegex\Replace\Map\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\NonReplaced\NonReplacedStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ThrowStrategy;
use TRegx\SafeRegex\preg;

class SpecificReplacePatternImpl implements SpecificReplacePattern
{
    const WHOLE_MATCH = 0;

    /** @var Pattern */
    private $pattern;

    /** @var string */
    private $subject;

    /** @var int */
    private $limit;

    /** @var NonReplacedStrategy */
    private $strategy;

    public function __construct(Pattern $pattern, string $subject, int $limit, NonReplacedStrategy $strategy)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->strategy = $strategy;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $result = preg::replace($this->pattern->pattern, $replacement, $this->subject, $this->limit, $replaced);
        if ($replaced === 0) {
            return $this->strategy->replacementResult($this->subject) ?? $result;
        }
        return $result;
    }

    public function callback(callable $callback): string
    {
        $invoker = new ReplacePatternCallbackInvoker(
            $this->pattern,
            new SubjectableImpl($this->subject),
            $this->limit,
            $this->strategy
        );
        return $invoker->invoke($callback);
    }

    public function by(): ByReplacePattern
    {
        return new ByReplacePatternImpl(
            new GroupFallbackReplacer(
                $this->pattern,
                new SubjectableImpl($this->subject),
                $this->limit,
                $this->strategy,
                new ApiBase($this->pattern, $this->subject, new UserData())
            ),
            new ThrowStrategy(
                MissingReplacementKeyException::class,
                new ForMatchMessage()
            )
        );
    }
}
