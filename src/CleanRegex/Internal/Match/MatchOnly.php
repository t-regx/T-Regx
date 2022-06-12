<?php
namespace TRegx\CleanRegex\Internal\Match;

use TRegx\CleanRegex\Internal\Definition;
use TRegx\CleanRegex\Internal\Limit;
use TRegx\CleanRegex\Internal\Pcre\Legacy\Base;
use TRegx\SafeRegex\preg;

class MatchOnly
{
    /** @var Definition */
    private $definition;
    /** @var Base */
    private $base;

    public function __construct(Definition $definition, Base $base)
    {
        $this->definition = $definition;
        $this->base = $base;
    }

    public function get(Limit $limit): array
    {
        if ($limit->empty()) {
            $this->validatePattern();
            return [];
        }
        if ($limit->intValue() === 1) {
            return $this->getOneMatch();
        }
        return \array_slice($this->base->matchAll()->getTexts(), 0, $limit->intValue());
    }

    private function validatePattern(): void
    {
        preg::match($this->definition->pattern, '');
    }

    private function getOneMatch(): array
    {
        $result = $this->base->match();
        if ($result->matched()) {
            return [$result->getText()];
        }
        return [];
    }
}
