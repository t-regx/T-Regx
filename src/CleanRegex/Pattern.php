<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Exception\PatternMalformedPatternException;
use TRegx\CleanRegex\Internal\Delimiter\TrailingBackslashException;
use TRegx\CleanRegex\Internal\InternalPattern;
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
        return PatternBuilder::builder()->prepare($input, $flags);
    }

    public static function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return PatternBuilder::builder()->bind($input, $values, $flags);
    }

    public static function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return PatternBuilder::builder()->inject($input, $values, $flags);
    }

    public static function compose(array $patterns): CompositePattern
    {
        return PatternBuilder::compose($patterns);
    }

    public static function format(string $format, array $tokens, string $flags = null): PatternInterface
    {
        return PatternBuilder::builder()->format($format, $tokens, $flags);
    }

    public static function template(string $pattern, string $flags = null): TemplatePattern
    {
        return PatternBuilder::builder()->template($pattern, $flags);
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
