<?php
namespace TRegx\CleanRegex\Internal\Match;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Definition;
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

    public function get(int $limit): array
    {
        if ($limit < 0) {
            throw new InvalidArgumentException("Negative limit: $limit");
        }
        if ($limit === 0) {
            $this->validatePattern();
            return [];
        }
        if ($limit === 1) {
            return $this->getOneMatch();
        }
        return \array_slice($this->base->matchAll()->getTexts(), 0, $limit);
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
