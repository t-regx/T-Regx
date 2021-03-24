<?php
namespace TRegx\CleanRegex\Builder;

use TRegx\CleanRegex\Internal\Prepared\Parser\BindingParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\InjectParser;
use TRegx\CleanRegex\Internal\Prepared\Parser\PreparedParser;
use TRegx\CleanRegex\Internal\Prepared\PrepareFacade;
use TRegx\CleanRegex\Internal\Prepared\Template\NoTemplate;
use TRegx\CleanRegex\PatternInterface;
use TRegx\CleanRegex\Template;

class PcrePatternBuilder
{
    public function bind(string $input, array $values): PatternInterface
    {
        return PrepareFacade::build(new BindingParser($input, $values, new NoTemplate()), true, '');
    }

    public function inject(string $input, array $values): PatternInterface
    {
        return PrepareFacade::build(new InjectParser($input, $values, new NoTemplate()), true, '');
    }

    public function prepare(array $input): PatternInterface
    {
        return PrepareFacade::build(new PreparedParser($input), true, '');
    }

    public function template(string $pattern): Template
    {
        return new Template($pattern, '', true);
    }
}
