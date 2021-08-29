<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Pcre;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Pattern;

class PcrePatternBuilder
{
    /**
     * @param string $delimitedPattern
     * @return Pattern
     * Please use method {@see Pattern::of}. Method {@see PcrePatternBuilder::of} is only present,
     * in case there's an automatic delimiters' bug, that would make {@link Pattern::of()} error-prone.
     * {@see PcrePatternBuilder::of} is error-prone to {@see MalformedPatternException}, because of delimiters.
     * @see \TRegx\CleanRegex\Pattern::of
     */
    public function of(string $delimitedPattern): Pattern
    {
        $pattern = new Pcre($delimitedPattern);
        return new Pattern($pattern->definition());
    }

    public function inject(string $input, array $values): Pattern
    {
        $build = new Template(new PcreOrthography($input), new InjectFigures($values));
        return new Pattern($build->definition());
    }

    public function template(string $pattern): TemplateBuilder
    {
        return new TemplateBuilder(new PcreOrthography($pattern), []);
    }
}
