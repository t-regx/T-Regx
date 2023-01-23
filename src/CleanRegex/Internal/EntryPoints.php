<?php
namespace TRegx\CleanRegex\Internal;

use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
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
    public static function of(string $pattern, string $modifiers = ''): Pattern
    {
        return new Pattern(new Standard(PcreAutoCapture::autoCapture(), new StandardSpelling($pattern,
            new Flags($modifiers), new UnsuitableStringCondition($pattern))));
    }

    public static function inject(string $pattern, array $texts, string $modifiers = ''): Pattern
    {
        return new Pattern(new Template(PcreAutoCapture::autoCapture(), new StandardSpelling(
            $pattern, new Flags($modifiers), new UnsuitableStringCondition($pattern)),
            new FigureClusters($texts)));
    }

    public static function mask(string $mask, array $keywords, string $modifiers = ''): Pattern
    {
        return new Pattern(new Mask(PcreAutoCapture::autoCapture(), $mask, new Flags($modifiers), $keywords));
    }

    public static function template(string $pattern, string $modifiers = ''): PatternTemplate
    {
        return new PatternTemplate(PcreAutoCapture::autoCapture(),
            new StandardOrthography($pattern, new Flags($modifiers)));
    }

    public static function builder(string $pattern, string $modifiers = ''): TemplateBuilder
    {
        return new TemplateBuilder(PcreAutoCapture::autoCapture(),
            new StandardOrthography($pattern, new Flags($modifiers)), new Clusters([]));
    }

    public static function literal(string $text, string $modifiers = ''): Pattern
    {
        return new Pattern(new Literal(PcreAutoCapture::autoCapture(), $text, new Flags($modifiers)));
    }

    public static function alteration(array $texts, string $modifiers = ''): Pattern
    {
        return new Pattern(new Alteration(PcreAutoCapture::autoCapture(), $texts, new Flags($modifiers)));
    }

    public static function list(array $patterns): PatternList
    {
        return new PatternList(new PatternStrings(PcreAutoCapture::autoCapture(), $patterns));
    }
}
