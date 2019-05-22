<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\ForArray\ForArrayPattern;
use TRegx\CleanRegex\ForArray\ForArrayPatternImpl;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\SubjectableImpl;
use TRegx\CleanRegex\Match\MatchPattern;
use TRegx\CleanRegex\Remove\RemoveLimit;
use TRegx\CleanRegex\Remove\RemovePattern;
use TRegx\CleanRegex\Replace\NonReplaced\DefaultStrategy;
use TRegx\CleanRegex\Replace\NonReplaced\ReplacePatternFactory;
use TRegx\CleanRegex\Replace\ReplaceLimit;
use TRegx\CleanRegex\Replace\ReplaceLimitImpl;
use TRegx\CleanRegex\Replace\ReplacePatternImpl;
use TRegx\CleanRegex\Replace\SpecificReplacePatternImpl;

class Pattern
{
    /** @var InternalPattern */
    private $pattern;

    public function __construct(string $pattern, string $flags = '')
    {
        $this->pattern = new InternalPattern($pattern, $flags);
    }

    public function test(string $subject): bool
    {
        return (new TestMatchesPattern($this->pattern, new SubjectableImpl($subject)))->test();
    }

    public function fails(string $subject): bool
    {
        return (new TestMatchesPattern($this->pattern, new SubjectableImpl($subject)))->fails();
    }

    public function match(string $subject): MatchPattern
    {
        return new MatchPattern($this->pattern, $subject);
    }

    public function replace(string $subject): ReplaceLimit
    {
        return new ReplaceLimitImpl(function (int $limit) use ($subject) {
            return new ReplacePatternImpl(
                new SpecificReplacePatternImpl($this->pattern, $subject, $limit, new DefaultStrategy()),
                $this->pattern,
                $subject,
                $limit,
                new ReplacePatternFactory());
        });
    }

    public function remove(string $subject): RemoveLimit
    {
        return new RemoveLimit(function (int $limit) use ($subject) {
            return (new RemovePattern($this->pattern, $subject, $limit))->remove();
        });
    }

    public function forArray(array $haystack): ForArrayPattern
    {
        return new ForArrayPatternImpl($this->pattern, $haystack);
    }

    public function split(string $subject): SplitPattern
    {
        return new SplitPattern($this->pattern, new SubjectableImpl($subject));
    }

    public function count(string $subject): int
    {
        return (new CountPattern($this->pattern, new SubjectableImpl($subject)))->count();
    }

    public static function quote(string $pattern): string
    {
        return (new QuotePattern($pattern))->quote();
    }

    public static function unquote(string $pattern): string
    {
        return (new UnquotePattern($pattern))->unquote();
    }

    public function is(): IsPattern
    {
        return new IsPattern($this->pattern);
    }

    public function delimiter(): string
    {
        return $this->pattern->pattern;
    }

    public static function of(string $pattern, string $flags = ''): Pattern
    {
        return new Pattern($pattern, $flags);
    }

    public static function prepare(array $input): Pattern
    {
        return PatternBuilder::prepare($input);
    }

    public static function inject(string $input, array $values): Pattern
    {
        return PatternBuilder::inject($input, $values);
    }

    public static function compose(array $patterns): CompositePattern
    {
        return PatternBuilder::compose($patterns);
    }
}
