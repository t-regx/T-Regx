<?php
namespace TRegx\CleanRegex\Internal\Match\Stream\Base;

use TRegx\CleanRegex\Exception\GroupNotMatchedException;
use TRegx\CleanRegex\Exception\IntegerFormatException;
use TRegx\CleanRegex\Exception\IntegerOverflowException;
use TRegx\CleanRegex\Exception\NonexistentGroupException;
use TRegx\CleanRegex\Exception\SubjectNotMatchedException;
use TRegx\CleanRegex\Internal\GroupKey\GroupKey;
use TRegx\CleanRegex\Internal\Match\Base\Base;
use TRegx\CleanRegex\Internal\Match\MatchAll\MatchAllFactory;
use TRegx\CleanRegex\Internal\Match\Stream\ListStream;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Number;
use TRegx\CleanRegex\Internal\Number\NumberFormatException;
use TRegx\CleanRegex\Internal\Number\NumberOverflowException;
use TRegx\CleanRegex\Internal\Number\StringNumber;

class MatchGroupIntStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Number\Base */
    private $numberBase;

    public function __construct(Base $base, GroupKey $group, MatchAllFactory $allFactory, Number\Base $numberBase)
    {
        $this->base = $base;
        $this->group = $group;
        $this->allFactory = $allFactory;
        $this->numberBase = $numberBase;
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
            throw GroupNotMatchedException::forFirst($this->group);
        }
        return $this->parseInteger($match->getGroup($this->group->nameOrIndex()));
    }

    private function parseInteger(string $string): int
    {
        $number = new StringNumber($string);
        try {
            return $number->asInt($this->numberBase);
        } catch (NumberFormatException $exception) {
            throw IntegerFormatException::forGroup($this->group, $string, $this->numberBase);
        } catch (NumberOverflowException $exception) {
            throw IntegerOverflowException::forGroup($this->group, $string, $this->numberBase);
        }
    }
}
