<?php
namespace TRegx\CleanRegex\Internal\Match;

class Amount
{
    /** @var SearchBase */
    private $base;

    public function __construct(SearchBase $base)
    {
        $this->base = $base;
    }

    public function atLeastOne(): bool
    {
        return $this->base->matched();
    }

    public function none(): bool
    {
        return !$this->base->matched();
    }

    public function intValue(): int
    {
        return $this->base->count();
    }
}
