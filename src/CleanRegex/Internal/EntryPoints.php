<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Builder\PatternTemplate;
use TRegx\CleanRegex\Builder\TemplateBuilder;
use TRegx\CleanRegex\Internal\Expression\Alteration;
use TRegx\CleanRegex\Internal\Expression\Literal;
use TRegx\CleanRegex\Internal\Expression\Predefinition\Predefinition;
use TRegx\CleanRegex\Internal\Prepared\Cluster\FigureClusters;
use TRegx\CleanRegex\Internal\Prepared\Clusters;
use TRegx\CleanRegex\Internal\Prepared\Expression\Mask;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternList;

trait EntryPoints
{
    public static function of(string $pattern, string $modifiers = null): Pattern
    {
        return new Pattern(new Standard(new StandardSpelling($pattern, Flags::from($modifiers), new UnsuitableStringCondition($pattern))));
    }

    public static function inject(string $pattern, array $texts, string $modifiers = null): Pattern
    {
        return new Pattern(new Template(new StandardSpelling($pattern, Flags::from($modifiers), new UnsuitableStringCondition($pattern)), new FigureClusters($texts)));
    }

    public static function mask(string $mask, array $keywords, string $modifiers = null): Pattern
    {
        return new Pattern(new Mask($mask, $keywords, Flags::from($modifiers)));
    }

    public static function template(string $pattern, string $modifiers = null): PatternTemplate
    {
        return new PatternTemplate(new StandardOrthography($pattern, Flags::from($modifiers)));
    }

    public static function builder(string $pattern, string $modifiers = null): TemplateBuilder
    {
        return new TemplateBuilder(new StandardOrthography($pattern, Flags::from($modifiers)), new Clusters([]));
    }

    public static function literal(string $text, string $modifiers = null): Pattern
    {
        return new Pattern(new Literal($text, Flags::from($modifiers)));
    }

    public static function alteration(array $texts, string $modifiers = null): Pattern
    {
        return new Pattern(new Alteration($texts, Flags::from($modifiers)));
    }

    public static function list(array $patterns): PatternList
    {
        return self::patternList(new PatternStrings($patterns));
    }

    private static function patternList(PatternStrings $patterns): PatternList
    {
        return new PatternList($patterns->predefinitions(static function (Pattern $pattern): Predefinition {
            /**
             * {@see Pattern} instance has reference to {@see Predefinition} as "predefinition"
             * private field. The {@see Predefinition} contains {@see Definition} field,
             * containing a delimited PCRE pattern withs flags as a string, and another
             * field {@see Definition::$undevelopedInput}, containing pattern before it
             * has been parsed - kept for debugging purposes in client applications.
             * It contains whatever string input was used to construct the real pattern.
             * It can either be the exact pcre string, it can be undelimited pattern
             * used with standard strategy, template with placeholders in case of
             * prepared pattern, or it can be user input in case of a mask with keywords.
             *
             * In the future, we plan to make {@see Definition::$undevelopedInput} visible
             * in the public API for clients, perhaps as a field in thrown exceptions, that
             * clients could use for debugging.
             *
             * In order to use pattern lists, we consume strings and {@see Pattern} as an
             * input, and construct {@see Definition} based on the input. In case of a string,
             * that's simple, we construct a new {@see Definition} with that. In case of a
             * {@see Pattern} instance, we could use the delimited pattern and call it a day,
             * but then the {@see Definition::$undevelopedInput} of the definition  would be
             * lost for the debugging purposes in the client applications. So to preserve that,
             * we don't construct new {@see Definition} instances, but we take one from the
             * {@see Pattern}. Normally, that would be impossible without reflection, breaking
             * encapsulation or exposing the internal pattern outside of {@see Pattern} (which
             * would break its point of being internal), but {@see EntryPoints} is a trait in
             * {@see Pattern}, so it has access to its private fields. That's why we can just
             * pass a closure, which can map {@see Pattern} to {@see Definition}.
             */
            return $pattern->predefinition;
        }));
    }
}
