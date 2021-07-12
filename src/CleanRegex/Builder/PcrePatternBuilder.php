<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Expression\Template;
use TRegx\CleanRegex\Internal\Prepared\Figure\InjectFigures;
use TRegx\CleanRegex\Internal\Prepared\Orthography\PcreOrthography;
use TRegx\CleanRegex\Pattern;

class PcrePatternBuilder
{
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
