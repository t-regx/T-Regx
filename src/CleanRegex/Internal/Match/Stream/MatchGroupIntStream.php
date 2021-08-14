<?php
namespace TRegx\CleanRegex\Internal\Match\Stream;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Integer;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;

class MatchGroupIntStream implements Stream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;

    public function __construct(Base $base, GroupKey $group, MatchAllFactory $allFactory)
    {
        $this->base = $base;
        $this->group = $group;
        $this->allFactory = $allFactory;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if ($matches->hasGroup($this->group->nameOrIndex())) {
            return \array_map([$this, 'parseIntegerOptional'], $matches->getGroupTexts($this->group->nameOrIndex()));
        }
        throw new NonexistentGroupException($this->group);
    }

    private function parseIntegerOptional(?string $text): ?int
    {
        if ($text === null) {
            return null;
        }
        return $this->parseInteger($text);
    }

    protected function firstValue(): int
    {
        $match = $this->base->matchOffset();
        $polyfill = new GroupPolyfillDecorator(new FalseNegative($match), $this->allFactory, $match->getIndex());
        if (!$polyfill->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if (!$match->matched()) {
            throw SubjectNotMatchedException::forFirstGroup($this->base, $this->group);
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw GroupNotMatchedException::forFirst($this->base, $this->group);
        }
        return $this->parseInteger($match->getGroup($this->group->nameOrIndex()));
    }

    private function parseInteger(string $string): int
    {
        if (Integer::isValid($string)) {
            return $string;
        }
        throw IntegerFormatException::forGroup($this->group, $string);
    }
}
