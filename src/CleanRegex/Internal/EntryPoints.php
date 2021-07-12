<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Builder\PatternBuilder;
use TRegx\CleanRegex\Builder\TemplateBuilder;
use TRegx\CleanRegex\Composite\CompositePattern;
use TRegx\CleanRegex\Internal\Prepared\Expression\Pcre;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Quotable\Extended;
use TRegx\CleanRegex\Pattern;
use TRegx\SafeRegex\preg;

trait EntryPoints
{
    public static function of(string $pattern, string $flags = null): Pattern
    {
        $standard = new Standard($pattern, $flags ?? '');
        return new Pattern($standard->definition());
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
        $pattern = new Pcre($delimitedPattern);
        return new Pattern($pattern->definition());
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
        return new CompositePattern(InternalPatterns::compose($patterns, static function (Pattern $pattern): Definition {
            /**
             * Pattern instance has reference to InternalPattern as "pattern" private field.
             * InternalPattern has "pattern" field, containing a delimited PCRE pattern with
             * flags as a string, and another field, "originalPattern", kept for debugging
             * purposes in client applications. It contains whatever string input was used
             * to construct the real pattern. It can either be the exact pcre string,
             * it can be undelimited pattern used with standard strategy, template with
             * placeholders in case of prepared pattern, or it can be user input in case
             * of a mask with keywords.
             *
             * In order to use composite patterns, we consume strings and Patterns as an
             * input, and construct InternalPattern instances with that. In case of a string,
             * that's simple, we construct a new InternalPattern with that. In case of a Pattern
             * instance, we could use the delimited pattern and call it a day, but then the
             * originalPattern of the internal InternalPattern would be lost for the debugging
             * purposes in the client applications. So to preserve that, we don't construct
             * new InternalPattern instances, but we take one from the Pattern. Normally, that
             * would be impossible without reflection, breaking encapsulation or exposing the
             * internal pattern outside of Pattern (which would break its point of being internal),
             * but EntryPoints is a trait in Pattern, so it has access to its private fields.
             * That's why we can just pass a closure, which can map Pattern to InternalPattern.
             */
            return $pattern->definition;
        }));
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
