<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Mask;
use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\StandardOrthography;
use TRegx\CleanRegex\Pattern;

class PatternBuilder
{
    public function pcre(): PcrePatternBuilder
    {
        return new PcrePatternBuilder();
    }

    public function inject(string $input, array $values, string $flags = null): Pattern
    {
        $template = new Template(new StandardOrthography($input, $flags ?? ''), new InjectFigures($values));
        return new Pattern($template->definition());
    }

    public function mask(string $mask, array $keywords, string $flags = null): Pattern
    {
        $mask = new Mask($mask, $keywords, $flags ?? '');
        return new Pattern($mask->definition());
    }

    public function template(string $pattern, string $flags = null): TemplateBuilder
    {
        return new TemplateBuilder(new StandardOrthography($pattern, $flags ?? ''), []);
    }
}
