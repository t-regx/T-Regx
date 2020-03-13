<?php
namespace TRegx\CleanRegex\Match;

use InvalidArgumentException;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\SafeRegex\preg;
use function array_slice;

class MatchOnly
{
    /** @var Base */
    private $base;
    /** @var int */
    private $limit;

    public function __construct(Base $base, int $limit)
    {
        $this->base = $base;
        $this->limit = $limit;
    }

    /**
     * @return (string|null)[]
     */
    public function get(): array
    {
        if ($this->limit < 0) {
            throw new InvalidArgumentException("Negative limit: $this->limit");
        }
        if ($this->limit === 0) {
            $this->validatePattern();
            return [];
        }
        if ($this->limit === 1) {
            return $this->getOneMatch();
        }
        return $this->getSlicedAll();
    }

    private function validatePattern(): void
    {
        preg::match($this->base->getPattern()->pattern, '');
    }

    /**
     * @return (string|null)[]
     */
    private function getOneMatch(): array
    {
        $result = $this->base->match();
        if ($result->matched()) {
            return [$result->getText()];
        }
        return [];
    }

    /**
     * @return (string|null)[]
     */
    private function getSlicedAll(): array
    {
        return array_slice($this->getAll(), 0, $this->limit);
    }

    private function getAll(): array
    {
        return $this->base->matchAll()->getTexts();
    }
}
