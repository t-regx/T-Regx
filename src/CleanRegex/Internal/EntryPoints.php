<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\Expression\Alteration;
use TRegx\CleanRegex\Internal\Expression\Literal;
use TRegx\CleanRegex\Internal\Prepared\Cluster\FigureClusters;
use TRegx\CleanRegex\Internal\Prepared\Clusters;
use TRegx\CleanRegex\Internal\Prepared\Expression\Mask;
use TRegx\CleanRegex\Internal\Prepared\Expression\Standard;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardSpelling;
use TRegx\CleanRegex\Pattern;
use TRegx\CleanRegex\PatternList;
use TRegx\CleanRegex\PatternTemplate;
use TRegx\CleanRegex\TemplateBuilder;

trait EntryPoints
{
    public static function of(string $pattern, string $modifiers = null): Pattern
    {
        return new Pattern(new Standard(new StandardSpelling($pattern, Flags::from($modifiers),
            new UnsuitableStringCondition($pattern))));
    }

    public static function inject(string $pattern, array $texts, string $modifiers = null): Pattern
    {
        return new Pattern(new Template(new StandardSpelling($pattern, Flags::from($modifiers),
            new UnsuitableStringCondition($pattern)), new FigureClusters($texts)));
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
        return new TemplateBuilder(new StandardOrthography($pattern, Flags::from($modifiers)),
            new Clusters([]));
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
        return new PatternList(new PatternStrings($patterns));
    }
}
