<?php
namespace TRegx\CleanRegex\Replace;

use TRegx\CleanRegex\Exception\CleanRegex\MissingReplacementKeyException;
use TRegx\CleanRegex\Internal\InternalPattern as Pattern;
use TRegx\CleanRegex\Internal\Match\Base\ApiBase;
use TRegx\CleanRegex\Internal\Match\UserData;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Replace\By\ByReplacePattern;
use TRegx\CleanRegex\Replace\By\ByReplacePatternImpl;
use TRegx\CleanRegex\Replace\By\GroupFallbackReplacer;
use TRegx\CleanRegex\Replace\By\PerformanceEmptyGroupReplace;
use TRegx\CleanRegex\Replace\Callback\MatchStrategy;
use TRegx\CleanRegex\Replace\Callback\ReplacePatternCallbackInvoker;
use TRegx\CleanRegex\Replace\NonReplaced\LazyMessageThrowStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplaceSubstitute;
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

    /** @var ReplaceSubstitute */
    private $substitute;

    public function __construct(Pattern $pattern, string $subject, int $limit, ReplaceSubstitute $substitute)
    {
        $this->pattern = $pattern;
        $this->subject = $subject;
        $this->limit = $limit;
        $this->substitute = $substitute;
    }

    public function with(string $replacement): string
    {
        return $this->withReferences(ReplaceReferences::quote($replacement));
    }

    public function withReferences(string $replacement): string
    {
        $result = preg::replace($this->pattern->pattern, $replacement, $this->subject, $this->limit, $replaced);
        if ($replaced === 0) {
            return $this->substitute->substitute($this->subject) ?? $result;
        }
        return $result;
    }

    public function callback(callable $callback): string
    {
        return $this->replaceCallbackInvoker()->invoke($callback, new MatchStrategy());
    }

    public function by(): ByReplacePattern
    {
        $subjectable = new SubjectableImpl($this->subject);
        return new ByReplacePatternImpl(
            new GroupFallbackReplacer(
                $this->pattern,
                $subjectable,
                $this->limit,
                $this->substitute,
                new ApiBase($this->pattern, $this->subject, new UserData())
            ),
            new LazyMessageThrowStrategy(MissingReplacementKeyException::class),
            new PerformanceEmptyGroupReplace($this->pattern, $subjectable, $this->limit),
            $this->replaceCallbackInvoker(),
            $this->subject
        );
    }

    private function replaceCallbackInvoker(): ReplacePatternCallbackInvoker
    {
        return new ReplacePatternCallbackInvoker(
            $this->pattern,
            new SubjectableImpl($this->subject),
            $this->limit,
            $this->substitute
        );
    }
}
