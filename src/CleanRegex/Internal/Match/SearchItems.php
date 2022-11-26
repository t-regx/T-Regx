<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Match\Flat\FlatFunction;
use TRegx\CleanRegex\Internal\Predicate;

class SearchItems
{
    /** @var SearchBase */
    private $base;

    public function __construct(SearchBase $base)
    {
        $this->base = $base;
    }

    public function filter(Predicate $predicate): array
    {
        return \array_values(\array_filter($this->base->matchAllTexts(), [$predicate, 'test']));
    }

    public function flatMap(FlatFunction $function): array
    {
        return $function->flatMap($this->base->matchAllTexts());
    }
}
