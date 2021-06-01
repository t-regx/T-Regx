<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Builder\PatternBuilder;
use TRegx\CleanRegex\Builder\TemplateBuilder;
use TRegx\CleanRegex\CompositePattern;
use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Extended;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\preg;

trait EntryPoints
{
    public static function of(string $pattern, string $flags = null): Pattern
    {
        try {
            return new Pattern(InternalPattern::standard($pattern, $flags ?? ''));
        } catch (TrailingBackslashException $exception) {
            throw new PatternMalformedPatternException('Pattern may not end with a trailing backslash');
        }
    }

    /**
     * @param string $delimitedPattern
     * @return Pattern
     * Please use method \TRegx\CleanRegex\Pattern::of. Method Pattern::pcre() is only present, in case
     * if there's an automatic delimiters' bug, that would make {@link Pattern::of()} error-prone.
     * {@link Pattern::pcre()} is error-prone to MalformedPatternException, because of delimiters.
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public static function pcre(string $delimitedPattern): Pattern
    {
        return new Pattern(InternalPattern::pcre($delimitedPattern));
    }

    public static function prepare(array $input, string $flags = null): Pattern
    {
        return self::builder()->prepare($input, $flags);
    }

    public static function bind(string $input, array $values, string $flags = null): Pattern
    {
        return self::builder()->bind($input, $values, $flags);
    }

    public static function inject(string $input, array $values, string $flags = null): Pattern
    {
        return self::builder()->inject($input, $values, $flags);
    }

    public static function mask(string $mask, array $keywords, string $flags = null): Pattern
    {
        return self::builder()->mask($mask, $keywords, $flags);
    }

    public static function template(string $pattern, string $flags = null): TemplateBuilder
    {
        return self::builder()->template($pattern, $flags);
    }

    public static function literal(string $text, string $flags = null): Pattern
    {
        return EntryPoints::of(Extended::quote(preg::quote($text)), $flags);
    }

    public static function compose(array $patterns): CompositePattern
    {
        return new CompositePattern((new CompositePatternMapper($patterns))->createPatterns());
    }

    public static function builder(): PatternBuilder
    {
        return new PatternBuilder();
    }

    public static function quote(string $string): string
    {
        return preg::quote($string);
    }

    public static function unquote(string $quotedString): string
    {
        return (new UnquotePattern($quotedString))->unquote();
    }
}
