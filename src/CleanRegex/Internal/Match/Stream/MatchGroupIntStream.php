<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;

class MatchGroupIntStream implements Stream
{
    /** @var Base */
    private $base;
    /** @var string|int */
    private $nameOrIndex;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, $nameOrIndex, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->nameOrIndex = $nameOrIndex;
        $this->allFactory = $allFactory;
    }

    /**
     * @return int[]
     */
    public function all(): array
    {
        $rawMatchesOffset = $this->base->matchAllOffsets();
        if ($rawMatchesOffset->hasGroup($this->nameOrIndex)) {
            return \array_map([$this, 'parseIntegerOptional'], $rawMatchesOffset->getGroupTexts($this->nameOrIndex));
        }
        throw new NonexistentGroupException($this->nameOrIndex);
    }

    private function parseIntegerOptional(?string $text): ?int
    {
        if ($text === null) {
            return null;
        }
        return $this->parseInteger($text);
    }

    public function first(): int
    {
        [$firstKey, $firstValue] = $this->firstTuple();
        return $firstValue;
    }

    public function firstKey(): int
    {
        [$firstKey, $firstValue] = $this->firstTuple();
        return $firstKey;
    }

    private function firstTuple(): array
    {
        $match = $this->base->matchOffset();
        $groupKey = $match->getIndex();
        $rawMatchOffset = new GroupPolyfillDecorator($match, $this->allFactory, $groupKey);
        if (!$rawMatchOffset->hasGroup($this->nameOrIndex)) {
            throw new NonexistentGroupException($this->nameOrIndex);
        }
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirstGroup($this->base, $this->nameOrIndex);
        }
        if (!$rawMatchOffset->isGroupMatched($this->nameOrIndex)) {
            throw GroupNotMatchedException::forFirst($this->base, $this->nameOrIndex);
        }
        $groupValue = $rawMatchOffset->getGroup($this->nameOrIndex);
        $this->parseInteger($groupValue);
        return [$groupKey, $groupValue];
    }

    private function parseInteger(string $text): int
    {
        if (Integer::isValid($text)) {
            return $text;
        }
        throw IntegerFormatException::forGroup($this->nameOrIndex, $text);
    }
}
