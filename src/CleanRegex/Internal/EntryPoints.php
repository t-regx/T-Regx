<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Builder\PcrePatternBuilder;
use TRegx\CleanRegex\Builder\TemplateBuilder;
use TRegx\CleanRegex\Composite\CompositePattern;
use TRegx\CleanRegex\Internal\Prepared\Expression\Mask;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;
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

    public static function inject(string $input, array $figures, string $flags = null): Pattern
    {
        $template = new Template(new StandardOrthography($input, $flags ?? ''), new InjectFigures($figures));
        return new Pattern($template->definition());
    }

    public static function mask(string $mask, array $keywords, string $flags = null): Pattern
    {
        $mask = new Mask($mask, $keywords, $flags ?? '');
        return new Pattern($mask->definition());
    }

    public static function template(string $pattern, string $flags = null): TemplateBuilder
    {
        return new TemplateBuilder(new StandardOrthography($pattern, $flags ?? ''), []);
    }

    public static function literal(string $text, string $flags = null): Pattern
    {
        return EntryPoints::of(Extended::quote(preg::quote($text)), $flags);
    }

    public static function pcre(): PcrePatternBuilder
    {
        return new PcrePatternBuilder();
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
}
