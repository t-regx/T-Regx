<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Delimiter\Strategy\StandardStrategy;
use TRegx\CleanRegex\Internal\Prepared\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\CleanRegex\Pattern;

class PatternBuilder
{
    public function pcre(): PcrePatternBuilder
    {
        return new PcrePatternBuilder();
    }

    public function inject(string $input, array $values, string $flags = null): Pattern
    {
        return PrepareFacade::build(new InjectParser($input, $values, new NoTemplate()), new StandardStrategy($flags ?? ''));
    }

    public function mask(string $mask, array $keywords, string $flags = null): Pattern
    {
        return PrepareFacade::build(new MaskParser($mask, $keywords), new StandardStrategy($flags ?? ''));
    }

    public function template(string $pattern, string $flags = null): TemplateBuilder
    {
        return new TemplateBuilder($pattern, new StandardStrategy($flags ?? ''), []);
    }
}
