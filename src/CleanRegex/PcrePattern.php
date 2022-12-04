<?php
namespace TRegx\CleanRegex;

use TRegx\CleanRegex\Internal\AutoCapture\PcreAutoCapture;
use TRegx\CleanRegex\Internal\Expression\Pcre;
use TRegx\CleanRegex\Internal\Prepared\Cluster\FigureClusters;
use TRegx\CleanRegex\Internal\Prepared\Clusters;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreSpelling;

class PcrePattern
{
    /**
     * Please, use {@see Pattern::of} and other methods in {@see Pattern}.
     * Method {@see PcrePattern::of} is only present for completeness, in case of
     * an automatic delimiters' bug, that would make {@link Pattern::of()} invalid.
     * Patterns with {@see PcrePattern} are error-prone to {@see MalformedPatternException},
     * because of delimiter errors within PHP engine itself - that is, there are
     * certain patterns that just don't work with {@see PcrePattern} or {@see preg_match},
     * that will however work just fine with {@see Pattern}.
     * @param string $pcrePattern
     * @return Pattern
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public static function of(string $pcrePattern): Pattern
    {
        return new Pattern(new Pcre(PcreAutoCapture::autoCapture(), $pcrePattern));
    }

    public static function inject(string $pcreTemplate, array $values): Pattern
    {
        return new Pattern(new Template(PcreAutoCapture::autoCapture(), new PcreSpelling($pcreTemplate), new FigureClusters($values)));
    }

    public static function template(string $pcreTemplate): PatternTemplate
    {
        return new PatternTemplate(PcreAutoCapture::autoCapture(), new PcreOrthography($pcreTemplate));
    }

    public static function builder(string $pcreTemplate): TemplateBuilder
    {
        return new TemplateBuilder(PcreAutoCapture::autoCapture(), new PcreOrthography($pcreTemplate), new Clusters([]));
    }
}
