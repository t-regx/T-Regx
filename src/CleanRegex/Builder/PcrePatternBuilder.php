<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\PcreStrategy;
use TRegx\CleanRegex\Internal\Prepared\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Pattern;

class PcrePatternBuilder
{
    public function inject(string $input, array $values): Pattern
    {
        return PrepareFacade::build(new InjectParser($input, $values), new PcreStrategy());
    }

    public function template(string $pattern): TemplateBuilder
    {
        return new TemplateBuilder($pattern, new PcreStrategy(), []);
    }
}
