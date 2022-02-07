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
use TRegx\CleanRegex\Internal\Match\Stream\StramRejectedException;
use TRegx\CleanRegex\Internal\Match\Stream\Upstream;
use TRegx\CleanRegex\Internal\Message\GroupNotMatched;
use TRegx\CleanRegex\Internal\Message\SubjectNotMatched\Group\FromFirstMatchIntMessage;
use TRegx\CleanRegex\Internal\Model\FalseNegative;
use TRegx\CleanRegex\Internal\Model\GroupPolyfillDecorator;
use TRegx\CleanRegex\Internal\Numeral;
use TRegx\CleanRegex\Internal\Numeral\NumeralFormatException;
use TRegx\CleanRegex\Internal\Numeral\NumeralOverflowException;
use TRegx\CleanRegex\Internal\Numeral\StringNumeral;

class MatchGroupIntStream implements Upstream
{
    use ListStream;

    /** @var Base */
    private $base;
    /** @var GroupKey */
    private $group;
    /** @var MatchAllFactory */
    private $allFactory;
    /** @var Numeral\Base */
    private $numberBase;

    public function __construct(Base $base, GroupKey $group, MatchAllFactory $allFactory, Numeral\Base $numberBase)
    {
        $this->base = $base;
        $this->group = $group;
        $this->allFactory = $allFactory;
        $this->numberBase = $numberBase;
    }

    protected function entries(): array
    {
        $matches = $this->base->matchAllOffsets();
        if (!$matches->hasGroup($this->group->nameOrIndex())) {
            throw new NonexistentGroupException($this->group);
        }
        if ($matches->matched()) {
            return \array_map([$this, 'parseIntegerOptional'], $matches->getGroupTexts($this->group->nameOrIndex()));
        }
        throw new UnmatchedStreamException();
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
            throw new StramRejectedException($this->base, SubjectNotMatchedException::class, new FromFirstMatchIntMessage($this->group));
        }
        if (!$polyfill->isGroupMatched($this->group->nameOrIndex())) {
            throw new StramRejectedException($this->base, GroupNotMatchedException::class, new GroupNotMatched\FromFirstMatchIntMessage($this->group));
        }
        return $this->parseInteger($match->getGroup($this->group->nameOrIndex()));
    }

    private function parseInteger(string $string): int
    {
        $number = new StringNumeral($string);
        try {
            return $number->asInt($this->numberBase);
        } catch (NumeralFormatException $exception) {
            throw IntegerFormatException::forGroup($this->group, $string, $this->numberBase);
        } catch (NumeralOverflowException $exception) {
            throw IntegerOverflowException::forGroup($this->group, $string, $this->numberBase);
        }
    }
}
