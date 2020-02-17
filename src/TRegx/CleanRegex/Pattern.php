<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\InternalPattern;

class Pattern
{
    public static function quote(string $pattern): string
    {
        return (new QuotePattern($pattern))->quote();
    }

    public static function unquote(string $pattern): string
    {
        return (new UnquotePattern($pattern))->unquote();
    }

    public static function of(string $pattern, string $flags = ''): PatternInterface
    {
        return new PatternImpl(InternalPattern::standard($pattern, $flags));
    }

    /**
     * @param string $delimitedPattern
     * @return PatternInterface
     * @deprecated Please use method \TRegx\CleanRegex\Pattern::of. Method Pattern::pcre() is only present, in case
     * if there's an automatic delimiters' bug, that would make {@link Pattern::of()} error-prone.
     * {@link Pattern::pcre()} is error-prone to MalformedPatternException, because of delimiters.
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public static function pcre(string $delimitedPattern): PatternInterface
    {
        return new PatternImpl(InternalPattern::pcre($delimitedPattern));
    }

    public static function prepare(array $input, string $flags = ''): PatternInterface
    {
        return PatternBuilder::builder()->prepare($input, $flags);
    }

    public static function bind(string $input, array $values, string $flags = ''): PatternInterface
    {
        return PatternBuilder::builder()->bind($input, $values, $flags);
    }

    public static function inject(string $input, array $values, string $flags = ''): PatternInterface
    {
        return PatternBuilder::builder()->inject($input, $values, $flags);
    }

    public static function compose(array $patterns): CompositePattern
    {
        return PatternBuilder::compose($patterns);
    }
}
