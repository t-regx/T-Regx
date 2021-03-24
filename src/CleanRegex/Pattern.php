<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Builder\PatternBuilder;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\CompositePatternMapper;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\InternalPattern;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Extended;
use TRegx\CleanRegex\Internal\UnquotePattern;
use TRegx\SafeRegex\preg;

class Pattern
{
    public static function of(string $pattern, string $flags = null): PatternInterface
    {
        try {
            return new PatternImpl(InternalPattern::standard($pattern, $flags ?? ''));
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }

    /**
     * @param string $delimitedPattern
     * @return PatternInterface
     * Please use method \TRegx\CleanRegex\Pattern::of. Method Pattern::pcre() is only present, in case
     * if there's an automatic delimiters' bug, that would make {@link Pattern::of()} error-prone.
     * {@link Pattern::pcre()} is error-prone to MalformedPatternException, because of delimiters.
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public static function pcre(string $delimitedPattern): PatternInterface
    {
        return new PatternImpl(InternalPattern::pcre($delimitedPattern));
    }

    public static function prepare(array $input, string $flags = null): PatternInterface
    {
        return self::builder()->prepare($input, $flags);
    }

    public static function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return self::builder()->bind($input, $values, $flags);
    }

    public static function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return self::builder()->inject($input, $values, $flags);
    }

    public static function mask(string $mask, array $keywords, string $flags = null): PatternInterface
    {
        return self::builder()->mask($mask, $keywords, $flags);
    }

    public static function template(string $pattern, string $flags = null): Template
    {
        return self::builder()->template($pattern, $flags);
    }

    public static function literal(string $text, string $flags = null): PatternInterface
    {
        return Pattern::of(Extended::quote(preg::quote($text)), $flags);
    }

    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    public static function quote(string $string): string
    {
        return preg::quote($string);
    }

    public static function unquote(string $quotedString): string
    {
        return (new UnquotePattern($quotedString))->unquote();
    }

    public static function builder(): PatternBuilder
    {
        return new PatternBuilder();
    }
}
