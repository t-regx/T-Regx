<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\MaskParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\CleanRegex\PatternInterface;
use TRegx\CleanRegex\Template;

class PatternBuilder
{
    public function pcre(): PcrePatternBuilder
    {
        return new PcrePatternBuilder();
    }

    public function bind(string $input, array $values, string $flags = null): PatternInterface
    {
        return PrepareFacade::build(new BindingParser($input, $values, new NoTemplate()), false, $flags ?? '');
    }

    public function inject(string $input, array $values, string $flags = null): PatternInterface
    {
        return PrepareFacade::build(new InjectParser($input, $values, new NoTemplate()), false, $flags ?? '');
    }

    public function prepare(array $input, string $flags = null): PatternInterface
    {
        return PrepareFacade::build(new PreparedParser($input), false, $flags ?? '');
    }

    public function mask(string $mask, array $keywords, string $flags = null): PatternInterface
    {
        return PrepareFacade::build(new MaskParser($mask, $keywords), false, $flags ?? '');
    }

    public function template(string $pattern, string $flags = null): Template
    {
        return new Template($pattern, $flags ?? '', false);
    }
}
